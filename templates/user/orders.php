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
                <div class="orders-page">
                    <div class="page-header">
                        <h2>交易记录</h2>
                    </div>
                    
                    <?php if (empty($orders)): ?>
                        <div class="empty-list">暂无交易记录</div>
                    <?php else: ?>
                        <div class="orders-list">
                            <table>
                                <thead>
                                    <tr>
                                        <th>时间</th>
                                        <th>公司名称</th>
                                        <th>类型</th>
                                        <th>数量</th>
                                        <th>价格</th>
                                        <th>总金额</th>
                                        <th>状态</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo format_datetime($order['created_at']); ?></td>
                                            <td>
                                                <a href="/company/view/<?php echo $order['company_id']; ?>">
                                                    <?php echo htmlspecialchars($order['company_name']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo $order['type'] == 'buy' ? '买入' : '卖出'; ?></td>
                                            <td><?php echo number_format($order['amount']); ?></td>
                                            <td><?php echo format_money($order['price']); ?></td>
                                            <td><?php echo format_money($order['total_amount']); ?></td>
                                            <td>
                                                <?php
                                                switch($order['status']) {
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
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'templates/common/footer.php'; ?>
</body>
</html> 