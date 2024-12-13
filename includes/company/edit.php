<?php
require_once 'lib/database.php';
require_once 'lib/utils.php';
require_once 'lib/security.php';

check_login();

$error = '';
$success = '';
$company_id = isset($parts[2]) ? (int)$parts[2] : 0;

if ($company_id <= 0) {
    header('Location: /user/company');
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // 获取公司信息
    $stmt = $db->prepare(
        "SELECT * FROM cs_companies WHERE id = ? AND owner_id = ?"
    );
    $stmt->execute([$company_id, $_SESSION['user_id']]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$company) {
        throw new Exception('公司不存在或您没有权限');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        verify_csrf_request();
        
        $name = clean_input($_POST['name']);
        $description = clean_input($_POST['description']);
        
        if (empty($name)) {
            throw new Exception('公司名称不能为空');
        }
        
        if (strlen($name) < 2 || strlen($name) > 100) {
            throw new Exception('公司名称长度必须在2-100个字符之间');
        }
        
        $db->beginTransaction();
        
        try {
            // 检查公司名称是否已被使用
            $stmt = $db->prepare(
                "SELECT id FROM cs_companies WHERE name = ? AND id != ?"
            );
            $stmt->execute([$name, $company_id]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('该公司名称已被使用');
            }
            
            // 更新公司信息
            $stmt = $db->prepare(
                "UPDATE cs_companies 
                 SET name = ?, description = ?, updated_at = NOW()
                 WHERE id = ?"
            );
            $stmt->execute([$name, $description, $company_id]);
            
            // 记录日志
            $stmt = $db->prepare(
                "INSERT INTO cs_user_logs (user_id, action, ip_address, created_at)
                 VALUES (?, 'edit_company', ?, NOW())"
            );
            $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
            
            $db->commit();
            
            $success = '更新成功';
            $company['name'] = $name;
            $company['description'] = $description;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    // 设置页���标题
    $page_title = '编辑公司 - ' . $company['name'];
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

include 'templates/company/edit.php'; 