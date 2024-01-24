<?php
require_once './helpers/AdminDAO.php';
$dao = new AdminDAO();

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];

    if ($type == 'user') {
        // 删除用户
        $dao->deleteUser($id);
    } elseif ($type == 'good') {
        // 删除商品
        $dao->deleteGood($id);
    }
}

header('Location: Admin.php');
exit;
?>
