<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 用户注册</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="register-container">
        <h2>用户注册</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/user/register.php">
            <div class="form-group">
                <label>用户名:</label>
                <input type="text" name="username" required minlength="4">
                <small>至少4个字符</small>
            </div>
            
            <div class="form-group">
                <label>密码:</label>
                <input type="password" name="password" required minlength="6">
                <small>至少6个字符</small>
            </div>
            
            <div class="form-group">
                <label>电子邮箱:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <button type="submit">注册</button>
                <a href="/user/login.php">返回登录</a>
            </div>
        </form>
    </div>
</body>
</html> 