<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/ipo.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$ipo_manager = new IPOManager();

// 处理审核操作
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_id = clean_input($_POST['application_id']);
    $approved = $_POST['action'] == 'approve';
    $reason = clean_input($_POST['reason'] ?? '');
    
    $result = $ipo_manager->reviewApplication($application_id, $approved, $reason);
    if ($result['success']) {
        $message = $approved ? 'IPO申请已通过' : 'IPO申请已拒绝';
        $success = true;
    } else {
        $error = $result['message'];
    }
}

// 获取待审核的申请列表
$pending_applications = $ipo_manager->getPendingApplications();
?>

<?php include 'templates/admin/ipo.php'; ?>
