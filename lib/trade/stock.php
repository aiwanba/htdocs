<?php
class StockManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取热门股票列表
    public function getHotStocks($limit = 10) {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.name as company_name,
                    (s.current_price - s.open_price) / s.open_price as price_change,
                    s.current_price * s.total_shares as market_value,
                    (SELECT COALESCE(SUM(amount), 0) FROM transactions 
                     WHERE stock_id = s.id 
                     AND DATE(created_at) = CURDATE()) as volume
             FROM stocks s
             JOIN companies c ON s.company_id = c.id
             WHERE s.status = 'active'
             ORDER BY volume DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取最新上市公司
    public function getLatestIPOs($limit = 5) {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.name as company_name
             FROM stocks s
             JOIN companies c ON s.company_id = c.id
             WHERE s.status = 'active'
             ORDER BY s.list_time DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取最新交易记录
    public function getLatestTrades($limit = 10) {
        $stmt = $this->db->prepare(
            "SELECT t.*, c.name as company_name
             FROM transactions t
             JOIN stocks s ON t.stock_id = s.id
             JOIN companies c ON s.company_id = c.id
             ORDER BY t.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取市场统计数据
    public function getMarketStats() {
        $stats = [];
        
        // 总市值
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(s.current_price * s.total_shares), 0) as total
             FROM stocks s
             WHERE s.status = 'active'"
        );
        $stmt->execute();
        $stats['total_market_value'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 上市公司数
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total
             FROM stocks s
             WHERE s.status = 'active'"
        );
        $stmt->execute();
        $stats['total_companies'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 今日成交额
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(price * amount), 0) as total
             FROM transactions
             WHERE DATE(created_at) = CURDATE()"
        );
        $stmt->execute();
        $stats['today_volume'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
    
    // 获取单个股票详情
    public function getStockDetail($stock_id) {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.name as company_name, c.description,
                    c.owner_id, c.created_at as company_created_at,
                    (s.current_price - s.open_price) / s.open_price as price_change
             FROM stocks s
             JOIN companies c ON s.company_id = c.id
             WHERE s.id = :stock_id"
        );
        $stmt->bindValue(':stock_id', $stock_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 