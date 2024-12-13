<?php
class AdminAuth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 验证管理员权限
    public function checkAdmin() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login.php');
            exit;
        }
    }
    
    // 管理员登录
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare(
                "SELECT id FROM admin_users 
                 WHERE username = ? AND password = ? AND status = 'active'"
            );
            $hash = generate_password_hash($password);
            $stmt->execute([$username, $hash]);
            
            if ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['admin_id'] = $admin['id'];
                log_action($admin['id'], 'admin_login', '管理员登录');
                return ['success' => true];
            }
            
            return ['success' => false, 'message' => '用户名或密码错误'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 