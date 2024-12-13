<?php
require_once 'lib/trade/order.php';
require_once 'lib/stock/quote.php';

check_login();

$stock_id = clean_input($_GET['stock_id'] ?? '');
$type = clean_input($_GET['type'] ?? '');

if (!in_array($type, ['buy', 'sell'])) {
    header('Location: /trade/list.php');
    exit;
}

// 获取股票信息
$quote = new StockQuote();
$stock = $quote->getQuote($stock_id);

if (!$stock) {
    header('Location: /trade/list.php');
    exit;
}

// 处理下单请求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $price = clean_input($_POST['price']);
    $amount = clean_input($_POST['amount']);
    
    $order = new TradeOrder($_SESSION['user_id']);
    $result = $order->createOrder($stock_id, $type, $price, $amount);
    
    if ($result['success']) {
        header('Location: /trade/history.php?msg=order_success');
        exit;
    }
    $error = $result['message'];
}
?>

<?php include 'templates/trade/order.php'; ?> 