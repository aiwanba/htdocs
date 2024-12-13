<?php
require_once 'lib/utils.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo SITE_NAME; ?> - 系统监控</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="/assets/js/chart.min.js"></script>
</head>
<body>
    <?php include 'templates/admin/header.php'; ?>
    
    <div class="admin-container">
        <h2>系统监控</h2>
        
        <!-- 系统告警 -->
        <?php if ($alerts): ?>
        <div class="alert-panel">
            <?php foreach ($alerts as $alert): ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <?php echo $alert['message']; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- 实时数据 -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3>在线用户</h3>
                <div class="metric-value" id="online-users">
                    <?php echo $online_users; ?>
                </div>
            </div>
            
            <div class="metric-card">
                <h3>今日交易</h3>
                <div class="metric-value">
                    <div>笔数: <?php echo $trade_stats['today']['count']; ?></div>
                    <div>金额: <?php echo format_money($trade_stats['today']['volume']); ?></div>
                </div>
            </div>
            
            <div class="metric-card">
                <h3>最近1小时</h3>
                <div class="metric-value">
                    <div>笔数: <?php echo $trade_stats['last_hour']['count']; ?></div>
                    <div>金额: <?php echo format_money($trade_stats['last_hour']['volume']); ?></div>
                </div>
            </div>
        </div>
        
        <!-- 系统性能 -->
        <div class="performance-panel">
            <div class="chart-container">
                <h3>CPU负载</h3>
                <canvas id="cpu-chart"></canvas>
                <div class="metrics-data">
                    <div>1分钟: <?php echo $metrics['cpu_load']['1min']; ?></div>
                    <div>5分钟: <?php echo $metrics['cpu_load']['5min']; ?></div>
                    <div>15分钟: <?php echo $metrics['cpu_load']['15min']; ?></div>
                </div>
            </div>
            
            <div class="chart-container">
                <h3>内存使用</h3>
                <canvas id="memory-chart"></canvas>
                <div class="metrics-data">
                    <div>总内存: <?php echo format_size($metrics['memory']['total']); ?></div>
                    <div>已用: <?php echo format_size($metrics['memory']['used']); ?></div>
                    <div>空闲: <?php echo format_size($metrics['memory']['free']); ?></div>
                    <div>使用率: <?php echo $metrics['memory']['usage']; ?>%</div>
                </div>
            </div>
        </div>
        
        <!-- 数据库状态 -->
        <div class="database-panel">
            <h3>数据库状态</h3>
            <table>
                <tr>
                    <th>当前连接数</th>
                    <td><?php echo $metrics['database']['connections']; ?></td>
                    <th>历史最大连接数</th>
                    <td><?php echo $metrics['database']['max_connections']; ?></td>
                </tr>
                <tr>
                    <th>总查询次数</th>
                    <td><?php echo $metrics['database']['queries']; ?></td>
                    <th>慢查询次数</th>
                    <td><?php echo $metrics['database']['slow_queries']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <script>
    // 初始化图表
    function initCharts() {
        // CPU负载图表
        new Chart(document.getElementById('cpu-chart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['1min', '5min', '15min'],
                datasets: [{
                    label: 'CPU负载',
                    data: [
                        <?php echo $metrics['cpu_load']['1min']; ?>,
                        <?php echo $metrics['cpu_load']['5min']; ?>,
                        <?php echo $metrics['cpu_load']['15min']; ?>
                    ],
                    borderColor: '#4CAF50',
                    tension: 0.1
                }]
            }
        });
        
        // 内存使用图表
        new Chart(document.getElementById('memory-chart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['已用', '空闲'],
                datasets: [{
                    data: [
                        <?php echo $metrics['memory']['used']; ?>,
                        <?php echo $metrics['memory']['free']; ?>
                    ],
                    backgroundColor: ['#F44336', '#4CAF50']
                }]
            }
        });
    }
    
    // 定时刷新数据
    function refreshData() {
        fetch('/admin/monitor.php?ajax=1')
            .then(response => response.json())
            .then(data => {
                document.getElementById('online-users').textContent = data.online_users;
                // 更新其他实时数据...
            });
    }
    
    initCharts();
    setInterval(refreshData, 30000); // 每30秒刷新一次
    </script>
    
    <?php include 'templates/admin/footer.php'; ?>
</body>
</html> 