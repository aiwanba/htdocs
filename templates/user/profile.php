<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 个人资料</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="profile-container">
        <h2>个人资料</h2>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-info">
            <h3>基本信息</h3>
            <form method="POST" action="/user/profile.php">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-group">
                    <label>用户名:</label>
                    <input type="text" value="<?php echo $user_data['username']; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>电子邮箱:</label>
                    <input type="email" name="email" value="<?php echo $user_data['email']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>账户余额:</label>
                    <input type="text" value="<?php echo format_money($user_data['balance']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>注册时间:</label>
                    <input type="text" value="<?php echo $user_data['created_at']; ?>" readonly>
                </div>
                
                <button type="submit">更新资料</button>
            </form>
        </div>
        
        <div class="change-password">
            <h3>修改密码</h3>
            <form method="POST" action="/user/profile.php">
                <input type="hidden" name="action" value="change_password">
                
                <div class="form-group">
                    <label>原密码:</label>
                    <input type="password" name="old_password" required>
                </div>
                
                <div class="form-group">
                    <label>新密码:</label>
                    <input type="password" name="new_password" required minlength="6">
                </div>
                
                <button type="submit">修改密码</button>
            </form>
        </div>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 