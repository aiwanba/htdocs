<!DOCTYPE html>
<html>
<head>
    <title>登录 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <div class="auth-form">
            <h2>用户登录</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/login.php">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label>用户名:</label>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>密码:</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-submit">登录</button>
            </form>
            
            <div class="auth-links">
                <a href="/register.php">注册新账号</a>
                <a href="/forgot-password.php">忘记密码？</a>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 