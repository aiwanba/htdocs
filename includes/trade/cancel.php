<?php
require_once 'lib/trade/order.php';
require_once 'lib/user/account.php';

check_login();

$order_id = clean_input($_GET['order_id'] ?? '');

if (!$order_id) {
    header('Location: /trade/history.php');
    exit;
}

$db = Database::getInstance()->getConnection();

try {
    $db->beginTransaction();
    
    // 检查订单是否存在且属于当前用户
    $stmt = $db->prepare(
        "SELECT * FROM orders 
         WHERE id = ? AND user_id = ? AND status = 'pending'"
    );
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        throw new Exception('订单不存在或已成交');
    }
    
    // 如果是买入订单，需要解冻资金
    if ($order['type'] == 'buy') {
        $total_amount = $order['price'] * $order['amount'] * (1 + TRADE_FEE_RATE);
        $account = new UserAccount($_SESSION['user_id']);
        $account->updateBalance($total_amount, 'add');
    }
    
    // 更新订单状态
    $stmt = $db->prepare(
        "UPDATE orders SET status = 'cancelled' WHERE id = ?"
    );
    $stmt->execute([$order_id]);
    
    $db->commit();
    header('Location: /trade/history.php?msg=cancel_success');
} catch (Exception $e) {
    $db->rollBack();
    header('Location: /trade/history.php?error=' . urlencode($e->getMessage()));
} 