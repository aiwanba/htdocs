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

// 处理议案创建
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $deadline = clean_input($_POST['deadline']);
    
    try {
        $stmt = $db->prepare(
            "INSERT INTO proposals (company_id, title, content, deadline, status, created_at)
             VALUES (?, ?, ?, ?, 'active', NOW())"
        );
        $stmt->execute([$company_id, $title, $content, $deadline]);
        
        header('Location: /company/meeting.php?id=' . $company_id . '&msg=proposal_created');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<?php include 'templates/company/proposal.php'; ?> 