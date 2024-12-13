<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';

$error = '';
$success = '';

// 获取用户信息
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare(
    "SELECT * FROM users WHERE id = ?"
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $current_password = clean_input($_POST['current_password']);
    $new_password = clean_input($_POST['new_password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    
    // 验证当前密码
    if (!verify_password($current_password, $user['password'])) {
        $error = '当前密码不正确';
    } elseif (!empty($new_password)) {
        // 如果要修改密码
        if (strlen($new_password) < 6) {
            $error = '新密码长度不能少于6个字符';
        } elseif ($new_password !== $confirm_password) {
            $error = '两次输入的新密码不一致';
        }
    }
    
    if (empty($error)) {
        try {
            $db->beginTransaction();
            
            // 更新邮箱
            if ($email !== $user['email']) {
                $stmt = $db->prepare(
                    "SELECT id FROM users WHERE email = ? AND id != ?"
                );
                $stmt->execute([$email, $_SESSION['user_id']]);
                if ($stmt->rowCount() > 0) {
                    throw new Exception('该邮箱已被使用');
                }
                
                $stmt = $db->prepare(
                    "UPDATE users SET email = ? WHERE id = ?"
                );
                $stmt->execute([$email, $_SESSION['user_id']]);
            }
            
            // 更新密码
            if (!empty($new_password)) {
                $hash = generate_password_hash($new_password);
                $stmt = $db->prepare(
                    "UPDATE users SET password = ? WHERE id = ?"
                );
                $stmt->execute([$hash, $_SESSION['user_id']]);
            }
            
            // 记录日志
            $stmt = $db->prepare(
                "INSERT INTO user_logs (user_id, action, ip_address, created_at)
                 VALUES (?, 'update_profile', ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
            
            $db->commit();
            $success = '个人资料更新成功';
            
            // 重新获取用户信息
            $stmt = $db->prepare(
                "SELECT * FROM users WHERE id = ?"
            );
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $db->rollBack();
            $error = $e->getMessage();
        }
    }
}

include 'templates/user/profile.php'; 