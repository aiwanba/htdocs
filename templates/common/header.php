<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="/"><?php echo SITE_NAME; ?></a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="/market">市场</a></li>
                    <li><a href="/stock/ipo">新股申购</a></li>
                    <li><a href="/company/list">上市公司</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/user/portfolio">我的持仓</a></li>
                        <li><a href="/user/orders">交易记录</a></li>
                        <li><a href="/user/company">我的公司</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="user-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="balance">
                        余额: <?php echo format_money($_SESSION['balance']); ?>
                    </span>
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>
                        <div class="dropdown-menu">
                            <a href="/user/profile">个人资料</a>
                            <a href="/user/settings">账户设置</a>
                            <a href="/user/recharge">充值</a>
                            <a href="/user/withdraw">提现</a>
                            <a href="/logout">退出登录</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn-login">登录</a>
                    <a href="/register" class="btn-register">注册</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>
</html> 