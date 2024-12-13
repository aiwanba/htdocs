<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';
$success = '';
$stock_id = isset($parts[2]) ? (int)$parts[2] : 0;

if ($stock_id <= 0) {
    header('Location: /market');
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取股票信息
    $stmt = $db->prepare(
        "SELECT s.*, c.name as company_name, c.owner_id
         FROM cs_stocks s
         JOIN cs_companies c ON s.company_id = c.id
         WHERE s.id = ? AND s.status = 'active'"
    );
    $stmt->execute([$stock_id]);
    $stock = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$stock) {
        throw new Exception('股票不存在或已停牌');
    }
    
    // 不能购买自己公司的股票
    if ($stock['owner_id'] == $_SESSION['user_id']) {
        throw new Exception('不能购买自己公司的股票');
    }
    
    // 获取用户余额
    $stmt = $db->prepare("SELECT balance FROM cs_users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        throw new Exception('用户数据获取失败');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        verify_csrf_request();
        
        $amount = (int)clean_input($_POST['amount']);
        $price = (float)clean_input($_POST['price']);
        
        if ($amount <= 0) {
            throw new Exception('购买数量必须大于0');
        }
        
        if ($price <= 0) {
            throw new Exception('购买价格必须大于0');
        }
        
        $total_cost = $amount * $price;
        if ($total_cost > $user['balance']) {
            throw new Exception('余额不足');
        }
        
        $db->beginTransaction();
        
        try {
            // 创建交易记录
            $stmt = $db->prepare(
                "INSERT INTO cs_transactions (user_id, stock_id, type, amount, price, 
                                         total_amount, status, created_at, updated_at)
                 VALUES (?, ?, 'buy', ?, ?, ?, 'pending', NOW(), NOW())"
            );
            $stmt->execute([
                $_SESSION['user_id'], $stock_id, $amount, $price, $total_cost
            ]);
            
            // 扣除用户余额
            $stmt = $db->prepare(
                "UPDATE cs_users SET balance = balance - ? WHERE id = ?"
            );
            $stmt->execute([$total_cost, $_SESSION['user_id']]);
            
            // 记录日志
            $stmt = $db->prepare(
                "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
                 VALUES (?, 'buy_stock', ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
            
            $db->commit();
            
            $success = '下单成功';
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    // 设置页面标题
    $page_title = '买入 - ' . $stock['company_name'];
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/trade/buy.php'; 