<?php
class UserAccount {
    private $db;
    private $user_id;
    
    public function __construct($user_id) {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }
    
    // 获取账户余额
    public function getBalance() {
        $stmt = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$this->user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['balance'] : 0;
    }
    
    // 更新账户余额
    public function updateBalance($amount, $type = 'add') {
        try {
            $this->db->beginTransaction();
            
            // 获取当前余额
            $current_balance = $this->getBalance();
            
            // 计算新余额
            $new_balance = $type == 'add' ? 
                          $current_balance + $amount : 
                          $current_balance - $amount;
            
            // 检查余额是否充足
            if ($type == 'subtract' && $new_balance < 0) {
                throw new Exception('余额不足');
            }
            
            // 更新余额
            $stmt = $this->db->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $stmt->execute([$new_balance, $this->user_id]);
            
            // 记录交易日志
            $action = $type == 'add' ? 'balance_add' : 'balance_subtract';
            log_action($this->user_id, $action, "金额: {$amount}");
            
            $this->db->commit();
            return ['success' => true, 'message' => '余额更新成功'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 