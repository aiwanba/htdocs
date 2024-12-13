<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

$error = '';

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取所有上市公司的股票信息
    $stmt = $db->prepare(
        "SELECT s.*, c.name as company_name,
                (s.current_price - s.open_price) / s.open_price * 100 as price_change,
                s.current_price * s.total_shares as market_value,
                (SELECT COALESCE(SUM(amount), 0) FROM cs_transactions 
                 WHERE stock_id = s.id 
                 AND DATE(created_at) = CURDATE()) as volume
         FROM cs_stocks s
         JOIN cs_companies c ON s.company_id = c.id
         WHERE s.status = 'active'
         ORDER BY market_value DESC"
    );
    $stmt->execute();
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 获取市场统计数据
    $stmt = $db->prepare(
        "SELECT 
            COUNT(*) as total_stocks,
            COALESCE(SUM(s.current_price * s.total_shares), 0) as total_market_value,
            COALESCE(SUM(
                SELECT SUM(amount * price) 
                FROM cs_transactions 
                WHERE stock_id = s.id 
                AND DATE(created_at) = CURDATE()
            ), 0) as today_volume
         FROM cs_stocks s
         WHERE s.status = 'active'"
    );
    $stmt->execute();
    $market_stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 设置页面标题
    $page_title = '股票市场';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/stock/market.php'; 