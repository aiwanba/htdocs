<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 用户登录</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="login-container">
        <h2>用户登录</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/user/login.php">
            <div class="form-group">
                <label>用户名:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>密码:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <button type="submit">登录</button>
                <a href="/user/register.php">注册新账号</a>
            </div>
        </form>
    </div>
</body>
</html> 