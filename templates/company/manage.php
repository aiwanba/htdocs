<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 公司管理</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="company-container">
        <h2>公司管理</h2>
        
        <?php if (count($companies) < MAX_COMPANIES_PER_USER): ?>
            <div class="create-company">
                <a href="/company/create.php" class="btn-create">创建新公司</a>
            </div>
        <?php endif; ?>
        
        <div class="company-list">
            <?php foreach ($companies as $company): ?>
            <div class="company-card">
                <h3><?php echo $company['name']; ?></h3>
                
                <div class="company-info">
                    <p>注册资本: <?php echo format_money($company['capital']); ?></p>
                    <p>经营状态: <?php echo $company['status']; ?></p>
                    <p>创建时间: <?php echo $company['created_at']; ?></p>
                    
                    <?php if ($company['total_shares'] > 0): ?>
                        <p>总股本: <?php echo $company['total_shares']; ?></p>
                        <p>当前股价: <?php echo format_money($company['stock_price']); ?></p>
                        <p>市值: <?php echo format_money($company['stock_price'] * $company['total_shares']); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="company-actions">
                    <?php if ($company['status'] == 'active' && $company['total_shares'] == 0): ?>
                        <a href="/company/ipo.php?id=<?php echo $company['id']; ?>" 
                           class="btn-ipo">申请IPO</a>
                    <?php endif; ?>
                    
                    <a href="/company/detail.php?id=<?php echo $company['id']; ?>" 
                       class="btn-detail">查看详情</a>
                       
                    <?php if ($company['total_shares'] > 0): ?>
                        <a href="/company/dividend.php?id=<?php echo $company['id']; ?>" 
                           class="btn-dividend">分红派息</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($pending_ipos): ?>
        <div class="pending-ipos">
            <h3>待审核的IPO申请</h3>
            <table>
                <thead>
                    <tr>
                        <th>公司名称</th>
                        <th>发行股数</th>
                        <th>发行价格</th>
                        <th>申请时间</th>
                        <th>状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_ipos as $ipo): ?>
                    <tr>
                        <td><?php echo $ipo['company_id']; ?></td>
                        <td><?php echo $ipo['share_amount']; ?></td>
                        <td><?php echo format_money($ipo['price_per_share']); ?></td>
                        <td><?php echo $ipo['created_at']; ?></td>
                        <td>审核中</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 