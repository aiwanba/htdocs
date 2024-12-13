<?php
require_once 'lib/trade/order.php';
require_once 'lib/stock/quote.php';
require_once 'lib/user/account.php';

// 获取所有待成交的订单
$db = Database::getInstance()->getConnection();

try {
    $db->beginTransaction();
    
    // 锁定待处理订单表，防止并发处理
    $stmt = $db->prepare(
        "SELECT o.*, s.current_price, s.company_id
         FROM orders o
         JOIN stocks s ON o.stock_id = s.id
         WHERE o.status = 'pending'
         ORDER BY o.created_at ASC
         FOR UPDATE"
    );
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 按股票分组处理订单
    $grouped_orders = [];
    foreach ($orders as $order) {
        $stock_id = $order['stock_id'];
        if (!isset($grouped_orders[$stock_id])) {
            $grouped_orders[$stock_id] = [
                'buy' => [],
                'sell' => []
            ];
        }
        $grouped_orders[$stock_id][$order['type']][] = $order;
    }
    
    // 处理每支股票的订单
    foreach ($grouped_orders as $stock_id => $stock_orders) {
        // 按价格排序（买单降序��卖单升序）
        usort($stock_orders['buy'], function($a, $b) {
            return $b['price'] <=> $a['price'];
        });
        usort($stock_orders['sell'], function($a, $b) {
            return $a['price'] <=> $b['price'];
        });
        
        // 尝试撮合订单
        while (!empty($stock_orders['buy']) && !empty($stock_orders['sell'])) {
            $buy_order = $stock_orders['buy'][0];
            $sell_order = $stock_orders['sell'][0];
            
            // 判断价格是否匹配
            if ($buy_order['price'] >= $sell_order['price']) {
                // 确定成交价格（取两个价格的中间值）
                $deal_price = ($buy_order['price'] + $sell_order['price']) / 2;
                
                // 确定成交数量
                $deal_amount = min($buy_order['amount'], $sell_order['amount']);
                
                // 创建交易记录
                $stmt = $db->prepare(
                    "INSERT INTO transactions (stock_id, buy_order_id, sell_order_id, 
                            price, amount, created_at)
                     VALUES (?, ?, ?, ?, ?, NOW())"
                );
                $stmt->execute([
                    $stock_id, $buy_order['id'], $sell_order['id'], 
                    $deal_price, $deal_amount
                ]);
                
                // 更新订单状态
                $buy_remaining = $buy_order['amount'] - $deal_amount;
                $sell_remaining = $sell_order['amount'] - $deal_amount;
                
                if ($buy_remaining > 0) {
                    $stmt = $db->prepare(
                        "UPDATE orders SET amount = ? WHERE id = ?"
                    );
                    $stmt->execute([$buy_remaining, $buy_order['id']]);
                    $stock_orders['buy'][0]['amount'] = $buy_remaining;
                } else {
                    $stmt = $db->prepare(
                        "UPDATE orders SET status = 'completed' WHERE id = ?"
                    );
                    $stmt->execute([$buy_order['id']]);
                    array_shift($stock_orders['buy']);
                }
                
                if ($sell_remaining > 0) {
                    $stmt = $db->prepare(
                        "UPDATE orders SET amount = ? WHERE id = ?"
                    );
                    $stmt->execute([$sell_remaining, $sell_order['id']]);
                    $stock_orders['sell'][0]['amount'] = $sell_remaining;
                } else {
                    $stmt = $db->prepare(
                        "UPDATE orders SET status = 'completed' WHERE id = ?"
                    );
                    $stmt->execute([$sell_order['id']]);
                    array_shift($stock_orders['sell']);
                }
                
                // 更新股票当前价格
                $stmt = $db->prepare(
                    "UPDATE stocks SET current_price = ? WHERE id = ?"
                );
                $stmt->execute([$deal_price, $stock_id]);
                
                // 处理买卖双方资金和股票
                $buy_fee = $deal_price * $deal_amount * TRADE_FEE_RATE;
                $sell_fee = $deal_price * $deal_amount * TRADE_FEE_RATE;
                
                // 买方付款
                $buyer_account = new UserAccount($buy_order['user_id']);
                $buyer_account->updateBalance(-($deal_price * $deal_amount + $buy_fee));
                
                // 卖方收款
                $seller_account = new UserAccount($sell_order['user_id']);
                $seller_account->updateBalance($deal_price * $deal_amount - $sell_fee);
                
                // 更新系统手续费收入
                $stmt = $db->prepare(
                    "INSERT INTO system_income (type, amount, created_at)
                     VALUES ('trade_fee', ?, NOW())"
                );
                $stmt->execute([$buy_fee + $sell_fee]);
            } else {
                // 价格不匹配，结束撮合
                break;
            }
        }
    }
    
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    error_log('Trade matching error: ' . $e->getMessage());
} 