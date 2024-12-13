<?php
// 只在函数未定义时才定义
if (!function_exists('format_money')) {
    // 格式化金额
    function format_money($amount) {
        return '¥' . number_format($amount, 2);
    }
}

if (!function_exists('format_size')) {
    // 格式化文件大小
    function format_size($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

if (!function_exists('format_datetime')) {
    // 格式化日期时间
    function format_datetime($datetime) {
        return date('Y-m-d H:i:s', strtotime($datetime));
    }
}

if (!function_exists('clean_input')) {
    // 清理输入数据
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if (!function_exists('generate_password_hash')) {
    // 生成密码哈希
    function generate_password_hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password')) {
    // 验证密码
    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
} 