<?php
class CompanyManage {
    private $db;
    private $user_id;
    
    public function __construct($user_id) {
        $this->db = Database::getInstance()->getConnection();
        $this->user_id = $user_id;
    }
    
    // 创建公司
    public function createCompany($name, $capital, $business_type) {
        try {
            // 检查用户创建的公司数量
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) as count FROM companies WHERE owner_id = ?"
            );
            $stmt->execute([$this->user_id]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] >= MAX_COMPANIES_PER_USER) {
                throw new Exception('已达到最大创建公司数量限制');
            }
            
            // 检查公司名称是否已存在
            $stmt = $this->db->prepare("SELECT id FROM companies WHERE name = ?");
            $stmt->execute([$name]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('公司名称已存在');
            }
            
            // 检查资金是否充足
            $account = new UserAccount($this->user_id);
            if ($account->getBalance() < $capital) {
                throw new Exception('账户余额不足');
            }
            
            $this->db->beginTransaction();
            
            // 扣除创建资金
            $account->updateBalance($capital, 'subtract');
            
            // 创建公司
            $stmt = $this->db->prepare(
                "INSERT INTO companies (name, owner_id, capital, business_type, status, created_at) 
                 VALUES (?, ?, ?, ?, 'active', NOW())"
            );
            $stmt->execute([$name, $this->user_id, $capital, $business_type]);
            
            $this->db->commit();
            return ['success' => true, 'message' => '公司创建成功'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 申请IPO
    public function applyIPO($company_id, $share_amount, $price_per_share) {
        try {
            // 检查公司所有权
            $stmt = $this->db->prepare(
                "SELECT * FROM companies WHERE id = ? AND owner_id = ?"
            );
            $stmt->execute([$company_id, $this->user_id]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$company) {
                throw new Exception('无权操作该公司');
            }
            
            // 检查公司资产是否达到上市条件
            if ($company['capital'] < MIN_IPO_CAPITAL) {
                throw new Exception('公司资产未达到上市条件');
            }
            
            // 创建IPO申请
            $stmt = $this->db->prepare(
                "INSERT INTO ipo_applications (company_id, share_amount, price_per_share, status, created_at) 
                 VALUES (?, ?, ?, 'pending', NOW())"
            );
            $stmt->execute([$company_id, $share_amount, $price_per_share]);
            
            return ['success' => true, 'message' => 'IPO申请提交成功'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 