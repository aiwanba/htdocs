<?php
require_once 'lib/company/manage.php';
require_once 'lib/stock/quote.php';

check_login();

$company_id = clean_input($_GET['id'] ?? '');

if (!$company_id) {
    header('Location: /company/manage.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// 获取公司详细信息
$stmt = $db->prepare(
    "SELECT c.*, 
            COALESCE(s.total_shares, 0) as total_shares,
            COALESCE(s.current_price, 0) as stock_price,
            u.username as owner_name
     FROM companies c
     LEFT JOIN stocks s ON c.id = s.company_id
     JOIN users u ON c.owner_id = u.id
     WHERE c.id = ?"
);
$stmt->execute([$company_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    header('Location: /company/manage.php?error=company_not_found');
    exit;
}

// 获取最近的分红记录
$stmt = $db->prepare(
    "SELECT * FROM dividends 
     WHERE company_id = ? 
     ORDER BY created_at DESC 
     LIMIT 5"
);
$stmt->execute([$company_id]);
$dividends = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取股东列表
if ($company['total_shares'] > 0) {
    $stmt = $db->prepare(
        "SELECT u.username, 
                SUM(CASE WHEN t.type = 'buy' THEN t.amount ELSE -t.amount END) as shares
         FROM transactions t
         JOIN users u ON t.user_id = u.id
         WHERE t.stock_id = (SELECT id FROM stocks WHERE company_id = ?)
         GROUP BY u.id, u.username
         HAVING shares > 0
         ORDER BY shares DESC"
    );
    $stmt->execute([$company_id]);
    $shareholders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include 'templates/company/detail.php'; ?> 