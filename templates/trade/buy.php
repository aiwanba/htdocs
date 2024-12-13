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
            <div class="trade-form">
                <h2>买入股票 - <?php echo htmlspecialchars($stock['company_name']); ?></h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="stock-info">
                    <div class="info-item">
                        <label>当前价格:</label>
                        <span><?php echo format_money($stock['current_price']); ?></span>
                    </div>
                    <div class="info-item">
                        <label>可用余额:</label>
                        <span><?php echo format_money($user['balance']); ?></span>
                    </div>
                </div>
                
                <form method="POST" action="/trade/buy/<?php echo $stock_id; ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label>买入价格:</label>
                        <input type="number" name="price" value="<?php echo isset($_POST['price']) ? (float)$_POST['price'] : $stock['current_price']; ?>" min="0.01" step="0.01" required>
                        <small>买入价格必须大于0</small>
                    </div>
                    
                    <div class="form-group">
                        <label>买入数量:</label>
                        <input type="number" name="amount" value="<?php echo isset($_POST['amount']) ? (int)$_POST['amount'] : 100; ?>" min="1" step="1" required>
                        <small>买入数量必须大于0</small>
                    </div>
                    
                    <div class="form-group">
                        <label>预计花费:</label>
                        <div class="total-cost">¥0.00</div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">确认买入</button>
                        <a href="/company/view/<?php echo $stock['company_id']; ?>" class="btn-cancel">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.querySelector('input[name="price"]');
        const amountInput = document.querySelector('input[name="amount"]');
        const totalCost = document.querySelector('.total-cost');
        
        function updateTotalCost() {
            const price = parseFloat(priceInput.value) || 0;
            const amount = parseInt(amountInput.value) || 0;
            const cost = price * amount;
            totalCost.textContent = '¥' + cost.toFixed(2);
        }
        
        priceInput.addEventListener('input', updateTotalCost);
        amountInput.addEventListener('input', updateTotalCost);
        
        updateTotalCost();
    });
    </script>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 