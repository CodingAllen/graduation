<?php
require_once './helpers/MessageDAO.php';
require_once './helpers/CommentDAO.php';
require_once './helpers/UserDAO.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user']->user_id;

if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];
    MessageDAO::markAsRead($notification_id);
    // 保持原来的重定向逻辑
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];
    $commentDAO = new CommentDAO();
    // 获取评论信息，包括所属商品的ID
    $comment = $commentDAO->getCommentById($comment_id);
    if ($comment) {
        $commentDAO->markCommentAsRead($comment_id);
        // 重定向到商品页面
        header('Location: goods.php?goods_id=' . $comment->goods_id);
        exit;
    }
}
if (isset($_GET['allnf_id'])) {
    $allnf_id = $_GET['allnf_id'];
    MessageDAO::markGlobalNotificationAsRead($user_id, $allnf_id);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
}


// 如果没有任何操作，重定向回原来的页面
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
