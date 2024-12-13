<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 系统设置</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>系统设置</h2>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="system-stats">
            <h3>系统统计</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">总用户数</div>
                    <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">总公司数</div>
                    <div class="stat-value"><?php echo $stats['total_companies']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">总交易笔数</div>
                    <div class="stat-value"><?php echo $stats['total_trades']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">总交易金额</div>
                    <div class="stat-value"><?php echo format_money($stats['total_volume']); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">系统总收入</div>
                    <div class="stat-value"><?php echo format_money($stats['total_income']); ?></div>
                </div>
            </div>
        </div>
        
        <div class="settings-form">
            <h3>系统参数设置</h3>
            <form method="POST">
                <div class="form-group">
                    <label>交易手续费率:</label>
                    <input type="number" name="trade_fee_rate" step="0.0001" min="0" max="0.01"
                           value="<?php echo $settings['trade_fee_rate'] ?? TRADE_FEE_RATE; ?>">
                    <small>当前: <?php echo ($settings['trade_fee_rate'] ?? TRADE_FEE_RATE) * 100; ?>%</small>
                </div>
                
                <div class="form-group">
                    <label>最低上市资本:</label>
                    <input type="number" name="min_ipo_capital" step="100000" min="100000"
                           value="<?php echo $settings['min_ipo_capital'] ?? MIN_IPO_CAPITAL; ?>">
                    <small>当前: <?php echo format_money($settings['min_ipo_capital'] ?? MIN_IPO_CAPITAL); ?></small>
                </div>
                
                <div class="form-group">
                    <label>涨跌幅限制:</label>
                    <input type="number" name="price_limit_rate" step="0.01" min="0.05" max="0.2"
                           value="<?php echo $settings['price_limit_rate'] ?? PRICE_LIMIT_RATE; ?>">
                    <small>当前: <?php echo ($settings['price_limit_rate'] ?? PRICE_LIMIT_RATE) * 100; ?>%</small>
                </div>
                
                <div class="form-group">
                    <label>每用户最大公司数:</label>
                    <input type="number" name="max_companies_per_user" min="1" max="10"
                           value="<?php echo $settings['max_companies_per_user'] ?? MAX_COMPANIES_PER_USER; ?>">
                </div>
                
                <div class="form-group">
                    <label>会话有效期(秒):</label>
                    <input type="number" name="session_lifetime" min="1800" max="86400"
                           value="<?php echo $settings['session_lifetime'] ?? SESSION_LIFETIME; ?>">
                    <small>当前: <?php echo ($settings['session_lifetime'] ?? SESSION_LIFETIME) / 3600; ?>小时</small>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-submit">保存设置</button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 