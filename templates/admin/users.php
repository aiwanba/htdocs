<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 用户管理</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>用户管理</h2>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="user-list">
            <table>
                <thead>
                    <tr>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>注册时间</th>
                        <th>账户余额</th>
                        <th>公司数量</th>
                        <th>交易量</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td><?php echo format_money($user['balance']); ?></td>
                        <td><?php echo $user['company_count']; ?></td>
                        <td><?php echo $user['trade_volume'] ?? 0; ?></td>
                        <td><?php echo $user['status']; ?></td>
                        <td>
                            <a href="/admin/user_detail.php?id=<?php echo $user['id']; ?>" 
                               class="btn-view">查看</a>
                            <?php if ($user['status'] == 'active'): ?>
                                <button onclick="updateStatus(<?php echo $user['id']; ?>, 'blocked')" 
                                        class="btn-block">封禁</button>
                            <?php else: ?>
                                <button onclick="updateStatus(<?php echo $user['id']; ?>, 'active')" 
                                        class="btn-activate">激活</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- 分页 -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="prev">上一页</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="next">下一页</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    function updateStatus(userId, status) {
        if (confirm('确定要' + (status == 'blocked' ? '封禁' : '激活') + '该用户吗？')) {
            var form = document.createElement('form');
            form.method = 'POST';
            
            var userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = userId;
            
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