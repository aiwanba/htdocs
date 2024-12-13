<?php
require_once 'lib/utils.php';
require_once 'lib/trade/stock.php';

// 获取热门股票列表
$stock_manager = new StockManager();
$hot_stocks = $stock_manager->getHotStocks();

// 获取最新上市公司
$latest_ipos = $stock_manager->getLatestIPOs();

// 获取最新交易记录
$latest_trades = $stock_manager->getLatestTrades();

// 获取市场统计数据
$market_stats = $stock_manager->getMarketStats();

// 获取系统公告
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare(
    "SELECT * FROM system_notifications 
     WHERE type = 'public'
     ORDER BY created_at DESC 
     LIMIT 5"
);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'templates/common/home.php'; ?> 