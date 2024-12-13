<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 管理员登录</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-login-container">
        <h2>管理员登录</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="admin-login-form">
            <div class="form-group">
                <label>用户名:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>密码:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">登录</button>
        </form>
    </div>
</body>
</html> 