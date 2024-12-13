<?php
class TradeOrder {
    private $db;
    private $user_id;
    
    public function __construct($user_id) {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }
    
    // 创建交易订单
    public function createOrder($stock_id, $type, $price, $amount) {
        try {
            $this->db->beginTransaction();
            
            // 检查股票状态
            $stmt = $this->db->prepare("SELECT status FROM stocks WHERE id = ?");
            $stmt->execute([$stock_id]);
            $stock = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$stock || $stock['status'] != 'active') {
                throw new Exception('股票不可交易');
            }
            
            // 检查价格限制
            $this->checkPriceLimit($stock_id, $price);
            
            // 如果是买入订单，检查资金是否充足
            if ($type == 'buy') {
                $total_cost = $price * $amount * (1 + TRADE_FEE_RATE);
                $account = new UserAccount($this->user_id);
                if ($account->getBalance() < $total_cost) {
                    throw new Exception('账户余额不足');
                }
                
                // 冻结资金
                $account->updateBalance($total_cost, 'subtract');
            }
            
            // 如果是卖出订单，检查持仓是否充足
            if ($type == 'sell') {
                $position = $this->getPosition($stock_id);
                if ($position < $amount) {
                    throw new Exception('持仓不足');
                }
            }
            
            // 创建订单
            $stmt = $this->db->prepare(
                "INSERT INTO orders (user_id, stock_id, type, price, amount, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, 'pending', NOW())"
            );
            $stmt->execute([$this->user_id, $stock_id, $type, $price, $amount]);
            
            $this->db->commit();
            return ['success' => true, 'message' => '订单创建成功'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 检查价格限制
    private function checkPriceLimit($stock_id, $price) {
        $stmt = $this->db->prepare("SELECT current_price FROM stocks WHERE id = ?");
        $stmt->execute([$stock_id]);
        $stock = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $price_limit = $stock['current_price'] * PRICE_LIMIT_RATE;
        if (abs($price - $stock['current_price']) > $price_limit) {
            throw new Exception('价格超出涨跌幅限制');
        }
    }
    
    // 获取持仓数量
    private function getPosition($stock_id) {
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) as total FROM transactions 
             WHERE user_id = ? AND stock_id = ? AND type = 'buy'"
        );
        $stmt->execute([$this->user_id, $stock_id]);
        $buy = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) as total FROM transactions 
             WHERE user_id = ? AND stock_id = ? AND type = 'sell'"
        );
        $stmt->execute([$this->user_id, $stock_id]);
        $sell = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        return $buy - $sell;
    }
} 