<?php
require_once 'lib/trade/order.php';
require_once 'lib/stock/quote.php';

check_login();

$db = Database::getInstance()->getConnection();

// 获取用户的交易历史
$stmt = $db->prepare(
    "SELECT t.*, s.current_price, c.name as company_name 
     FROM transactions t
     JOIN stocks s ON t.stock_id = s.id
     JOIN companies c ON s.company_id = c.id
     WHERE t.user_id = ?
     ORDER BY t.created_at DESC
     LIMIT 50"
);
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取未成交订单
$stmt = $db->prepare(
    "SELECT o.*, s.current_price, c.name as company_name 
     FROM orders o
     JOIN stocks s ON o.stock_id = s.id
     JOIN companies c ON s.company_id = c.id
     WHERE o.user_id = ? AND o.status = 'pending'
     ORDER BY o.created_at DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'templates/trade/history.php'; ?> 