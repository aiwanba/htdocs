<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        verify_csrf_request();
        
        $name = clean_input($_POST['name']);
        $description = clean_input($_POST['description']);
        
        if (empty($name)) {
            throw new Exception('公司名称不能为空');
        }
        
        if (strlen($name) < 2 || strlen($name) > 100) {
            throw new Exception('公司名称长度必须在2-100个字符之间');
        }
        
        $db = Database::getInstance()->getConnection();
        
        // 检查公司名称是否已存在
        $stmt = $db->prepare("SELECT id FROM cs_companies WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('该公司名称已被使用');
        }
        
        // 创建公司
        $stmt = $db->prepare(
            "INSERT INTO cs_companies (name, description, owner_id, status, created_at, updated_at)
             VALUES (?, ?, ?, 'active', NOW(), NOW())"
        );
        $stmt->execute([$name, $description, $_SESSION['user_id']]);
        
        $company_id = $db->lastInsertId();
        
        // 记录日志
        $stmt = $db->prepare(
            "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
             VALUES (?, 'create_company', ?, NOW())"
        );
        $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
        
        // 跳转到公司详情页
        header('Location: /company/view/' . $company_id);
        exit;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// 设置页面标题
$page_title = '创建公司';

include 'templates/company/create.php'; 