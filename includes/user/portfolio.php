<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取用户持仓
    $stmt = $db->prepare(
        "SELECT h.*, s.current_price, s.open_price,
                c.name as company_name,
                h.amount * s.current_price as market_value,
                (s.current_price - s.open_price) / s.open_price * 100 as price_change
         FROM cs_holdings h
         JOIN cs_stocks s ON h.stock_id = s.id
         JOIN cs_companies c ON s.company_id = c.id
         WHERE h.user_id = ? AND h.amount > 0
         ORDER BY market_value DESC"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $holdings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 计算总市值
    $total_value = 0;
    foreach ($holdings as $holding) {
        $total_value += $holding['market_value'];
    }
    
    // 设置页面标题
    $page_title = '我的持仓';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/user/portfolio.php'; 