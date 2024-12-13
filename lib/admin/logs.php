<?php
class LogManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取系统日志
    public function getLogs($page = 1, $limit = 50, $type = null) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT l.*, u.username 
                FROM user_logs l
                LEFT JOIN users u ON l.user_id = u.id";
        $params = [];
        
        if ($type) {
            $sql .= " WHERE l.action = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取日志总数
    public function getTotalLogs($type = null) {
        $sql = "SELECT COUNT(*) as total FROM user_logs";
        $params = [];
        
        if ($type) {
            $sql .= " WHERE action = ?";
            $params[] = $type;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    // 获取日志类型列表
    public function getLogTypes() {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT action FROM user_logs ORDER BY action"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
} 