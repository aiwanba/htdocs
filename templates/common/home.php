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
                <div class="market-overview">
                    <div class="market-stats">
                        <div class="stat-item">
                            <label>总市值</label>
                            <span><?php echo format_money($market_stats['total_market_value']); ?></span>
                        </div>
                        <div class="stat-item">
                            <label>上市公司</label>
                            <span><?php echo number_format($market_stats['total_stocks']); ?></span>
                        </div>
                        <div class="stat-item">
                            <label>今日成交额</label>
                            <span><?php echo format_money($market_stats['today_volume']); ?></span>
                        </div>
                    </div>
                    
                    <div class="hot-stocks">
                        <h2>热门股票</h2>
                        <div class="stock-list">
                            <?php if (!empty($hot_stocks)): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>公司名称</th>
                                            <th>当前价格</th>
                                            <th>涨跌幅</th>
                                            <th>成交量</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hot_stocks as $stock): ?>
                                            <tr>
                                                <td>
                                                    <a href="/company/view/<?php echo $stock['company_id']; ?>">
                                                        <?php echo htmlspecialchars($stock['company_name']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo format_money($stock['current_price']); ?></td>
                                                <td class="<?php echo $stock['price_change'] >= 0 ? 'up' : 'down'; ?>">
                                                    <?php echo number_format($stock['price_change'], 2); ?>%
                                                </td>
                                                <td><?php echo number_format($stock['volume']); ?></td>
                                                <td>
                                                    <a href="/trade/buy/<?php echo $stock['id']; ?>" class="btn btn-sm">买入</a>
                                                    <a href="/trade/sell/<?php echo $stock['id']; ?>" class="btn btn-sm">卖出</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-list">暂无数据</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="latest-ipos">
                        <h2>最新上市</h2>
                        <div class="ipo-list">
                            <?php if (!empty($latest_ipos)): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>公司名称</th>
                                            <th>发行价</th>
                                            <th>当前价格</th>
                                            <th>涨跌幅</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latest_ipos as $stock): ?>
                                            <tr>
                                                <td>
                                                    <a href="/company/view/<?php echo $stock['company_id']; ?>">
                                                        <?php echo htmlspecialchars($stock['company_name']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo format_money($stock['open_price']); ?></td>
                                                <td><?php echo format_money($stock['current_price']); ?></td>
                                                <td class="<?php echo $stock['price_change'] >= 0 ? 'up' : 'down'; ?>">
                                                    <?php echo number_format($stock['price_change'], 2); ?>%
                                                </td>
                                                <td>
                                                    <a href="/trade/buy/<?php echo $stock['id']; ?>" class="btn btn-sm">买入</a>
                                                    <a href="/trade/sell/<?php echo $stock['id']; ?>" class="btn btn-sm">卖出</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-list">暂无数据</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 