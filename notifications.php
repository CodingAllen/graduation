<?php
require_once './helpers/UserDAO.php';
require_once './helpers/MessageDAO.php'; 
require_once './helpers/CommentDAO.php'; 

session_start();

if (!isset($_SESSION['user'])) {
    // 处理未登录的情况，比如重定向到登录页面
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']->user_id;
$personalNotifications = MessageDAO::getAllPersonalNotifications($user_id);
$globalNotifications = MessageDAO::getAllGlobalNotifications($user_id);
$commentNotifications = MessageDAO::getAllCommentsNotifications($user_id);

// 然后在HTML中显示这些通知
include('header.php');

?>

<!DOCTYPE html>
<html>
<head>
    <title>通知中心</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.main-container {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    text-align: center;
}

h2 {
    color: #444;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.notification {
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.notification:hover {
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
}

.personal {
    background-color: #e7f4e4;
}

.global {
    background-color: #e4f0f6;
}

.comment {
    background-color: #fff3e4;
}

.read {
    opacity: 0.6;
}

.unread {
    font-weight: bold;
}

.notification a {
    text-decoration: none;
    color: #333;
}

.notification a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="main-container">
        <h1>お知らせ履歴</h1>
        
       <!-- 个人通知 -->
<h2>個人通知</h2>
<?php foreach ($personalNotifications as $notification) : ?>
    <div class="<?= $notification->notif_read ? 'notification read' : 'notification unread personal' ?>">
        <a href="mark_read.php?notification_id=<?= $notification->notification_id ?>">
            <?= htmlspecialchars($notification->content) ?>
        </a>
        <?= $notification->notif_read ? " (個人 - 済み)" : " (個人)" ?>
    </div>
<?php endforeach; ?>

<!-- 全体通知 -->
<h2>全体通知</h2>
<?php foreach ($globalNotifications as $notification) : ?>
    <div class="<?= isset($notification->read_user) ? 'notification read' : 'notification unread global' ?>">
        <a href="mark_read.php?allnf_id=<?= $notification->allnf_id ?>">
            <?= htmlspecialchars($notification->allnf) ?>
        </a>
        <?= isset($notification->read_user) ? " (全体 - 済み)" : " (全体)" ?>
    </div>
<?php endforeach; ?>


        <!-- 商品留言通知 -->
        <h2>コメント</h2>
        <?php foreach ($commentNotifications as $notification) : ?>
            <div class="<?= $notification->comm_read ? 'notification read' : 'notification unread comment' ?>">
                <a href="goods.php?goods_id=<?= htmlspecialchars($notification->goods_id) ?>">
                    <?= htmlspecialchars($notification->comment_text) ?>
                </a>
                <?= $notification->comm_read ? " (コメント - 済み)" : " (コメント)" ?>
            </div>
        <?php endforeach; ?>

    </div>
    <?php include('footer.php'); ?>
</body>

</html>
