<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'cs_aiwanba_net');
define('DB_USER', 'cs_aiwanba_net');
define('DB_PASS', 'pYjRdbdmfEEsKZd2');

// 系统配置
define('SITE_NAME', '多人在线股票交易游戏');
define('SITE_URL', 'http://localhost');
define('DEBUG_MODE', true);

// 交易配置
define('MAX_COMPANIES_PER_USER', 3);  // 每个用户最多创建3个公司
define('MIN_IPO_CAPITAL', 1000000);   // 最低上市资本(100万游戏币)
define('TRADE_FEE_RATE', 0.001);      // 交易手续费率0.1%
define('PRICE_LIMIT_RATE', 0.1);      // 涨跌幅限制10%

// 安全配置
define('SESSION_LIFETIME', 3600);      // 会话有效期1小时
define('LOGIN_ATTEMPTS_LIMIT', 5);     // 登录尝试次数限制
define('PASSWORD_HASH_ALGO', 'sha256'); // 密码哈希算法 