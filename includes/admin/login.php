<?php
require_once 'lib/admin/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    
    $auth = new AdminAuth();
    $result = $auth->login($username, $password);
    
    if ($result['success']) {
        header('Location: /admin/index.php');
        exit;
    }
    $error = $result['message'];
}
?>

<?php include 'templates/admin/login.php'; ?> 