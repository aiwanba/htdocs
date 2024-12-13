<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 持仓查询</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/trade.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="position-container">
        <h2>持仓查询</h2>
        
        <div class="position-list">
            <table>
                <thead>
                    <tr>
                        <th>股票代码</th>
                        <th>公司名称</th>
                        <th>持仓数量</th>
                        <th>当前价格</th>
                        <th>成本价格</th>
                        <th>持仓市值</th>
                        <th>浮动盈亏</th>
                        <th>盈亏比例</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $pos): ?>
                    <tr>
                        <td><?php echo $pos['stock_id']; ?></td>
                        <td><?php echo $pos['company_name']; ?></td>
                        <td><?php echo $pos['position']; ?></td>
                        <td><?php echo format_money($pos['current_price']); ?></td>
                        <td><?php echo format_money($pos['avg_cost']); ?></td>
                        <td><?php echo format_money($pos['current_price'] * $pos['position']); ?></td>
                        <td class="<?php echo $pos['profit'] >= 0 ? 'profit' : 'loss'; ?>">
                            <?php echo format_money($pos['profit']); ?>
                        </td>
                        <td class="<?php echo $pos['profit'] >= 0 ? 'profit' : 'loss'; ?>">
                            <?php echo round($pos['profit_percent'], 2); ?>%
                        </td>
                        <td>
                            <a href="/trade/order.php?stock_id=<?php echo $pos['stock_id']; ?>&type=sell" 
                               class="btn-sell">卖出</a>
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