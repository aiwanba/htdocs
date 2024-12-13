<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/user.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$user_id = clean_input($_GET['id'] ?? '');
if (!$user_id) {
    header('Location: /admin/users.php');
    exit;
}

$user_manager = new UserManager();
$user = $user_manager->getUserDetail($user_id);

if (!$user) {
    header('Location: /admin/users.php?error=user_not_found');
    exit;
}

// 获取用户的公司列表
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare(
    "SELECT c.*, s.total_shares, s.current_price
     FROM companies c
     LEFT JOIN stocks s ON c.id = s.company_id
     WHERE c.owner_id = ?
     ORDER BY c.created_at DESC"
);
$stmt->execute([$user_id]);
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取最近的交易记录
$stmt = $db->prepare(
    "SELECT t.*, s.company_id, c.name as company_name
     FROM transactions t
     JOIN stocks s ON t.stock_id = s.id
     JOIN companies c ON s.company_id = c.id
     WHERE t.user_id = ?
     ORDER BY t.created_at DESC
     LIMIT 10"
);
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'templates/admin/user_detail.php'; ?> 