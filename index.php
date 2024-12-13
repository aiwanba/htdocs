<?php
session_start();
require_once 'config/site.php';
require_once 'config/database.php';
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

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

// 定义路由规则
$routes = [
    'user' => [
        'login', 'register', 'logout', 'profile', 'company', 'portfolio', 'orders'
    ],
    'company' => [
        'create', 'edit', 'view', 'list'
    ],
    'stock' => [
        'ipo'
    ],
    'trade' => [
        'buy', 'sell', 'cancel', 'history'
    ],
    'market' => [
        'index'
    ]
];

// 解析 URL
$parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$module = $parts[0] ?? '';
$action = $parts[1] ?? 'index';

// 检查路由是否有效
if (empty($module)) {
    $module = 'market';
    $action = 'index';
}

// 检查模块和操作是否存在
if (!isset($routes[$module]) || 
    ($action != 'index' && !in_array($action, $routes[$module]))) {
    include 'templates/error/404.php';
    exit;
}

// 加载控制器
$controller_file = "includes/{$module}/{$action}.php";
if (!file_exists($controller_file)) {
    include 'templates/error/404.php';
    exit;
}

require_once $controller_file; 