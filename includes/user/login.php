<?php
require_once 'lib/user/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    
    $auth = new UserAuth();
    $result = $auth->login($username, $password);
    
    if ($result['success']) {
        header('Location: /index.php');
        exit;
    }
    $error = $result['message'];
}
?>

<?php include 'templates/user/login.php'; ?> 