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
                <h2>编辑公司</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/company/edit/<?php echo $company_id; ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label>公司名称:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>" required>
                        <small>公司名称长度必须在2-100个字符之间</small>
                    </div>
                    
                    <div class="form-group">
                        <label>公司简介:</label>
                        <textarea name="description" rows="5"><?php echo htmlspecialchars($company['description']); ?></textarea>
                        <small>简要描述公司的主营业务和发展方向</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">保存修改</button>
                        <a href="/company/view/<?php echo $company_id; ?>" class="btn-cancel">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 