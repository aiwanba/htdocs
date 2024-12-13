<?php
class Database {
    private static $instance = null;
    private $connection = null;
    
    private function __construct() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                DB_HOST,
                DB_NAME
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new Exception("数据库连接失败: " . $e->getMessage());
        }
    }
    
    // 获取数据库实例（单例模式）
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // 获取数据库连接
    public function getConnection() {
        return $this->connection;
    }
    
    // 开始事务
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    // 提交事务
    public function commit() {
        return $this->connection->commit();
    }
    
    // 回滚事务
    public function rollBack() {
        return $this->connection->rollBack();
    }
    
    // 防止克隆
    private function __clone() {}
    
    // 防止反序列化
    public function __wakeup() {
        throw new Exception("不能反序列化单例");
    }
} 