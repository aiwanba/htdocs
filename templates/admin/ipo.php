<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - IPO审核管理</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>IPO审核管理</h2>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="ipo-applications">
            <?php if ($pending_applications): ?>
            <table>
                <thead>
                    <tr>
                        <th>申请时间</th>
                        <th>公司名称</th>
                        <th>注册资本</th>
                        <th>主营业务</th>
                        <th>发行股数</th>
                        <th>发行价格</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_applications as $app): ?>
                    <tr>
                        <td><?php echo $app['created_at']; ?></td>
                        <td><?php echo $app['company_name']; ?></td>
                        <td><?php echo format_money($app['capital']); ?></td>
                        <td><?php echo $app['business_type']; ?></td>
                        <td><?php echo $app['share_amount']; ?></td>
                        <td><?php echo format_money($app['price_per_share']); ?></td>
                        <td>
                            <button onclick="showReviewForm(<?php echo $app['id']; ?>, 'approve')" 
                                    class="btn-approve">通过</button>
                            <button onclick="showReviewForm(<?php echo $app['id']; ?>, 'reject')" 
                                    class="btn-reject">拒绝</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>暂无待审核的IPO申请</p>
            <?php endif; ?>
        </div>
        
        <!-- 审核表单弹窗 -->
        <div id="review-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <h3>审核IPO申��</h3>
                <form method="POST" id="review-form">
                    <input type="hidden" name="application_id" id="application_id">
                    <input type="hidden" name="action" id="action">
                    
                    <div class="form-group">
                        <label>审核意见:</label>
                        <textarea name="reason" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit">确认</button>
                        <button type="button" onclick="hideReviewForm()">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    function showReviewForm(id, action) {
        document.getElementById('application_id').value = id;
        document.getElementById('action').value = action;
        document.getElementById('review-modal').style.display = 'block';
    }
    
    function hideReviewForm() {
        document.getElementById('review-modal').style.display = 'none';
    }
    </script>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 