<?php
require_once './helpers/UserDAO.php';
require_once './helpers/MessageDAO.php'; 
require_once './helpers/CommentDAO.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$messages = [];
$newComments = [];

if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];

    // 获取用户通知
    $messages = []; // 确保清空数组
    $messages = MessageDAO::getContentByUserId($user->user_id);
    // 获取全体通知
    $globalMessages = MessageDAO::getAllNotifications($user->user_id);


    // 检查新评论
    $commentDAO = new CommentDAO();
    $goodsIds =  MessageDAO::getGoodsIdsByUserId($user->user_id);
    foreach ($goodsIds as $goodsId) {
        $comments = $commentDAO->get_comments_by_goods_id2($goodsId);
        foreach ($comments as $comment) {
            // 这里可以添加逻辑来过滤出新评论
            $newComments[] = $comment;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フリーマーケット</title>
    <link rel="stylesheet" href="css/HeaderStyle.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-custom">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo3.png" alt="Logo" class="circle-cropped">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- 添加搜索框 -->
                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0" action="index.php" method="get">
                        <input class="form-control mr-sm-2" type="search" placeholder="商品検索" aria-label="Search" name="query">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>
                <!-- 出品按钮，依据登录状态决定行为 -->
                <li class="nav-item">
                    <?php if (isset($user) && is_object($user)) : ?>
                        <a class="nav-link" href="produce.php">出品</a> <!-- 用户已登录，直接出品 -->
                    <?php else : ?>
                        <a class="nav-link" href="login.php">出品</a> <!-- 用户未登录，引导登录 -->
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mypage.php">マイページ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="like.php">お気に入り</a>
                </li>
                <?php if (isset($user) && is_object($user)) : ?>
                    <!-- 通知下拉菜单 -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            お知らせ
                            <span class="badge badge-danger"><?= count($messages) + count($newComments) + count($globalMessages) ?></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php foreach ($globalMessages as $gMessage) : ?>
                                <a class="dropdown-item" href="mark_read.php?allnf_id=<?= $gMessage->allnf_id ?>">
                                    <p>日電フリ事務局からのメッセージ：</p>
                                    <?= htmlspecialchars($gMessage->allnf) ?>
                                </a>
                            <?php endforeach; ?>


                            <?php foreach ($messages as $message) : ?>

                                <!-- 对于通知 -->

                                <a class="dropdown-item" href="mark_read.php?notification_id=<?= $message->notification_id ?>">
                                    <p>日電フリ事務局からのメッセージ：</p>
                                    <?= htmlspecialchars($message->content) ?>
                                </a>
                            <?php endforeach; ?>
                            <?php foreach ($newComments as $comment) : ?>
                                <!-- 对于评论 -->
                                <a class="dropdown-item" href="mark_read.php?comment_id=<?= $comment->comment_id ?>">
                                    あなたの <?= htmlspecialchars($comment->goods_name) ?> 商品で <?= htmlspecialchars($comment->username) ?> 様からのコメントがあります
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </li>
                    <!-- 用户头像 -->
                    <li class="nav-item">
                        <a class="nav-link d-lg-none" href="userprofile.php"> <!-- 用于小屏幕 -->
                            <img src="images/userIcons/<?= htmlspecialchars($user->icon_image ?? 'default_icon.png', ENT_QUOTES, 'UTF-8') ?>" class="rounded-circle" alt="User Icon" style="width: 30px; height: 30px; object-fit: cover;">
                            <?= htmlspecialchars($user->username ?? 'Unknown User', ENT_QUOTES, 'UTF-8') ?>
                        </a>
                        <span class="navbar-text d-none d-lg-block"> <!-- 用于大屏幕 -->
                            <img src="images/userIcons/<?= htmlspecialchars($user->icon_image ?? 'default_icon.png', ENT_QUOTES, 'UTF-8') ?>" class="rounded-circle" alt="User Icon" style="width: 30px; height: 30px; object-fit: cover;">
                            <?= htmlspecialchars($user->username ?? 'Unknown User', ENT_QUOTES, 'UTF-8') ?>さん
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ログアウト</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">ログイン</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Your content here -->

    <!-- 引入Bootstrap JavaScript 和依赖的 Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // 激活 Bootstrap 下拉菜单
            $('.dropdown-toggle').dropdown();
        });
    </script>

</body>

</html>