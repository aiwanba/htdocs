<?php
class UserAuth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 用户注册
    public function register($username, $password, $email) {
        try {
            // 检查用户名是否已存在
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => '用户名已存在'];
            }
            
            // 创建新用户
            $hash = generate_password_hash($password);
            $stmt = $this->db->prepare(
                "INSERT INTO users (username, password, email, balance, status, created_at) 
                 VALUES (?, ?, ?, 0, 'active', NOW())"
            );
            $stmt->execute([$username, $hash, $email]);
            
            return ['success' => true, 'message' => '注册成功'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => '注册失败: ' . $e->getMessage()];
        }
    }
    
    // 用户登录
    public function login($username, $password) {
        try {
            $stmt = $this->db->prepare("SELECT id, password FROM users WHERE username = ? AND status = 'active'");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && hash(PASSWORD_HASH_ALGO, $password) === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                log_action($user['id'], 'login', '用户登录成功');
                return ['success' => true, 'message' => '登录成功'];
            }
            
            return ['success' => false, 'message' => '用户名或密码错误'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => '登录失败: ' . $e->getMessage()];
        }
    }
} 