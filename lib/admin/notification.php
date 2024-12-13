<?php
class NotificationManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 创建系统通知
    public function createNotification($title, $content, $type = 'info') {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO system_notifications (title, content, type, created_at)
                 VALUES (?, ?, ?, NOW())"
            );
            $stmt->execute([$title, $content, $type]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 获取通知列表
    public function getNotifications($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare(
            "SELECT * FROM system_notifications 
             ORDER BY created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 删除通知
    public function deleteNotification($id) {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM system_notifications WHERE id = ?"
            );
            $stmt->execute([$id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 