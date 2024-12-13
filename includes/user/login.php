<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        verify_csrf_request();
        
        $username = clean_input($_POST['username']);
        $password = clean_input($_POST['password']);
        
        if (empty($username) || empty($password)) {
            $error = '请输入用户名和密码';
        } else {
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare(
                "SELECT id, username, password, balance, status 
                 FROM cs_users 
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
                        "UPDATE cs_users SET last_active = NOW() WHERE id = ?"
                    );
                    $stmt->execute([$user['id']]);
                    
                    // 记录登录日志
                    $stmt = $db->prepare(
                        "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
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
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'templates/user/login.php'; 