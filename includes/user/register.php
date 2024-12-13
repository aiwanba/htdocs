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
        $email = clean_input($_POST['email']);
        $password = clean_input($_POST['password']);
        $confirm_password = clean_input($_POST['confirm_password']);
        
        // 验证输入
        if (empty($username) || empty($email) || empty($password)) {
            $error = '所有字段都必须填写';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = '邮箱格式不正确';
        } elseif ($password !== $confirm_password) {
            $error = '两次输入的密码不一致';
        } elseif (strlen($password) < 6) {
            $error = '密码长度不能少于6个字符';
        } else {
            $db = Database::getInstance()->getConnection();
            
            // 检查用户名是否已存在
            $stmt = $db->prepare("SELECT id FROM cs_users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                $error = '用户名已被使用';
            } else {
                // 检查邮箱是否已存在
                $stmt = $db->prepare("SELECT id FROM cs_users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $error = '邮箱已被注册';
                } else {
                    // 创建用户
                    try {
                        $stmt = $db->prepare(
                            "INSERT INTO cs_users (username, email, password, status, created_at, updated_at)
                             VALUES (?, ?, ?, 'active', NOW(), NOW())"
                        );
                        $hash = generate_password_hash($password);
                        $stmt->execute([$username, $email, $hash]);
                        
                        $success = true;
                        
                        // 自动登录
                        $_SESSION['user_id'] = $db->lastInsertId();
                        $_SESSION['username'] = $username;
                        $_SESSION['balance'] = 0;
                        
                        // 记录日志
                        $stmt = $db->prepare(
                            "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
                             VALUES (?, 'register', ?, NOW())"
                        );
                        $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
                        
                        // 跳转到首页
                        header('Location: /');
                        exit;
                    } catch (Exception $e) {
                        $error = '注册失败，请稍后重试';
                    }
                }
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'templates/user/register.php'; 