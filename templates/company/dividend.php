<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 分红派息</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="dividend-container">
        <h2>分红派息 - <?php echo $company['name']; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="company-info">
            <p>总股本: <?php echo $company['total_shares']; ?></p>
            <p>公司资金: <?php echo format_money($company['capital']); ?></p>
            <p>当前股价: <?php echo format_money($company['current_price']); ?></p>
        </div>
        
        <form method="POST" class="dividend-form">
            <div class="form-group">
                <label>每股分红金额:</label>
                <input type="number" name="amount_per_share" step="0.01" min="0.01" required>
                <small>总分红金额 = 每股分红 × 总股本</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">发放分红</button>
                <a href="/company/detail.php?id=<?php echo $company['id']; ?>" class="btn-cancel">取消</a>
            </div>
        </form>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 