<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - <?php echo $company['name']; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="detail-container">
        <h2><?php echo $company['name']; ?></h2>
        
        <div class="company-overview">
            <div class="basic-info">
                <h3>基本信息</h3>
                <p>创始人: <?php echo $company['owner_name']; ?></p>
                <p>注册资本: <?php echo format_money($company['capital']); ?></p>
                <p>主营业务: <?php echo $company['business_type']; ?></p>
                <p>经营状态: <?php echo $company['status']; ?></p>
                <p>创建时间: <?php echo $company['created_at']; ?></p>
            </div>
            
            <?php if ($company['total_shares'] > 0): ?>
            <div class="stock-info">
                <h3>股票信息</h3>
                <p>总股本: <?php echo $company['total_shares']; ?></p>
                <p>当前股价: <?php echo format_money($company['stock_price']); ?></p>
                <p>市值: <?php echo format_money($company['stock_price'] * $company['total_shares']); ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($company['total_shares'] > 0): ?>
        <div class="shareholders">
            <h3>主要股东</h3>
            <table>
                <thead>
                    <tr>
                        <th>股东</th>
                        <th>持股数量</th>
                        <th>持股比例</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shareholders as $holder): ?>
                    <tr>
                        <td><?php echo $holder['username']; ?></td>
                        <td><?php echo $holder['shares']; ?></td>
                        <td><?php echo round($holder['shares'] / $company['total_shares'] * 100, 2); ?>%</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="dividend-history">
            <h3>分红记录</h3>
            <?php if ($dividends): ?>
            <table>
                <thead>
                    <tr>
                        <th>分���时间</th>
                        <th>每股分红</th>
                        <th>总分红金额</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dividends as $div): ?>
                    <tr>
                        <td><?php echo $div['created_at']; ?></td>
                        <td><?php echo format_money($div['amount_per_share']); ?></td>
                        <td><?php echo format_money($div['amount_per_share'] * $company['total_shares']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>暂无分红记录</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($company['owner_id'] == $_SESSION['user_id']): ?>
        <div class="management-actions">
            <?php if ($company['total_shares'] == 0): ?>
                <a href="/company/ipo.php?id=<?php echo $company['id']; ?>" class="btn-ipo">申请IPO</a>
            <?php else: ?>
                <a href="/company/dividend.php?id=<?php echo $company['id']; ?>" class="btn-dividend">分红派息</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 