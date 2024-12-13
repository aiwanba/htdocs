<?php
class UserProfile {
    private $db;
    private $user_id;
    
    public function __construct($user_id) {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }
    
    // 获取用户资料
    public function getProfile() {
        $stmt = $this->db->prepare(
            "SELECT username, email, balance, status, created_at 
             FROM users WHERE id = ?"
        );
        $stmt->execute([$this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 更新用户资料
    public function updateProfile($data) {
        try {
            $allowed_fields = ['email'];
            $updates = [];
            $values = [];
            
            foreach ($data as $field => $value) {
                if (in_array($field, $allowed_fields)) {
                    $updates[] = "{$field} = ?";
                    $values[] = $value;
                }
            }
            
            if (empty($updates)) {
                return ['success' => false, 'message' => '没有可更新的字段'];
            }
            
            $values[] = $this->user_id;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
            
            log_action($this->user_id, 'profile_update', '更新个人资料');
            return ['success' => true, 'message' => '资料更新成功'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => '更新失败: ' . $e->getMessage()];
        }
    }
    
    // 修改密码
    public function changePassword($old_password, $new_password) {
        try {
            // 验证旧密码
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || hash(PASSWORD_HASH_ALGO, $old_password) !== $user['password']) {
                return ['success' => false, 'message' => '原密码错误'];
            }
            
            // 更新新密码
            $hash = generate_password_hash($new_password);
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hash, $this->user_id]);
            
            log_action($this->user_id, 'password_change', '密码修改成功');
            return ['success' => true, 'message' => '密码修改成功'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => '密码修改失败: ' . $e->getMessage()];
        }
    }
} 