<?php
require_once 'lib/trade/order.php';
require_once 'lib/stock/quote.php';

check_login();

// 获取所有可交易的股票
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare(
    "SELECT s.*, c.name as company_name 
     FROM stocks s 
     JOIN companies c ON s.company_id = c.id 
     WHERE s.status = 'active'"
);
$stmt->execute();
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取实时行情
$quote = new StockQuote();
foreach ($stocks as &$stock) {
    $price_change = $quote->calculatePriceChange($stock['id']);
    $stock['change'] = $price_change['change'];
    $stock['change_percent'] = $price_change['change_percent'];
}
?>

<?php include 'templates/trade/list.php'; ?> 