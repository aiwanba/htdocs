<?php
class MaintenanceManager {
    private $db;
    private $config_file = 'config/maintenance.php';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 开启维护模式
    public function enableMaintenance($message = '') {
        try {
            file_put_contents($this->config_file, "<?php return true;");
            
            if ($message) {
                $this->setMaintenanceMessage($message);
            }
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 关闭维护模式
    public function disableMaintenance() {
        try {
            file_put_contents($this->config_file, "<?php return false;");
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 设置维护提示信息
    public function setMaintenanceMessage($message) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO system_settings (`key`, `value`) 
                 VALUES ('maintenance_message', ?)
                 ON DUPLICATE KEY UPDATE `value` = ?"
            );
            $stmt->execute([$message, $message]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 