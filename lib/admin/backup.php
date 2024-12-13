<?php
require_once __DIR__ . '/../../lib/database.php';

class BackupManager {
    private $db;
    private $backup_dir = 'backups/';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 创建数据库备份
    public function createBackup() {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = $this->backup_dir . $filename;
            
            // 使用mysqldump命令备份
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                $path
            );
            
            exec($command, $output, $return_var);
            
            if ($return_var !== 0) {
                throw new Exception('备份创建失败');
            }
            
            // 记录备份信息
            $stmt = $this->db->prepare(
                "INSERT INTO backup_logs (filename, size, created_at)
                 VALUES (?, ?, NOW())"
            );
            $stmt->execute([$filename, filesize($path)]);
            
            return ['success' => true, 'filename' => $filename];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    // 获取备份列表
    public function getBackupList() {
        $stmt = $this->db->prepare(
            "SELECT * FROM backup_logs ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 删除备份
    public function deleteBackup($filename) {
        try {
            $path = $this->backup_dir . $filename;
            if (!file_exists($path)) {
                throw new Exception('备份文件不存在');
            }
            
            unlink($path);
            
            $stmt = $this->db->prepare(
                "DELETE FROM backup_logs WHERE filename = ?"
            );
            $stmt->execute([$filename]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 