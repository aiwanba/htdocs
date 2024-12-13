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
            <div class="ipo-form">
                <h2>申请上市 - <?php echo htmlspecialchars($company['name']); ?></h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/stock/ipo/<?php echo $company_id; ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label>发行股数:</label>
                        <input type="number" name="total_shares" value="<?php echo isset($_POST['total_shares']) ? (int)$_POST['total_shares'] : 10000; ?>" min="10000" step="1000" required>
                        <small>最少发行10000股</small>
                    </div>
                    
                    <div class="form-group">
                        <label>发行价格:</label>
                        <input type="number" name="price" value="<?php echo isset($_POST['price']) ? (float)$_POST['price'] : 1.00; ?>" min="0.01" step="0.01" required>
                        <small>发行价格必须大于0</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">申请上市</button>
                        <a href="/company/view/<?php echo $company_id; ?>" class="btn-cancel">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 