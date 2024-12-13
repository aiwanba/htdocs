<?php
class AdminManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取管理员列表
    public function getAdminList() {
        $stmt = $this->db->prepare(
            "SELECT * FROM admin_users ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 添加管理员
    public function addAdmin($username, $password, $role) {
        try {
            // 检查用户名是否已存在
            $stmt = $this->db->prepare(
                "SELECT id FROM admin_users WHERE username = ?"
            );
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('管理员用户名已存在');
            }
            
            // 创建管理员账号
            $stmt = $this->db->prepare(
                "INSERT INTO admin_users (username, password, role, status, created_at)
                 VALUES (?, ?, ?, 'active', NOW())"
            );
            $hash = generate_password_hash($password);
            $stmt->execute([$username, $hash, $role]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 更新管理员状态
    public function updateAdminStatus($admin_id, $status) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE admin_users SET status = ? WHERE id = ?"
            );
            $stmt->execute([$status, $admin_id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 修改管理员密码
    public function changePassword($admin_id, $old_password, $new_password) {
        try {
            // 验证旧密码
            $stmt = $this->db->prepare(
                "SELECT password FROM admin_users WHERE id = ?"
            );
            $stmt->execute([$admin_id]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$admin || $admin['password'] !== generate_password_hash($old_password)) {
                throw new Exception('原密码错误');
            }
            
            // 更新密码
            $stmt = $this->db->prepare(
                "UPDATE admin_users SET password = ? WHERE id = ?"
            );
            $hash = generate_password_hash($new_password);
            $stmt->execute([$hash, $admin_id]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 