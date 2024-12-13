<?php
require_once 'lib/trade/order.php';
require_once 'lib/stock/quote.php';

check_login();

$db = Database::getInstance()->getConnection();

// 获取用户的持仓信息
$stmt = $db->prepare(
    "SELECT 
        s.id as stock_id,
        c.name as company_name,
        s.current_price,
        SUM(CASE WHEN t.type = 'buy' THEN t.amount ELSE -t.amount END) as position,
        AVG(CASE WHEN t.type = 'buy' THEN t.price ELSE NULL END) as avg_cost
     FROM transactions t
     JOIN stocks s ON t.stock_id = s.id
     JOIN companies c ON s.company_id = c.id
     WHERE t.user_id = ?
     GROUP BY s.id, c.name, s.current_price
     HAVING position > 0"
);
$stmt->execute([$_SESSION['user_id']]);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 计算每个持仓的盈亏
$quote = new StockQuote();
foreach ($positions as &$pos) {
    $pos['profit'] = ($pos['current_price'] - $pos['avg_cost']) * $pos['position'];
    $pos['profit_percent'] = ($pos['current_price'] / $pos['avg_cost'] - 1) * 100;
}
?>

<?php include 'templates/trade/position.php'; ?> 