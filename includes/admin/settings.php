<?php
require_once 'lib/admin/auth.php';
require_once 'lib/admin/settings.php';

// 验证管理员权限
$auth = new AdminAuth();
$auth->checkAdmin();

$settings_manager = new SystemSettings();

// 处理设置更新
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_settings = [
        'trade_fee_rate' => clean_input($_POST['trade_fee_rate']),
        'min_ipo_capital' => clean_input($_POST['min_ipo_capital']),
        'price_limit_rate' => clean_input($_POST['price_limit_rate']),
        'max_companies_per_user' => clean_input($_POST['max_companies_per_user']),
        'session_lifetime' => clean_input($_POST['session_lifetime'])
    ];
    
    $result = $settings_manager->updateSettings($new_settings);
    if ($result['success']) {
        $message = '系统设置更新成功';
        $success = true;
    } else {
        $error = $result['message'];
    }
}

// 获取当前设置和统计数据
$settings = $settings_manager->getSettings();
$stats = $settings_manager->getSystemStats();
?>

<?php include 'templates/admin/settings.php'; ?> 