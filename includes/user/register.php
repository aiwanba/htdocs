<?php
require_once 'lib/user/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    $email = clean_input($_POST['email']);
    
    // 基本验证
    if (strlen($username) < 4) {
        $error = '用户名至少需要4个字符';
    } elseif (strlen($password) < 6) {
        $error = '密码至少需要6个字符';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '邮箱格式不正确';
    } else {
        $auth = new UserAuth();
        $result = $auth->register($username, $password, $email);
        
        if ($result['success']) {
            header('Location: /user/login.php?msg=register_success');
            exit;
        }
        $error = $result['message'];
    }
}
?>

<?php include 'templates/user/register.php'; ?> 