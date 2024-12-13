<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 首页</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <!-- 系统公告 -->
        <?php if ($notifications): ?>
        <div class="notifications">
            <h2>系统公告</h2>
            <?php foreach ($notifications as $notice): ?>
            <div class="notice">
                <h3><?php echo $notice['title']; ?></h3>
                <div class="notice-content"><?php echo $notice['content']; ?></div>
                <div class="notice-time"><?php echo format_datetime($notice['created_at']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- 市场概况 -->
        <div class="market-overview">
            <h2>市场概况</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">总市值</div>
                    <div class="stat-value"><?php echo format_money($market_stats['total_market_value']); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">上市公司</div>
                    <div class="stat-value"><?php echo $market_stats['total_companies']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">今日成交额</div>
                    <div class="stat-value"><?php echo format_money($market_stats['today_volume']); ?></div>
                </div>
            </div>
        </div>
        
        <!-- 热门股票 -->
        <div class="hot-stocks">
            <h2>热门股票</h2>
            <table>
                <thead>
                    <tr>
                        <th>公司名称</th>
                        <th>当前价格</th>
                        <th>涨跌幅</th>
                        <th>成交量</th>
                        <th>市值</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hot_stocks as $stock): ?>
                    <tr>
                        <td>
                            <a href="/stock.php?id=<?php echo $stock['id']; ?>">
                                <?php echo $stock['company_name']; ?>
                            </a>
                        </td>
                        <td><?php echo format_money($stock['current_price']); ?></td>
                        <td class="<?php echo $stock['price_change'] >= 0 ? 'up' : 'down'; ?>">
                            <?php echo number_format($stock['price_change'] * 100, 2); ?>%
                        </td>
                        <td><?php echo $stock['volume']; ?></td>
                        <td><?php echo format_money($stock['market_value']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- 最新上市 -->
        <div class="latest-ipos">
            <h2>最新上市</h2>
            <div class="ipo-grid">
                <?php foreach ($latest_ipos as $ipo): ?>
                <div class="ipo-card">
                    <h3><?php echo $ipo['company_name']; ?></h3>
                    <div class="ipo-info">
                        <div>发行价: <?php echo format_money($ipo['price']); ?></div>
                        <div>发行量: <?php echo $ipo['shares']; ?></div>
                        <div>上市时间: <?php echo format_datetime($ipo['list_time']); ?></div>
                    </div>
                    <a href="/stock.php?id=<?php echo $ipo['id']; ?>" class="btn-view">查���详情</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- 最新交易 -->
        <div class="latest-trades">
            <h2>最新交易</h2>
            <table>
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>股票</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>金额</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_trades as $trade): ?>
                    <tr>
                        <td><?php echo format_datetime($trade['created_at']); ?></td>
                        <td><?php echo $trade['company_name']; ?></td>
                        <td><?php echo format_money($trade['price']); ?></td>
                        <td><?php echo $trade['amount']; ?></td>
                        <td><?php echo format_money($trade['price'] * $trade['amount']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 