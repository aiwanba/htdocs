<?php
// 站点基本配置
define('SITE_NAME', '虚拟股票交易系统');
define('SITE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'admin@example.com');

// 系统设置
define('DEBUG_MODE', true);           // 调试模式
define('TIMEZONE', 'Asia/Shanghai');  // 时区设置
define('CHARSET', 'UTF-8');          // 字符集

// 交易相关配置
define('TRADE_FEE_RATE', 0.001);      // 交易手续费率
define('MIN_IPO_CAPITAL', 1000000);   // 最低上市资本
define('PRICE_LIMIT_RATE', 0.1);      // 涨跌幅限制
define('MAX_COMPANIES_PER_USER', 3);   // 每用户最大公司数
define('SESSION_LIFETIME', 7200);      // 会话有效期(秒)

// 文件上传配置
define('UPLOAD_MAX_SIZE', 5242880);    // 最大上传文件大小 (5MB)
define('ALLOWED_FILE_TYPES', 'jpg,jpeg,png,gif,pdf,doc,docx');  // 允许的文件类型

// 分页设置
define('ITEMS_PER_PAGE', 20);         // 每页显示数量