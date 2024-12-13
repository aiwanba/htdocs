<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - IPO申请</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="ipo-container">
        <h2>IPO申请 - <?php echo $company['name']; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="company-info">
            <p>注册资本: <?php echo format_money($company['capital']); ?></p>
            <p>上市条件: 公司资产不低于<?php echo format_money(MIN_IPO_CAPITAL); ?></p>
        </div>
        
        <form method="POST" class="ipo-form">
            <div class="form-group">
                <label>发行股数:</label>
                <input type="number" name="share_amount" required min="10000" step="1000">
                <small>最低发行1万股</small>
            </div>
            
            <div class="form-group">
                <label>发行价格:</label>
                <input type="number" name="price_per_share" required min="1" step="0.01">
                <small>每股价格不低于1游戏币</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">提交申请</button>
                <a href="/company/manage.php" class="btn-cancel">取消</a>
            </div>
        </form>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 