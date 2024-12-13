<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 管理员管理</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>管理员管理</h2>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="admin-add">
            <h3>添加管理员</h3>
            <form method="POST" class="add-form">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label>用户名:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>密码:</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label>角色:</label>
                    <select name="role" required>
                        <option value="admin">普通管理员</option>
                        <option value="super_admin">超级管理员</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">添加管理员</button>
            </form>
        </div>
        
        <div class="admin-list">
            <h3>管理员列表</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户名</th>
                        <th>角色</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><?php echo $admin['username']; ?></td>
                        <td><?php echo $admin['role']; ?></td>
                        <td><?php echo $admin['status']; ?></td>
                        <td><?php echo $admin['created_at']; ?></td>
                        <td>
                            <?php if ($admin['status'] == 'active'): ?>
                                <button onclick="updateStatus(<?php echo $admin['id']; ?>, 'blocked')" 
                                        class="btn-block">禁用</button>
                            <?php else: ?>
                                <button onclick="updateStatus(<?php echo $admin['id']; ?>, 'active')" 
                                        class="btn-activate">启用</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    function updateStatus(adminId, status) {
        if (confirm('确定要' + (status == 'blocked' ? '禁用' : '启用') + '该管理员吗？')) {
            var form = document.createElement('form');
            form.method = 'POST';
            
            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'status';
            
            var adminIdInput = document.createElement('input');
            adminIdInput.type = 'hidden';
            adminIdInput.name = 'admin_id';
            adminIdInput.value = adminId;
            
            var statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(actionInput);
            form.appendChild(adminIdInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 