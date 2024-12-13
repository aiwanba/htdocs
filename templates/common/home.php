<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 首页</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'templates/common/header.php'; ?>
    
    <div class="container">
        <div class="market-overview">
            <div class="market-stats">
                <div class="stat-item">
                    <label>总市值</label>
                    <span><?php echo format_money($market_stats['total_market_value']); ?></span>
                </div>
                <div class="stat-item">
                    <label>上市公司</label>
                    <span><?php echo $market_stats['total_companies']; ?></span>
                </div>
                <div class="stat-item">
                    <label>今日成交额</label>
                    <span><?php echo format_money($market_stats['today_volume']); ?></span>
                </div>
            </div>
            
            <div class="hot-stocks">
                <h2>热门股票</h2>
                <div class="stock-list">
                    <?php foreach ($hot_stocks as $stock): ?>
                        <div class="stock-item">
                            <div class="stock-name">
                                <a href="/stock/view/<?php echo $stock['id']; ?>">
                                    <?php echo htmlspecialchars($stock['company_name']); ?>
                                </a>
                            </div>
                            <div class="stock-price">
                                <?php echo format_money($stock['current_price']); ?>
                            </div>
                            <div class="stock-change <?php echo $stock['price_change'] >= 0 ? 'up' : 'down'; ?>">
                                <?php echo number_format($stock['price_change'] * 100, 2); ?>%
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="latest-ipos">
                <h2>最新上市</h2>
                <div class="ipo-list">
                    <?php foreach ($latest_ipos as $ipo): ?>
                        <div class="ipo-item">
                            <div class="company-name">
                                <a href="/stock/view/<?php echo $ipo['id']; ?>">
                                    <?php echo htmlspecialchars($ipo['company_name']); ?>
                                </a>
                            </div>
                            <div class="ipo-price">
                                发行价: <?php echo format_money($ipo['current_price']); ?>
                            </div>
                            <div class="list-time">
                                上市时间: <?php echo format_datetime($ipo['list_time']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 