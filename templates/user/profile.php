<!DOCTYPE html>
<html>
<head>
    <title>个人资料 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <div class="profile-form">
            <h2>个人资料</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/user/profile">
                <div class="form-group">
                    <label>用户名:</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    <small>用户名不可修改</small>
                </div>
                
                <div class="form-group">
                    <label>邮箱:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>当前密码:</label>
                    <input type="password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label>新密码:</label>
                    <input type="password" name="new_password" minlength="6">
                    <small>如不修改密码请留空</small>
                </div>
                
                <div class="form-group">
                    <label>确认新密码:</label>
                    <input type="password" name="confirm_password">
                </div>
                
                <button type="submit" class="btn-submit">保存修改</button>
            </form>
            
            <div class="profile-info">
                <h3>账户信息</h3>
                <div class="info-item">
                    <label>账户余额:</label>
                    <span><?php echo format_money($user['balance']); ?></span>
                </div>
                <div class="info-item">
                    <label>注册时间:</label>
                    <span><?php echo format_datetime($user['created_at']); ?></span>
                </div>
                <div class="info-item">
                    <label>最后登录:</label>
                    <span><?php echo format_datetime($user['last_active']); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 