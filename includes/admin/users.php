<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/user.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$user_manager = new UserManager();

// 处理用户状态更新
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = clean_input($_POST['user_id']);
    $status = clean_input($_POST['status']);
    
    $result = $user_manager->updateUserStatus($user_id, $status);
    if ($result['success']) {
        $message = '用户状态更新成功';
        $success = true;
    } else {
        $error = $result['message'];
    }
}

// 分页处理
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$total_users = $user_manager->getTotalUsers();
$total_pages = ceil($total_users / $limit);

// 获取用户列表
$users = $user_manager->getUserList($page, $limit);
?>

<?php include 'templates/admin/users.php'; ?> 