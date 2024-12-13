<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取用户订单
    $stmt = $db->prepare(
        "SELECT t.*, c.name as company_name
         FROM cs_transactions t
         JOIN cs_stocks s ON t.stock_id = s.id
         JOIN cs_companies c ON s.company_id = c.id
         WHERE t.user_id = ?
         ORDER BY t.created_at DESC
         LIMIT 50"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 设置页面标题
    $page_title = '交易记录';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/user/orders.php'; 