<?php
class IPOManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取待审核的IPO申请
    public function getPendingApplications() {
        $stmt = $this->db->prepare(
            "SELECT i.*, c.name as company_name, c.capital, c.business_type
             FROM ipo_applications i
             JOIN companies c ON i.company_id = c.id
             WHERE i.status = 'pending'
             ORDER BY i.created_at ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 审核IPO申请
    public function reviewApplication($application_id, $approved, $reason = '') {
        try {
            $this->db->beginTransaction();
            
            // 获取申请信息
            $stmt = $this->db->prepare(
                "SELECT i.*, c.capital 
                 FROM ipo_applications i
                 JOIN companies c ON i.company_id = c.id
                 WHERE i.id = ? AND i.status = 'pending'"
            );
            $stmt->execute([$application_id]);
            $application = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$application) {
                throw new Exception('申请不存在或已处理');
            }
            
            if ($approved) {
                // 创建股票记录
                $stmt = $this->db->prepare(
                    "INSERT INTO stocks (company_id, total_shares, current_price, status)
                     VALUES (?, ?, ?, 'active')"
                );
                $stmt->execute([
                    $application['company_id'],
                    $application['share_amount'],
                    $application['price_per_share']
                ]);
                
                $status = 'approved';
            } else {
                $status = 'rejected';
            }
            
            // 更新申请状态
            $stmt = $this->db->prepare(
                "UPDATE ipo_applications 
                 SET status = ?, review_reason = ?, reviewed_at = NOW()
                 WHERE id = ?"
            );
            $stmt->execute([$status, $reason, $application_id]);
            
            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 