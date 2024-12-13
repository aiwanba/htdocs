<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 用户详情</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>用户详情</h2>
        
        <div class="user-info">
            <h3>基本信息</h3>
            <table>
                <tr>
                    <th>用户ID:</th>
                    <td><?php echo $user['id']; ?></td>
                    <th>用户名:</th>
                    <td><?php echo $user['username']; ?></td>
                </tr>
                <tr>
                    <th>注册时间:</th>
                    <td><?php echo $user['created_at']; ?></td>
                    <th>账户状态:</th>
                    <td><?php echo $user['status']; ?></td>
                </tr>
                <tr>
                    <th>账户余额:</th>
                    <td><?php echo format_money($user['balance']); ?></td>
                    <th>交易总量:</th>
                    <td><?php echo $user['trade_volume'] ?? 0; ?></td>
                </tr>
            </table>
        </div>
        
        <div class="user-companies">
            <h3>创建的公司</h3>
            <?php if ($companies): ?>
            <table>
                <thead>
                    <tr>
                        <th>公司名称</th>
                        <th>注册资本</th>
                        <th>创建时间</th>
                        <th>股票状态</th>
                        <th>总股本</th>
                        <th>当前股价</th>
                        <th>市值</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?php echo $company['name']; ?></td>
                        <td><?php echo format_money($company['capital']); ?></td>
                        <td><?php echo $company['created_at']; ?></td>
                        <td><?php echo $company['total_shares'] ? '已上市' : '未上市'; ?></td>
                        <td><?php echo $company['total_shares'] ?? '-'; ?></td>
                        <td><?php echo $company['current_price'] ? format_money($company['current_price']) : '-'; ?></td>
                        <td>
                            <?php 
                            echo $company['total_shares'] && $company['current_price'] 
                                ? format_money($company['total_shares'] * $company['current_price']) 
                                : '-'; 
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>暂未创建公司</p>
            <?php endif; ?>
        </div>
        
        <div class="user-transactions">
            <h3>最近交易记录</h3>
            <?php if ($transactions): ?>
            <table>
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>公司</th>
                        <th>类型</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>金额</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $trans): ?>
                    <tr>
                        <td><?php echo $trans['created_at']; ?></td>
                        <td><?php echo $trans['company_name']; ?></td>
                        <td><?php echo $trans['type'] == 'buy' ? '买入' : '���出'; ?></td>
                        <td><?php echo format_money($trans['price']); ?></td>
                        <td><?php echo $trans['amount']; ?></td>
                        <td><?php echo format_money($trans['price'] * $trans['amount']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>暂无交易记录</p>
            <?php endif; ?>
        </div>
        
        <div class="admin-actions">
            <a href="/admin/users.php" class="btn-back">返回用户列表</a>
            <?php if ($user['status'] == 'active'): ?>
                <button onclick="updateStatus('blocked')" class="btn-block">封禁用户</button>
            <?php else: ?>
                <button onclick="updateStatus('active')" class="btn-activate">激活用户</button>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    function updateStatus(status) {
        if (confirm('确定要' + (status == 'blocked' ? '封禁' : '激活') + '该用户吗？')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/users.php';
            
            var userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = <?php echo $user['id']; ?>;
            
            var statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(userIdInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 