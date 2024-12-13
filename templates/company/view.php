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
                <div class="company-detail">
                    <div class="company-header">
                        <h2><?php echo htmlspecialchars($company['name']); ?></h2>
                        <div class="company-status <?php echo $company['status']; ?>">
                            <?php echo $company['status'] == 'active' ? '正常' : '已退市'; ?>
                        </div>
                    </div>
                    
                    <div class="company-info">
                        <div class="info-section">
                            <h3>基本信息</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>创建者:</label>
                                    <span><?php echo htmlspecialchars($company['owner_name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>创建时间:</label>
                                    <span><?php echo format_datetime($company['created_at']); ?></span>
                                </div>
                                <div class="info-item">
                                    <label>公司简介:</label>
                                    <p><?php echo nl2br(htmlspecialchars($company['description'])); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (isset($company['current_price'])): ?>
                            <div class="info-section">
                                <h3>股票信息</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label>当前股价:</label>
                                        <span><?php echo format_money($company['current_price']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>开盘价:</label>
                                        <span><?php echo format_money($company['open_price']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>最高价:</label>
                                        <span><?php echo format_money($company['high_price']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>最低价:</label>
                                        <span><?php echo format_money($company['low_price']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>总股本:</label>
                                        <span><?php echo number_format($company['total_shares']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>市值:</label>
                                        <span><?php echo format_money($company['market_value']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>上市时间:</label>
                                        <span><?php echo format_datetime($company['list_time']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>交易状态:</label>
                                        <span class="stock-status <?php echo $company['stock_status']; ?>">
                                            <?php 
                                            switch($company['stock_status']) {
                                                case 'active':
                                                    echo '正常交易';
                                                    break;
                                                case 'suspended':
                                                    echo '停牌';
                                                    break;
                                                case 'delisted':
                                                    echo '退市';
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($transactions)): ?>
                                <div class="info-section">
                                    <h3>最近交易</h3>
                                    <div class="transaction-list">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>时间</th>
                                                    <th>用户</th>
                                                    <th>类型</th>
                                                    <th>数量</th>
                                                    <th>价格</th>
                                                    <th>状态</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions as $tx): ?>
                                                    <tr>
                                                        <td><?php echo format_datetime($tx['created_at']); ?></td>
                                                        <td><?php echo htmlspecialchars($tx['username']); ?></td>
                                                        <td><?php echo $tx['type'] == 'buy' ? '买入' : '卖出'; ?></td>
                                                        <td><?php echo number_format($tx['amount']); ?></td>
                                                        <td><?php echo format_money($tx['price']); ?></td>
                                                        <td>
                                                            <?php
                                                            switch($tx['status']) {
                                                                case 'pending':
                                                                    echo '待处理';
                                                                    break;
                                                                case 'completed':
                                                                    echo '已完成';
                                                                    break;
                                                                case 'cancelled':
                                                                    echo '已取消';
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="company-actions">
                        <?php if ($company['owner_id'] == $_SESSION['user_id']): ?>
                            <a href="/company/edit/<?php echo $company['id']; ?>" class="btn">编辑公司</a>
                        <?php endif; ?>
                        <?php if (isset($company['current_price']) && $company['stock_status'] == 'active'): ?>
                            <a href="/trade/buy/<?php echo $company['id']; ?>" class="btn btn-primary">买入股票</a>
                            <a href="/trade/sell/<?php echo $company['id']; ?>" class="btn btn-danger">卖出股票</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 