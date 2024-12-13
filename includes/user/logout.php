<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // 记录登出日志
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare(
        "INSERT INTO user_logs (user_id, action, ip_address, created_at)
         VALUES (?, 'logout', ?, NOW())"
    );
    $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
}

// 清除所有会话数据
session_destroy();

// 重定向到首页
header('Location: /');
exit;
 