<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';
$company_id = isset($parts[2]) ? (int)$parts[2] : 0;

if ($company_id <= 0) {
    header('Location: /user/company');
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取公司详情
    $stmt = $db->prepare(
        "SELECT c.*, u.username as owner_name,
                s.current_price, s.total_shares,
                s.current_price * s.total_shares as market_value,
                s.open_price, s.high_price, s.low_price,
                s.list_time, s.status as stock_status
         FROM cs_companies c
         LEFT JOIN cs_users u ON c.owner_id = u.id
         LEFT JOIN cs_stocks s ON c.id = s.company_id
         WHERE c.id = ?"
    );
    $stmt->execute([$company_id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        throw new Exception('公司不存在');
    }
    
    // 获取最近交易记录
    $stmt = $db->prepare(
        "SELECT t.*, u.username
         FROM cs_transactions t
         JOIN cs_users u ON t.user_id = u.id
         WHERE t.stock_id IN (SELECT id FROM cs_stocks WHERE company_id = ?)
         ORDER BY t.created_at DESC
         LIMIT 10"
    );
    $stmt->execute([$company_id]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 设置页面标题
    $page_title = $company['name'] . ' - 公司详情';
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/company/view.php'; 