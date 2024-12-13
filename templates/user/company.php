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
            <div class="company-list">
                <div class="page-header">
                    <h2>我的公司</h2>
                    <a href="/company/create" class="btn-create">创建新公司</a>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (empty($companies)): ?>
                    <div class="empty-list">
                        <p>您还没有创建任何公司</p>
                        <a href="/company/create" class="btn">立即创建</a>
                    </div>
                <?php else: ?>
                    <div class="company-grid">
                        <?php foreach ($companies as $company): ?>
                            <div class="company-card">
                                <div class="company-name">
                                    <h3><?php echo htmlspecialchars($company['name']); ?></h3>
                                    <span class="company-status <?php echo $company['status']; ?>">
                                        <?php echo $company['status'] == 'active' ? '正常' : '已退市'; ?>
                                    </span>
                                </div>
                                
                                <div class="company-info">
                                    <?php if (isset($company['current_price'])): ?>
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
                                    <?php else: ?>
                                        <div class="not-listed">未上市</div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="company-actions">
                                    <a href="/company/view/<?php echo $company['id']; ?>" class="btn">查看详情</a>
                                    <a href="/company/edit/<?php echo $company['id']; ?>" class="btn">编辑</a>
                                    <?php if (!isset($company['current_price'])): ?>
                                        <a href="/stock/ipo/<?php echo $company['id']; ?>" class="btn">申请上市</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 