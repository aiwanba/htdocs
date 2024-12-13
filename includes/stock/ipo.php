<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';
$success = '';
$company_id = isset($parts[2]) ? (int)$parts[2] : 0;

if ($company_id <= 0) {
    header('Location: /user/company');
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取公司信息
    $stmt = $db->prepare(
        "SELECT c.*, s.id as stock_id 
         FROM cs_companies c
         LEFT JOIN cs_stocks s ON c.id = s.company_id
         WHERE c.id = ? AND c.owner_id = ?"
    );
    $stmt->execute([$company_id, $_SESSION['user_id']]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        throw new Exception('公司不存在或您没有权限');
    }
    
    if ($company['stock_id']) {
        throw new Exception('该公司已经上市');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        verify_csrf_request();
        
        $total_shares = (int)clean_input($_POST['total_shares']);
        $price = (float)clean_input($_POST['price']);
        
        if ($total_shares < 10000) {
            throw new Exception('发行股数不能少于10000股');
        }
        
        if ($price <= 0) {
            throw new Exception('发行价格必须大于0');
        }
        
        $db->beginTransaction();
        
        try {
            // 创建股票记录
            $stmt = $db->prepare(
                "INSERT INTO cs_stocks (company_id, total_shares, current_price, open_price, 
                                    high_price, low_price, status, list_time, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, 'active', NOW(), NOW(), NOW())"
            );
            $stmt->execute([
                $company_id, $total_shares, $price, $price, $price, $price
            ]);
            
            // 记录日志
            $stmt = $db->prepare(
                "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
                 VALUES (?, 'ipo', ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
            
            $db->commit();
            
            // 跳转到公司详情页
            header('Location: /company/view/' . $company_id);
            exit;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    // 设置页面标题
    $page_title = $company['name'] . ' - 申请上市';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/stock/ipo.php'; 