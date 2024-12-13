<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 股票交易</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/trade.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="trade-container">
        <h2>股票交易</h2>
        
        <div class="stock-list">
            <table>
                <thead>
                    <tr>
                        <th>代码</th>
                        <th>公司名称</th>
                        <th>当前价格</th>
                        <th>涨跌幅</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo $stock['id']; ?></td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo format_money($stock['current_price']); ?></td>
                        <td class="<?php echo $stock['change'] >= 0 ? 'up' : 'down'; ?>">
                            <?php echo $stock['change_percent']; ?>%
                        </td>
                        <td>
                            <a href="/trade/order.php?stock_id=<?php echo $stock['id']; ?>&type=buy" class="btn-buy">买入</a>
                            <a href="/trade/order.php?stock_id=<?php echo $stock['id']; ?>&type=sell" class="btn-sell">卖出</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 