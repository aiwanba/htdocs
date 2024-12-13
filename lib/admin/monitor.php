<?php
class SystemMonitor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // 获取系统性能指标
    public function getSystemMetrics() {
        $metrics = [];
        
        // CPU使用率
        $load = sys_getloadavg();
        $metrics['cpu_load'] = [
            '1min' => $load[0],
            '5min' => $load[1],
            '15min' => $load[2]
        ];
        
        // 内存使用情况
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        
        $metrics['memory'] = [
            'total' => $mem[1],
            'used' => $mem[2],
            'free' => $mem[3],
            'usage' => round($mem[2] / $mem[1] * 100, 2)
        ];
        
        // 数据库状态
        $stmt = $this->db->prepare("SHOW STATUS");
        $stmt->execute();
        $db_status = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $db_status[$row['Variable_name']] = $row['Value'];
        }
        
        $metrics['database'] = [
            'connections' => $db_status['Threads_connected'],
            'max_connections' => $db_status['Max_used_connections'],
            'queries' => $db_status['Questions'],
            'slow_queries' => $db_status['Slow_queries']
        ];
        
        return $metrics;
    }
    
    // 获取在线用户数
    public function getOnlineUsers() {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM users 
             WHERE last_active > DATE_SUB(NOW(), INTERVAL 5 MINUTE)"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    // 获取实时交易统计
    public function getTradeStats() {
        $stats = [];
        
        // 今日交易量
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count, SUM(price * amount) as volume
             FROM transactions 
             WHERE DATE(created_at) = CURDATE()"
        );
        $stmt->execute();
        $today = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['today'] = [
            'count' => $today['count'],
            'volume' => $today['volume']
        ];
        
        // 最近1小时交易量
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count, SUM(price * amount) as volume
             FROM transactions 
             WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)"
        );
        $stmt->execute();
        $hour = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['last_hour'] = [
            'count' => $hour['count'],
            'volume' => $hour['volume']
        ];
        
        return $stats;
    }
    
    // 获取系统告警信息
    public function getAlerts() {
        $alerts = [];
        
        // 检查CPU负载
        $load = sys_getloadavg();
        if ($load[0] > 5) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'CPU负载过高: ' . $load[0]
            ];
        }
        
        // 检查内存使用
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        
        if (($mem[2] / $mem[1]) > 0.9) {
            $alerts[] = [
                'type' => 'danger',
                'message' => '内存使用率超过90%'
            ];
        }
        
        // 检查数据库连接数
        $stmt = $this->db->prepare("SHOW STATUS LIKE 'Threads_connected'");
        $stmt->execute();
        $connections = $stmt->fetch(PDO::FETCH_ASSOC)['Value'];
        
        if ($connections > 100) {
            $alerts[] = [
                'type' => 'warning',
                'message' => '数据库连接数过多: ' . $connections
            ];
        }
        
        return $alerts;
    }
} 