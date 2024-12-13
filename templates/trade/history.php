<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 交易历史</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/trade.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="history-container">
        <h2>交易历史</h2>
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'order_success'): ?>
            <div class="success-message">下单成功</div>
        <?php endif; ?>
        
        <div class="pending-orders">
            <h3>未成交订单</h3>
            <table>
                <thead>
                    <tr>
                        <th>订单时间</th>
                        <th>公司名称</th>
                        <th>类型</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_orders as $order): ?>
                    <tr>
                        <td><?php echo $order['created_at']; ?></td>
                        <td><?php echo $order['company_name']; ?></td>
                        <td><?php echo $order['type'] == 'buy' ? '买入' : '卖出'; ?></td>
                        <td><?php echo format_money($order['price']); ?></td>
                        <td><?php echo $order['amount']; ?></td>
                        <td>待成交</td>
                        <td>
                            <a href="/trade/cancel.php?order_id=<?php echo $order['id']; ?>" 
                               class="btn-cancel" onclick="return confirm('确定要撤销订单吗？')">撤销</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="transaction-history">
            <h3>成交记录</h3>
            <table>
                <thead>
                    <tr>
                        <th>成交时间</th>
                        <th>公司名称</th>
                        <th>类型</th>
                        <th>成交价格</th>
                        <th>成交数量</th>
                        <th>成交金额</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $trans): ?>
                    <tr>
                        <td><?php echo $trans['created_at']; ?></td>
                        <td><?php echo $trans['company_name']; ?></td>
                        <td><?php echo $trans['type'] == 'buy' ? '买入' : '卖出'; ?></td>
                        <td><?php echo format_money($trans['price']); ?></td>
                        <td><?php echo $trans['amount']; ?></td>
                        <td><?php echo format_money($trans['price'] * $trans['amount']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 