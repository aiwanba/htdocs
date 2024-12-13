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
    "SELECT * FROM companies 
     WHERE id = ? AND owner_id = ? AND status = 'active'"
);
$stmt->execute([$company_id, $_SESSION['user_id']]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    header('Location: /company/manage.php?error=invalid_company');
    exit;
}

// 检查是否已有待审核的IPO申请
$stmt = $db->prepare(
    "SELECT id FROM ipo_applications 
     WHERE company_id = ? AND status = 'pending'"
);
$stmt->execute([$company_id]);
if ($stmt->rowCount() > 0) {
    header('Location: /company/manage.php?error=pending_ipo');
    exit;
}

// 处理IPO申请
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $share_amount = clean_input($_POST['share_amount']);
    $price_per_share = clean_input($_POST['price_per_share']);
    
    $company_manage = new CompanyManage($_SESSION['user_id']);
    $result = $company_manage->applyIPO($company_id, $share_amount, $price_per_share);
    
    if ($result['success']) {
        header('Location: /company/manage.php?msg=ipo_submitted');
        exit;
    }
    $error = $result['message'];
}
?>

<?php include 'templates/company/ipo.php'; ?> 