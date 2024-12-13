<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - <?php echo $type == 'buy' ? '买入' : '卖出'; ?>股票</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/trade.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="order-container">
        <h2><?php echo $type == 'buy' ? '买入' : '卖出'; ?>股票</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="stock-info">
            <p>股票代码: <?php echo $stock['id']; ?></p>
            <p>公司名称: <?php echo $stock['company_name']; ?></p>
            <p>当前价格: <?php echo format_money($stock['current_price']); ?></p>
        </div>
        
        <form method="POST" class="order-form">
            <div class="form-group">
                <label>价格:</label>
                <input type="number" name="price" step="0.01" min="0" 
                       value="<?php echo $stock['current_price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>数量:</label>
                <input type="number" name="amount" min="1" required>
            </div>
            
            <div class="form-group">
                <label>交易费用:</label>
                <p class="fee-info">交易费率: <?php echo TRADE_FEE_RATE * 100; ?>%</p>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-<?php echo $type; ?>">
                    确认<?php echo $type == 'buy' ? '买入' : '卖出'; ?>
                </button>
                <a href="/trade/list.php" class="btn-cancel">取消</a>
            </div>
        </form>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 