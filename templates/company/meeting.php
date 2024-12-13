<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 股东大会</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/company.css">
</head>
<body>
    <?php include 'includes/common/header.php'; ?>
    
    <div class="meeting-container">
        <h2>股东大会 - <?php echo $company['name']; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="voting-info">
            <p>您的持股数量: <?php echo $user_shares; ?></p>
            <p>占总股本比例: <?php echo round($user_shares / $company['total_shares'] * 100, 2); ?>%</p>
        </div>
        
        <?php if ($can_create_proposal): ?>
        <div class="create-proposal">
            <a href="/company/proposal.php?id=<?php echo $company['id']; ?>" class="btn-create">
                创建新议案
            </a>
        </div>
        <?php endif; ?>
        
        <div class="proposal-list">
            <h3>当前议案</h3>
            <?php if ($proposals): ?>
                <?php foreach ($proposals as $proposal): ?>
                <div class="proposal-card">
                    <h4><?php echo $proposal['title']; ?></h4>
                    <div class="proposal-content">
                        <?php echo nl2br($proposal['content']); ?>
                    </div>
                    <div class="proposal-meta">
                        <p>发起时间: <?php echo $proposal['created_at']; ?></p>
                        <p>当前投票数: <?php echo $proposal['total_votes']; ?></p>
                    </div>
                    
                    <?php if ($user_shares > 0 && !isset($proposal['user_vote'])): ?>
                    <form method="POST" class="vote-form">
                        <input type="hidden" name="proposal_id" value="<?php echo $proposal['id']; ?>">
                        <button type="submit" name="vote" value="agree" class="btn-agree">同意</button>
                        <button type="submit" name="vote" value="disagree" class="btn-disagree">反对</button>
                        <button type="submit" name="vote" value="abstain" class="btn-abstain">弃权</button>
                    </form>
                    <?php elseif (isset($proposal['user_vote'])): ?>
                    <div class="vote-result">
                        您已投票: <?php echo $proposal['user_vote']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <p>暂无议案</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'includes/common/footer.php'; ?>
</body>
</html> 