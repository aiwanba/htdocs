<?php
require_once 'lib/stock/quote.php';

check_login();

$db = Database::getInstance()->getConnection();

// 获取所有上市公司股票
$stmt = $db->prepare(
    "SELECT s.*, c.name as company_name, c.business_type
     FROM stocks s
     JOIN companies c ON s.company_id = c.id
     WHERE s.status = 'active'
     ORDER BY s.current_price * s.total_shares DESC"
);
$stmt->execute();
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 计算涨跌幅
$quote = new StockQuote();
foreach ($stocks as &$stock) {
    $price_change = $quote->calculatePriceChange($stock['id']);
    $stock['change'] = $price_change['change'];
    $stock['change_percent'] = $price_change['change_percent'];
    $stock['market_value'] = $stock['current_price'] * $stock['total_shares'];
}

// 获取成交量排行
$stmt = $db->prepare(
    "SELECT s.id, s.current_price, c.name as company_name,
            COUNT(*) as trade_count,
            SUM(t.amount) as trade_volume,
            SUM(t.price * t.amount) as trade_amount
     FROM transactions t
     JOIN stocks s ON t.stock_id = s.id
     JOIN companies c ON s.company_id = c.id
     WHERE t.created_at >= CURDATE()
     GROUP BY s.id, s.current_price, c.name
     ORDER BY trade_volume DESC
     LIMIT 10"
);
$stmt->execute();
$volume_rank = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'templates/market/index.php'; ?> 