<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/monitor.php';
require_once 'lib/utils.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$monitor = new SystemMonitor();

// 获取监控数据
$metrics = $monitor->getSystemMetrics();
$online_users = $monitor->getOnlineUsers();
$trade_stats = $monitor->getTradeStats();
$alerts = $monitor->getAlerts();

// AJAX请求返回JSON数据
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'metrics' => $metrics,
        'online_users' => $online_users,
        'trade_stats' => $trade_stats,
        'alerts' => $alerts
    ]);
    exit;
}
?>

<?php include 'templates/admin/monitor.php'; ?> 