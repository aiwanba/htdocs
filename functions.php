<?php
// 安全过滤函数
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 生成密码哈希
function generate_password_hash($password) {
    return hash(PASSWORD_HASH_ALGO, $password);
}

// 验证用户登录状态
function check_login() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /user/login.php');
        exit();
    }
}

// 格式化金额
function format_money($amount) {
    return number_format($amount, 2);
}

// 记录系统日志
function log_action($user_id, $action, $details) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO user_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
} 