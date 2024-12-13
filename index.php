<?php
session_start();
require_once 'config/site.php';
require_once 'config/database.php';
require_once 'lib/utils.php';

// 设置时区
date_default_timezone_set(TIMEZONE);

// 设置错误报告
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// 路由处理
$page = isset($_GET['page']) ? clean_input($_GET['page']) : 'home';
$action = isset($_GET['action']) ? clean_input($_GET['action']) : 'index';

// 根据路由加载相应的控制器
switch ($page) {
    case 'user':
        require_once 'includes/user/' . $action . '.php';
        break;
    case 'trade':
        require_once 'includes/trade/' . $action . '.php';
        break;
    case 'company':
        require_once 'includes/company/' . $action . '.php';
        break;
    case 'stock':
        require_once 'includes/stock/' . $action . '.php';
        break;
    default:
        require_once 'includes/common/home.php';
} 