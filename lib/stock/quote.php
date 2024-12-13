<?php
class StockQuote {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取实时行情
    public function getQuote($stock_id) {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.name as company_name 
             FROM stocks s 
             JOIN companies c ON s.company_id = c.id 
             WHERE s.id = ?"
        );
        $stmt->execute([$stock_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 更新股票价格
    public function updatePrice($stock_id, $new_price) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE stocks SET 
                 current_price = ?, 
                 updated_at = NOW() 
                 WHERE id = ?"
            );
            $stmt->execute([$new_price, $stock_id]);
            
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 获取成交记录
    public function getTransactions($stock_id, $limit = 20) {
        $stmt = $this->db->prepare(
            "SELECT * FROM transactions 
             WHERE stock_id = ? 
             ORDER BY created_at DESC 
             LIMIT ?"
        );
        $stmt->execute([$stock_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 计算涨跌幅
    public function calculatePriceChange($stock_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                current_price,
                (SELECT price 
                 FROM transactions 
                 WHERE stock_id = ? 
                 AND created_at < CURDATE() 
                 ORDER BY created_at DESC 
                 LIMIT 1) as yesterday_price
             FROM stocks 
             WHERE id = ?"
        );
        $stmt->execute([$stock_id, $stock_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data['yesterday_price']) {
            return ['change' => 0, 'change_percent' => 0];
        }
        
        $change = $data['current_price'] - $data['yesterday_price'];
        $change_percent = ($change / $data['yesterday_price']) * 100;
        
        return [
            'change' => round($change, 2),
            'change_percent' => round($change_percent, 2)
        ];
    }
} 