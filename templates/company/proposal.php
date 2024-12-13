<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 创建议案</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="proposal-container">
        <h2>创建议案 - <?php echo $company['name']; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="proposal-form">
            <div class="form-group">
                <label>议案标题:</label>
                <input type="text" name="title" required minlength="5" maxlength="100">
            </div>
            
            <div class="form-group">
                <label>议案内容:</label>
                <textarea name="content" required minlength="10" rows="10"></textarea>
            </div>
            
            <div class="form-group">
                <label>投票截止时间:</label>
                <input type="datetime-local" name="deadline" required 
                       min="<?php echo date('Y-m-d\TH:i', strtotime('+1 day')); ?>">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">创建议案</button>
                <a href="/company/meeting.php?id=<?php echo $company['id']; ?>" class="btn-cancel">取消</a>
            </div>
        </form>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 