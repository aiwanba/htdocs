<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/logs.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$log_manager = new LogManager();

// 获取筛选参数
$type = isset($_GET['type']) ? clean_input($_GET['type']) : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;

// 获取日志数据
$logs = $log_manager->getLogs($page, $limit, $type);
$total_logs = $log_manager->getTotalLogs($type);
$total_pages = ceil($total_logs / $limit);

// 获取日志类型列表用于筛选
$log_types = $log_manager->getLogTypes();
?>

<?php include 'templates/admin/logs.php'; ?> 