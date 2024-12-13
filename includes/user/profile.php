<?php
require_once 'lib/user/profile.php';

// 检查登录状态
check_login();

$profile = new UserProfile($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $result = $profile->updateProfile([
                    'email' => clean_input($_POST['email'])
                ]);
                break;
                
            case 'change_password':
                $result = $profile->changePassword(
                    clean_input($_POST['old_password']),
                    clean_input($_POST['new_password'])
                );
                break;
        }
        
        $message = $result['message'];
        $success = $result['success'];
    }
}

$user_data = $profile->getProfile();
?>

<?php include 'templates/user/profile.php'; ?> 