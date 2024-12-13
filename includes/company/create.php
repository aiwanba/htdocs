<?php
require_once 'lib/company/manage.php';

check_login();

// 检查用户已创建的公司数量
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT COUNT(*) as count FROM companies WHERE owner_id = ?");
$stmt->execute([$_SESSION['user_id']]);
if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] >= MAX_COMPANIES_PER_USER) {
    header('Location: /company/manage.php?error=max_companies_reached');
    exit;
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $capital = clean_input($_POST['capital']);
    $business_type = clean_input($_POST['business_type']);
    
    $company = new CompanyManage($_SESSION['user_id']);
    $result = $company->createCompany($name, $capital, $business_type);
    
    if ($result['success']) {
        header('Location: /company/manage.php?msg=create_success');
        exit;
    }
    $error = $result['message'];
}
?>

<?php include 'templates/company/create.php'; ?> 