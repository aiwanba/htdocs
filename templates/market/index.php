<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 市场行情</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/market.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="market-container">
        <h2>市场行情</h2>
        
        <div class="stock-list">
            <table>
                <thead>
                    <tr>
                        <th>代码</th>
                        <th>公司名称</th>
                        <th>行业</th>
                        <th>最新价</th>
                        <th>涨跌幅</th>
                        <th>总股本</th>
                        <th>市值</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo $stock['id']; ?></td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo $stock['business_type']; ?></td>
                        <td><?php echo format_money($stock['current_price']); ?></td>
                        <td class="<?php echo $stock['change'] >= 0 ? 'up' : 'down'; ?>">
                            <?php echo $stock['change_percent']; ?>%
                        </td>
                        <td><?php echo $stock['total_shares']; ?></td>
                        <td><?php echo format_money($stock['market_value']); ?></td>
                        <td>
                            <a href="/trade/order.php?stock_id=<?php echo $stock['id']; ?>&type=buy" 
                               class="btn-buy">买入</a>
                            <a href="/trade/order.php?stock_id=<?php echo $stock['id']; ?>&type=sell" 
                               class="btn-sell">卖出</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="volume-rank">
            <h3>成交量排行</h3>
            <table>
                <thead>
                    <tr>
                        <th>代码</th>
                        <th>公司名称</th>
                        <th>成交量</th>
                        <th>成交额</th>
                        <th>成交笔数</th>
                        <th>当前价格</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($volume_rank as $rank): ?>
                    <tr>
                        <td><?php echo $rank['id']; ?></td>
                        <td><?php echo $rank['company_name']; ?></td>
                        <td><?php echo $rank['trade_volume']; ?></td>
                        <td><?php echo format_money($rank['trade_amount']); ?></td>
                        <td><?php echo $rank['trade_count']; ?></td>
                        <td><?php echo format_money($rank['current_price']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 