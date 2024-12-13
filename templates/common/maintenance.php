<!DOCTYPE html>
<html>
<head>
    <title>系统维护中 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <div class="maintenance-page">
        <h1>系统维护中</h1>
        <p><?php 
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT value FROM system_settings WHERE `key` = 'maintenance_message'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($result['value'] ?? '系统正在维护中，请稍后再试...');
        ?></p>
    </div>
</body>
</html> 