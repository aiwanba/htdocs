<?php
require_once 'lib/company/manage.php';

check_login();

$db = Database::getInstance()->getConnection();

// 获取用户创建的公司列表
$stmt = $db->prepare(
    "SELECT c.*, 
            COALESCE(s.total_shares, 0) as total_shares,
            COALESCE(s.current_price, 0) as stock_price
     FROM companies c
     LEFT JOIN stocks s ON c.id = s.company_id
     WHERE c.owner_id = ?
     ORDER BY c.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取IPO申请状态
$stmt = $db->prepare(
    "SELECT * FROM ipo_applications 
     WHERE company_id IN (SELECT id FROM companies WHERE owner_id = ?)
     AND status = 'pending'"
);
$stmt->execute([$_SESSION['user_id']]);
$pending_ipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'templates/company/manage.php'; ?> 