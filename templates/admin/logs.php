<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 系统日志</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>系统日志</h2>
        
        <div class="log-filter">
            <form method="GET" class="filter-form">
                <select name="type" onchange="this.form.submit()">
                    <option value="">全部类型</option>
                    <?php foreach ($log_types as $log_type): ?>
                    <option value="<?php echo $log_type; ?>" 
                            <?php echo $type == $log_type ? 'selected' : ''; ?>>
                        <?php echo $log_type; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        
        <div class="log-list">
            <table>
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>用户</th>
                        <th>操作类型</th>
                        <th>详细信息</th>
                        <th>IP地址</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo $log['created_at']; ?></td>
                        <td>
                            <?php if ($log['username']): ?>
                                <a href="/admin/user_detail.php?id=<?php echo $log['user_id']; ?>">
                                    <?php echo $log['username']; ?>
                                </a>
                            <?php else: ?>
                                系统
                            <?php endif; ?>
                        </td>
                        <td><?php echo $log['action']; ?></td>
                        <td><?php echo $log['details']; ?></td>
                        <td><?php echo $log['ip_address'] ?? '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- 分页 -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&type=<?php echo $type; ?>" 
                       class="prev">上一页</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&type=<?php echo $type; ?>" 
                       class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&type=<?php echo $type; ?>" 
                       class="next">下一页</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 