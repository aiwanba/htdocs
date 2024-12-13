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
        "SELECT s.*, c.name as company_name, c.owner_id,
                h.amount as holding_amount
         FROM cs_stocks s
         JOIN cs_companies c ON s.company_id = c.id
         LEFT JOIN cs_holdings h ON s.id = h.stock_id AND h.user_id = ?
         WHERE s.id = ? AND s.status = 'active'"
    );
    $stmt->execute([$_SESSION['user_id'], $stock_id]);
    $stock = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$stock) {
        throw new Exception('股票不存在或已停牌');
    }
    
    if ($stock['owner_id'] == $_SESSION['user_id']) {
        throw new Exception('不能卖出自己公司的股票');
    }
    
    if (empty($stock['holding_amount'])) {
        throw new Exception('您没有持有该股票');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        verify_csrf_request();
        
        $amount = (int)clean_input($_POST['amount']);
        $price = (float)clean_input($_POST['price']);
        
        if ($amount <= 0) {
            throw new Exception('卖出数量必须大于0');
        }
        
        if ($amount > $stock['holding_amount']) {
            throw new Exception('卖出数量不能大于持有数量');
        }
        
        if ($price <= 0) {
            throw new Exception('卖出价格必须大于0');
        }
        
        $total_amount = $amount * $price;
        
        $db->beginTransaction();
        
        try {
            // 创建交易记录
            $stmt = $db->prepare(
                "INSERT INTO cs_transactions (user_id, stock_id, type, amount, price, 
                                         total_amount, status, created_at, updated_at)
                 VALUES (?, ?, 'sell', ?, ?, ?, 'pending', NOW(), NOW())"
            );
            $stmt->execute([
                $_SESSION['user_id'], $stock_id, $amount, $price, $total_amount
            ]);
            
            // 更新用户持仓
            $stmt = $db->prepare(
                "UPDATE cs_holdings 
                 SET amount = amount - ?, updated_at = NOW()
                 WHERE user_id = ? AND stock_id = ?"
            );
            $stmt->execute([$amount, $_SESSION['user_id'], $stock_id]);
            
            // 记录日志
            $stmt = $db->prepare(
                "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
                 VALUES (?, 'sell_stock', ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
            
            $db->commit();
            
            $success = '下单成功';
            $stock['holding_amount'] -= $amount;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    // 设置页面标题
    $page_title = '卖出 - ' . $stock['company_name'];
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/trade/sell.php'; 