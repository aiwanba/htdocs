<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 创建公司</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="create-container">
        <h2>创建新公司</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="create-form">
            <div class="form-group">
                <label>公司名称:</label>
                <input type="text" name="name" required minlength="2" maxlength="50">
                <small>公司名称必须唯一</small>
            </div>
            
            <div class="form-group">
                <label>注册资本:</label>
                <input type="number" name="capital" required min="10000" step="10000">
                <small>最低注册资本10000游戏币</small>
            </div>
            
            <div class="form-group">
                <label>主营业务:</label>
                <select name="business_type" required>
                    <option value="technology">科技</option>
                    <option value="finance">金融</option>
                    <option value="retail">零售</option>
                    <option value="manufacturing">制造业</option>
                    <option value="service">服务业</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">创建公司</button>
                <a href="/company/manage.php" class="btn-cancel">取消</a>
            </div>
        </form>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 