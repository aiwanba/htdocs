<?php
class SystemSettings {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取系统设置
    public function getSettings() {
        $stmt = $this->db->prepare("SELECT * FROM system_settings");
        $stmt->execute();
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
    
    // 更新系统设置
    public function updateSettings($settings) {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                $stmt = $this->db->prepare(
                    "INSERT INTO system_settings (`key`, `value`) 
                     VALUES (?, ?)
                     ON DUPLICATE KEY UPDATE `value` = ?"
                );
                $stmt->execute([$key, $value, $value]);
            }
            
            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 获取系统统计数据
    public function getSystemStats() {
        $stats = [];
        
        // 用户统计
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 公司统计
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM companies");
        $stmt->execute();
        $stats['total_companies'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 交易统计
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count, 
                    SUM(price * amount) as volume 
             FROM transactions"
        );
        $stmt->execute();
        $trade_stats = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_trades'] = $trade_stats['count'];
        $stats['total_volume'] = $trade_stats['volume'];
        
        // 系统收入
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) as total FROM system_income"
        );
        $stmt->execute();
        $stats['total_income'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
} 