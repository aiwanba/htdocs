<?php
require_once 'lib/company/manage.php';

check_login();

$company_id = clean_input($_GET['id'] ?? '');

if (!$company_id) {
    header('Location: /company/manage.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// 检查公司所有权和状态
$stmt = $db->prepare(
    "SELECT c.*, s.total_shares, s.current_price 
     FROM companies c
     JOIN stocks s ON c.id = s.company_id
     WHERE c.id = ? AND c.owner_id = ? AND c.status = 'active'"
);
$stmt->execute([$company_id, $_SESSION['user_id']]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company || !$company['total_shares']) {
    header('Location: /company/manage.php?error=invalid_company');
    exit;
}

// 处理分红申请
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount_per_share = clean_input($_POST['amount_per_share']);
    $total_amount = $amount_per_share * $company['total_shares'];
    
    try {
        $db->beginTransaction();
        
        // 检查公司资金是否充足
        if ($company['capital'] < $total_amount) {
            throw new Exception('公司资金不足');
        }
        
        // 创���分红记录
        $stmt = $db->prepare(
            "INSERT INTO dividends (company_id, amount_per_share, created_at)
             VALUES (?, ?, NOW())"
        );
        $stmt->execute([$company_id, $amount_per_share]);
        
        // 更新公司资本
        $stmt = $db->prepare(
            "UPDATE companies SET capital = capital - ? WHERE id = ?"
        );
        $stmt->execute([$total_amount, $company_id]);
        
        // 给股东发放分红
        $stmt = $db->prepare(
            "SELECT user_id, SUM(CASE WHEN type = 'buy' THEN amount ELSE -amount END) as shares
             FROM transactions 
             WHERE stock_id = (SELECT id FROM stocks WHERE company_id = ?)
             GROUP BY user_id
             HAVING shares > 0"
        );
        $stmt->execute([$company_id]);
        $shareholders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($shareholders as $holder) {
            $dividend = $amount_per_share * $holder['shares'];
            $stmt = $db->prepare(
                "UPDATE users SET balance = balance + ? WHERE id = ?"
            );
            $stmt->execute([$dividend, $holder['user_id']]);
        }
        
        $db->commit();
        header('Location: /company/detail.php?id=' . $company_id . '&msg=dividend_success');
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $error = $e->getMessage();
    }
}
?>

<?php include 'templates/company/dividend.php'; ?> 