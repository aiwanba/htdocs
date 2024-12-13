<?php
require_once 'lib/company/manage.php';

check_login();

$company_id = clean_input($_GET['id'] ?? '');

if (!$company_id) {
    header('Location: /company/manage.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// 获取公司信息
$stmt = $db->prepare(
    "SELECT c.*, s.total_shares 
     FROM companies c
     JOIN stocks s ON c.id = s.company_id
     WHERE c.id = ? AND c.status = 'active'"
);
$stmt->execute([$company_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    header('Location: /company/manage.php?error=invalid_company');
    exit;
}

// 获取用户持股数量
$stmt = $db->prepare(
    "SELECT SUM(CASE WHEN type = 'buy' THEN amount ELSE -amount END) as shares
     FROM transactions 
     WHERE stock_id = (SELECT id FROM stocks WHERE company_id = ?)
     AND user_id = ?"
);
$stmt->execute([$company_id, $_SESSION['user_id']]);
$user_shares = $stmt->fetch(PDO::FETCH_ASSOC)['shares'] ?? 0;

// 获取当前投票议案
$stmt = $db->prepare(
    "SELECT p.*, 
            (SELECT COUNT(*) FROM votes v WHERE v.proposal_id = p.id) as total_votes,
            (SELECT vote FROM votes v WHERE v.proposal_id = p.id AND v.user_id = ?) as user_vote
     FROM proposals p
     WHERE p.company_id = ? AND p.status = 'active'
     ORDER BY p.created_at DESC"
);
$stmt->execute([$_SESSION['user_id'], $company_id]);
$proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 处理投票
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $proposal_id = clean_input($_POST['proposal_id']);
    $vote = clean_input($_POST['vote']);
    
    try {
        $db->beginTransaction();
        
        // 检查是否已投票
        $stmt = $db->prepare(
            "SELECT id FROM votes 
             WHERE proposal_id = ? AND user_id = ?"
        );
        $stmt->execute([$proposal_id, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('您已经投过票了');
        }
        
        // 记录投票
        $stmt = $db->prepare(
            "INSERT INTO votes (proposal_id, user_id, vote, shares, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([$proposal_id, $_SESSION['user_id'], $vote, $user_shares]);
        
        $db->commit();
        header('Location: /company/meeting.php?id=' . $company_id . '&msg=vote_success');
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $error = $e->getMessage();
    }
}

// 如果是公司创始人，可以创建新议案
$can_create_proposal = ($company['owner_id'] == $_SESSION['user_id']);

?>

<?php include 'templates/company/meeting.php'; ?> 