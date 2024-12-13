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
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php else: ?>
                <div class="portfolio-page">
                    <div class="page-header">
                        <h2>我的持仓</h2>
                        <div class="total-value">
                            <label>总市值:</label>
                            <span><?php echo format_money($total_value); ?></span>
                        </div>
                    </div>
                    
                    <?php if (empty($holdings)): ?>
                        <div class="empty-list">暂无持仓</div>
                    <?php else: ?>
                        <div class="holdings-list">
                            <table>
                                <thead>
                                    <tr>
                                        <th>公司名称</th>
                                        <th>持仓数量</th>
                                        <th>现价</th>
                                        <th>涨跌幅</th>
                                        <th>市值</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($holdings as $holding): ?>
                                        <tr>
                                            <td>
                                                <a href="/company/view/<?php echo $holding['company_id']; ?>">
                                                    <?php echo htmlspecialchars($holding['company_name']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo number_format($holding['amount']); ?></td>
                                            <td><?php echo format_money($holding['current_price']); ?></td>
                                            <td class="<?php echo $holding['price_change'] >= 0 ? 'up' : 'down'; ?>">
                                                <?php echo number_format($holding['price_change'], 2); ?>%
                                            </td>
                                            <td><?php echo format_money($holding['market_value']); ?></td>
                                            <td>
                                                <a href="/trade/buy/<?php echo $holding['stock_id']; ?>" class="btn btn-sm">买入</a>
                                                <a href="/trade/sell/<?php echo $holding['stock_id']; ?>" class="btn btn-sm">卖出</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 