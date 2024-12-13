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

// 获取请求的路径
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = trim($request_uri, '/');

// 如果是直接访问特定PHP文件，去掉.php后缀
if (preg_match('/^(register|login|logout)(\.php)?$/', $request_uri, $matches)) {
    $page = $matches[1];
} else {
    // 解析路由参数
    $parts = explode('/', $request_uri);
    $page = !empty($parts[0]) ? clean_input($parts[0]) : 'home';
    $action = isset($parts[1]) ? clean_input($parts[1]) : 'index';
}

// 检查维护模式
if (file_exists('config/maintenance.php') && require('config/maintenance.php')) {
    if (!isset($_SESSION['admin_id'])) {
        include 'templates/common/maintenance.php';
        exit;
    }
}

// 需要登录的页面
$auth_required = ['user', 'trade', 'company'];

// 检查登录状态
if (in_array($page, $auth_required) && !isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// 根据路由加载相应的控制器
try {
    switch ($page) {
        case 'register':
            if (isset($_SESSION['user_id'])) {
                header('Location: /');
                exit;
            }
            require_once 'includes/user/register.php';
            break;
            
        case 'login':
            if (isset($_SESSION['user_id'])) {
                header('Location: /');
                exit;
            }
            require_once 'includes/user/login.php';
            break;
            
        case 'logout':
            require_once 'includes/user/logout.php';
            break;
            
        case 'user':
            // 验证action是否合法
            $allowed_actions = ['profile', 'settings', 'portfolio', 'orders', 'company'];
            if (!in_array($action, $allowed_actions)) {
                throw new Exception('无效的操作');
            }
            require_once 'includes/user/' . $action . '.php';
            break;
            
        case 'trade':
            // 验证action是否合法
            $allowed_actions = ['buy', 'sell', 'history'];
            if (!in_array($action, $allowed_actions)) {
                throw new Exception('无效的操作');
            }
            require_once 'includes/trade/' . $action . '.php';
            break;
            
        case 'company':
            // 验证action是否合法
            $allowed_actions = ['create', 'edit', 'list', 'view'];
            if (!in_array($action, $allowed_actions)) {
                throw new Exception('无效的操作');
            }
            require_once 'includes/company/' . $action . '.php';
            break;
            
        case 'stock':
            // 验证action是否合法
            $allowed_actions = ['view', 'ipo', 'market'];
            if (!in_array($action, $allowed_actions)) {
                throw new Exception('无效的操作');
            }
            require_once 'includes/stock/' . $action . '.php';
            break;
            
        case 'admin':
            // 验证管理员登录
            if (!isset($_SESSION['admin_id'])) {
                header('Location: /admin/login.php');
                exit;
            }
            require_once 'includes/admin/' . $action . '.php';
            break;
            
        case 'home':
            require_once 'includes/common/home.php';
            break;
            
        default:
            throw new Exception('页面不存在');
    }
} catch (Exception $e) {
    // 记录错误
    error_log($e->getMessage());
    
    // 显示错误页面
    $error_message = DEBUG_MODE ? $e->getMessage() : '系统错误';
    include 'templates/common/error.php';
} 