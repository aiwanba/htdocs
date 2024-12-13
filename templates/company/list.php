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
                <div class="company-list-page">
                    <div class="page-header">
                        <h2>所有公司</h2>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="/company/create" class="btn-create">创建新公司</a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (empty($companies)): ?>
                        <div class="empty-list">暂无公司</div>
                    <?php else: ?>
                        <div class="company-grid">
                            <?php foreach ($companies as $company): ?>
                                <div class="company-card">
                                    <div class="company-header">
                                        <h3>
                                            <a href="/company/view/<?php echo $company['id']; ?>">
                                                <?php echo htmlspecialchars($company['name']); ?>
                                            </a>
                                        </h3>
                                        <?php if ($company['is_listed']): ?>
                                            <span class="status listed">已上市</span>
                                        <?php else: ?>
                                            <span class="status unlisted">未上市</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="company-info">
                                        <div class="info-item">
                                            <label>创建者:</label>
                                            <span><?php echo htmlspecialchars($company['owner_name']); ?></span>
                                        </div>
                                        <div class="info-item">
                                            <label>创建时间:</label>
                                            <span><?php echo format_datetime($company['created_at']); ?></span>
                                        </div>
                                        <?php if ($company['is_listed']): ?>
                                            <div class="info-item">
                                                <label>当前股价:</label>
                                                <span><?php echo format_money($company['current_price']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>总股本:</label>
                                                <span><?php echo number_format($company['total_shares']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <label>市值:</label>
                                                <span><?php echo format_money($company['market_value']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="company-description">
                                        <?php if (!empty($company['description'])): ?>
                                            <p><?php echo nl2br(htmlspecialchars($company['description'])); ?></p>
                                        <?php else: ?>
                                            <p class="no-description">暂无简介</p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="company-actions">
                                        <a href="/company/view/<?php echo $company['id']; ?>" class="btn">查看详情</a>
                                        <?php if ($company['is_listed']): ?>
                                            <a href="/trade/buy/<?php echo $company['id']; ?>" class="btn btn-primary">买入股票</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 