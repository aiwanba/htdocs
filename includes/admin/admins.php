<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/manager.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$admin_manager = new AdminManager();

// 处理添加管理员
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $result = $admin_manager->addAdmin(
                    clean_input($_POST['username']),
                    clean_input($_POST['password']),
                    clean_input($_POST['role'])
                );
                break;
                
            case 'status':
                $result = $admin_manager->updateAdminStatus(
                    clean_input($_POST['admin_id']),
                    clean_input($_POST['status'])
                );
                break;
        }
        
        if ($result['success']) {
            $message = '操作成功';
            $success = true;
        } else {
            $error = $result['message'];
        }
    }
}

// 获取管理员列表
$admins = $admin_manager->getAdminList();
?>

<?php include 'templates/admin/admins.php'; ?> 