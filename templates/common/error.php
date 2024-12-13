<!DOCTYPE html>
<html>
<head>
    <title>错误 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <div class="error-page">
            <h1>系统错误</h1>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <div class="error-actions">
                <a href="/" class="btn">返回首页</a>
                <a href="javascript:history.back()" class="btn">返回上一页</a>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 