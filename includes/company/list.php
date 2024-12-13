<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

$error = '';

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取所有公司列表
    $stmt = $db->prepare(
        "SELECT c.*, u.username as owner_name,
                s.current_price, s.total_shares,
                s.current_price * s.total_shares as market_value,
                (SELECT COUNT(*) FROM cs_stocks WHERE company_id = c.id) as is_listed
         FROM cs_companies c
         LEFT JOIN cs_users u ON c.owner_id = u.id
         LEFT JOIN cs_stocks s ON c.id = s.company_id
         WHERE c.status = 'active'
         ORDER BY c.created_at DESC"
    );
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 设置页面标题
    $page_title = '公司列表';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/company/list.php'; 