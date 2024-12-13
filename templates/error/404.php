<!DOCTYPE html>
<html>
<head>
    <title>页面不存在 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <div class="error-page">
                <h1>404</h1>
                <div class="error-message">页面不存在</div>
                <div class="error-actions">
                    <a href="/" class="btn">返回首页</a>
                    <a href="javascript:history.back()" class="btn">返回上一页</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 