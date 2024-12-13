<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <div class="company-form">
                <h2>创建新公司</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/company/create">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label>公司名称:</label>
                        <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        <small>公司名称长度必须在2-100个字符之间</small>
                    </div>
                    
                    <div class="form-group">
                        <label>公司简介:</label>
                        <textarea name="description" rows="5"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        <small>简要描述公司的主营业务和发展方向</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">创建公司</button>
                        <a href="/user/company" class="btn-cancel">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 