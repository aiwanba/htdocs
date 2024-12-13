<!DOCTYPE html>
<html>
<head>
    <title>注册 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <div class="auth-form">
            <h2>用户注册</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/register.php">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label>用户名:</label>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <small>用户名只能包含字母、数字和下划线</small>
                </div>
                
                <div class="form-group">
                    <label>邮箱:</label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>密码:</label>
                    <input type="password" name="password" required minlength="6">
                    <small>密码长度不能少于6个字符</small>
                </div>
                
                <div class="form-group">
                    <label>确认密码:</label>
                    <input type="password" name="confirm_password" required minlength="6">
                </div>
                
                <button type="submit" class="btn-submit">注册</button>
            </form>
            
            <div class="auth-links">
                已有账号？<a href="/login.php">立即登录</a>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 