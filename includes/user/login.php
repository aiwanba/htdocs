<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $error = '无效的请求';
    } else {
        $username = clean_input($_POST['username']);
        $password = clean_input($_POST['password']);
        
        if (empty($username) || empty($password)) {
            $error = '请输入用户名和密码';
        } else {
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare(
                "SELECT id, username, password, balance, status 
                 FROM users 
                 WHERE username = ?"
            );
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && verify_password($password, $user['password'])) {
                if ($user['status'] == 'blocked') {
                    $error = '账号已被禁用';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['balance'] = $user['balance'];
                    
                    // 更新最后登录时间
                    $stmt = $db->prepare(
                        "UPDATE users SET last_active = NOW() WHERE id = ?"
                    );
                    $stmt->execute([$user['id']]);
                    
                    // 记录登录日志
                    $stmt = $db->prepare(
                        "INSERT INTO user_logs (user_id, action, ip_address, created_at)
                         VALUES (?, 'login', ?, NOW())"
                    );
                    $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR']]);
                    
                    header('Location: /');
                    exit;
                }
            } else {
                $error = '用户名或密码错误';
            }
        }
    }
}

include 'templates/user/login.php'; 