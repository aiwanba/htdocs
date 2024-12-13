<?php
class UserManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取用户列表
    public function getUserList($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare(
            "SELECT u.*, 
                    (SELECT COUNT(*) FROM companies WHERE owner_id = u.id) as company_count,
                    (SELECT SUM(amount) FROM transactions WHERE user_id = u.id) as trade_volume
             FROM users u
             ORDER BY u.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 获取用户总数
    public function getTotalUsers() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    // 更新用户状态
    public function updateUserStatus($user_id, $status) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE users SET status = ? WHERE id = ?"
            );
            $stmt->execute([$status, $user_id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 获取用户详细信息
    public function getUserDetail($user_id) {
        $stmt = $this->db->prepare(
            "SELECT u.*,
                    (SELECT COUNT(*) FROM companies WHERE owner_id = u.id) as company_count,
                    (SELECT COUNT(*) FROM transactions WHERE user_id = u.id) as trade_count,
                    (SELECT SUM(amount) FROM transactions WHERE user_id = u.id) as trade_volume
             FROM users u
             WHERE u.id = ?"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 