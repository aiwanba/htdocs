<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

$error = '';
$success = '';

// 获取用户的公司列表
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare(
    "SELECT c.*, s.current_price, s.total_shares,
            s.current_price * s.total_shares as market_value
     FROM cs_companies c
     LEFT JOIN cs_stocks s ON c.id = s.company_id
     WHERE c.owner_id = ?
     ORDER BY c.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 设置页面标题
$page_title = '我的公司';

include 'templates/user/company.php'; 