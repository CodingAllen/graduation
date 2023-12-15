<?php
require_once './helpers/UserDAO.php';
require_once './helpers/GoodsDAO.php';
require_once './helpers/CommentDAO.php';
require_once './helpers/FavoriteDAO.php';

session_start();
if (isset($_GET['goods_id']) && is_numeric($_GET['goods_id'])) {
    $goods_id = intval($_GET['goods_id']);
    // 现在可以使用 $goods_id 来获取商品和评论信息
}
// 保存原始的 goods_id
$originalGoodsId = isset($_GET['goods_id']) ? $_GET['goods_id'] : null;

if (isset($_POST['add_to_favorite'], $_POST['favorite_goods_id'], $_SESSION['user'])) {
    $favoriteDAO = new FavoriteDAO();
    $user_id = $_SESSION['user']->user_id;
    $goods_id = $_POST['favorite_goods_id'];

    // 添加到收藏
    $favoriteDAO->add_to_favorite($user_id, $goods_id);

    // 可以选择重定向到 like.php 或者留在当前页面
    header("Location: like.php");
    exit();
}


if (isset($_POST['delete_comment'], $_POST['comment_id'], $_SESSION['user'])) {
    $commentDAO = new CommentDAO();
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user']->user_id;

    // 执行删除操作
    $commentDAO->delete_comment($comment_id, $user_id);

    // 重新加载页面以显示更新后的留言
    header("Location: goods.php?goods_id=" . $_POST['goods_id']);
    exit();
}

// 处理留言提交
if (isset($_POST['comment_text']) && isset($_SESSION['user']) && isset($_GET['goods_id'])) {
    $commentDAO = new CommentDAO();
    $comment = new Comment();
    $comment->user_id = $_SESSION['user']->user_id;
    $comment->goods_id = $_GET['goods_id'];
    $comment->comment_text = $_POST['comment_text'];

    // 添加留言到数据库
    $commentDAO->add_comment($comment);

    // 重新加载页面以显示新留言
    header('Location: goods.php?goods_id=' . $_GET['goods_id']);
    exit();
}
if (isset($_GET['goods_id'])) {
    $goods_id = $_GET['goods_id'];
    $goodsDAO = new GoodsDAO();
    $goods = $goodsDAO->get_goods_by_id($goods_id);
    // 获取出品者信息
    $userDAO = new UserDAO();
    $seller = $userDAO->get_user_by_id($goods->user_id);
}
// 处理价格更新
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_price'], $_POST['goods_id'], $_SESSION['user'])) {
    $new_price = $_POST['new_price'];
    $goods_id = $_POST['goods_id'];

    if ($_SESSION['user']->user_id == $goods->user_id) {
        $goodsDAO->update_goods_price($goods_id, $new_price);
        header('Location: goods.php?goods_id=' . $goods_id);
        exit();
    }
}

// 处理商品详情更新
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_goods_detail'], $_POST['goods_id'], $_SESSION['user'])) {
    $new_goods_detail = $_POST['new_goods_detail'];
    $goods_id = $_POST['goods_id'];

    if ($_SESSION['user']->user_id == $goods->user_id) {
        $goodsDAO->update_goods_detail($goods_id, $new_goods_detail);
        header('Location: goods.php?goods_id=' . $goods_id);
        exit();
    }
}



$status_map = [
    1 => '新品',
    2 => '未使用に近い',
    3 => '目立った傷や汚れなし',
    4 => 'やや傷や汚れあり',
    5 => '傷や汚れあり',
    6 => '全体的に状態が悪い',
    7 => '故障や不具合ありのジャンク品',
    8 => '修理必須',
    9 => '部品取り',
    10 => 'その他'
];
$payers = [
    1 => '売手',
    2 => '買手'
];
$commentDAO = new CommentDAO();
$comments = $commentDAO->get_comments_by_goods_id($goods_id);

include "header.php"; // 包含 header.php

// 现在可以使用 $originalGoodsId 来获取商品和评论信息
if ($originalGoodsId !== null) {
    $goodsDAO = new GoodsDAO();
    $goods = $goodsDAO->get_goods_by_id($originalGoodsId);
    $userDAO = new UserDAO();
    $seller = $userDAO->get_user_by_id($goods->user_id);
    $commentDAO = new CommentDAO();
    $comments = $commentDAO->get_comments_by_goods_id($originalGoodsId);
}
//商品は購入されたかどうかを確認する
if (isset($_GET['goods_id'])) {
    $goods_id = $_GET['goods_id'];
    $goodsDAO = new GoodsDAO();
    $goods = $goodsDAO->get_goods_by_id($goods_id);
    $isPurchased = $goodsDAO->is_goods_purchased($goods_id);
    $userDAO = new UserDAO();
    $seller = $userDAO->get_user_by_id($goods->user_id);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Goods</title>
    <style>
        .sold-out-alert {
            background-color: red;
            /* 红色背景 */
            color: white;
            /* 白色文字 */
            padding: 10px;
            /* 内边距 */
            border-radius: 5px;
            /* 圆角边框 */
            margin-bottom: 10px;
            /* 底部外边距 */
        }

        .card-img-top {
            height: 300px;
            /* 设置图片高度 */
            width: auto;
            /* 宽度自动 */
            object-fit: cover;
            /* 裁剪以适应高度 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* 图片阴影 */
            transition: transform 0.3s ease;
            /* 平滑变换效果 */
        }

        .card-img-top:hover {
            transform: scale(1.1);
            /* 放大图片 */
        }

        .card-body {
            padding-left: 20px;
            /* 增加左侧内边距 */
        }

        #edit-price-link,
        #edit-goods-detail-form button {
            /* 添加自定义样式 */
        }

        .form-control {
            /* 调整输入框样式 */
        }

        .images-container {
            display: flex;
            /* 使图片水平排列 */
            justify-content: space-around;
            /* 在图片之间添加一些空间 */
            align-items: center;
            /* 垂直居中图片 */
        }

        .card-img-top {
            max-width: 45%;
            /* 设置最大宽度 */
            height: auto;
            /* 高度自动调整以保持图片比例 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* 图片阴影 */
            margin-bottom: 15px;
            /* 底部留白 */
        }
    </style>
</head>

<body>

    <div class="container my-4">
        <div class="card">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <div class="images-container">
                        <img src="./images/goodsimagesL/<?= $goods->goods_img_large ?>" class="card-img-top"
                            alt="<?= $goods->goods_name ?>">
                        <img src="./images/goodsimagesS/<?= $goods->goods_img_small ?>" class="card-img-top"
                            alt="<?= $goods->goods_name ?>">
                    </div>
                    <!-- 商品详情卡片 -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">商品詳細</h5>
                            <p class="card-text">
                                <?= htmlspecialchars($goods->goods_detail, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']->user_id == $goods->user_id): ?>
                                <button onclick="showEditGoodsDetailForm()" class="btn btn-link">商品詳細を編集する</button>
                                <form id="edit-goods-detail-form" action="goods.php?goods_id=<?= $goods_id ?>" method="post"
                                    style="display:none;">
                                    <input type="hidden" name="goods_id" value="<?= $goods->goods_id ?>">
                                    <textarea name="new_goods_detail"
                                        class="form-control mb-2"><?= $goods->goods_detail ?></textarea>
                                    <button type="submit" class="btn btn-primary">更新</button>
                                </form>

                            <?php endif; ?>
                        </div>
                    </div>


                </div>


                <div class="col-md-6">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= $goods->goods_name ?>
                        </h5>
                        <?php if ($isPurchased): ?>
                            <div class="sold-out-alert">
                                販売済み！！！
                            </div>
                        <?php else: ?>
                            <!-- 正常显示商品信息 -->



                        <?php endif; ?>
                        <p class="card-text">
                            <i class="fas fa-tag"></i> 価格:
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']->user_id == $goods->user_id): ?>
                                <a href="#" id="edit-price-link" onclick="showEditPriceForm()" class="btn btn-link">¥
                                    <?= number_format($goods->price) ?>
                                </a>
                            <form id="edit-price-form" action="goods.php?goods_id=<?= $goods_id ?>" method="post"
                                style="display:none;">
                                <input type="hidden" name="goods_id" value="<?= $goods->goods_id ?>">
                                <input type="text" name="new_price" value="<?= $goods->price ?>" class="form-control mb-2">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </form>

                        <?php else: ?>
                            ¥
                            <?= number_format($goods->price) ?>
                        <?php endif; ?>
                        </p>

                        <p class="card-text"><i class="fas fa-user"></i> 出品者:
                            <?= htmlspecialchars($seller->username) ?>
                        </p>
                        <!-- 其他商品信息 -->
                        <p class="card-text"><i class="fas fa-info-circle"></i> 商品状態:
                            <?= $status_map[$goods->status_id] ?? "未知" ?>
                        </p>
                      
                        <p class="card-text"><i class="fas fa-hourglass-half"></i> 発送までの日数:
                            <?= $goods->delivery_days ?>日
                        <p class="card-text"><i class="fas fa-credit-card"></i> 発送料金負担者:
                            <?= $payers[$goods->payer] ?? "未知" ?>
                        </p>
                        <p class="card-text">
                            <?= $goods->recommend ? "<span class='badge badge-success'>おすすめ</span>" : "" ?>
                        </p>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']->user_id != $goods->user_id): ?>
                            <form action="goods.php?goods_id=<?= $goods_id ?>" method="post">
                                <input type="hidden" name="favorite_goods_id" value="<?= $goods->goods_id ?>">
                                <button type="submit" name="add_to_favorite" class="btn btn-warning" <?= $isPurchased ? 'disabled' : '' ?>>お気に入りに追加</button>
                            </form>
                        <?php endif; ?>

                        <div class="comments-section">
                            <!-- 留言部分 -->
                            <div class="comment-section card my-3">
                                <div class="card-body">
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <form action="goods.php?goods_id=<?= $goods_id ?>" method="POST">
                                            <div class="form-group">
                                                <textarea name="comment_text" class="form-control" required
                                                    placeholder="コメント"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">コメントする</button>
                                        </form>
                                    <?php else: ?>
                                        <p><a href="login.php">LogIn</a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- 显示留言 -->
                            <div class="comments-display">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="media">
                                                <img src="./images/userIcons/<?= htmlspecialchars($comment->icon_image, ENT_QUOTES, 'UTF-8') ?>"
                                                    class="mr-3" alt="User Image" style="width: 50px; height: 50px;">
                                                <div class="media-body">
                                                    <h5 class="mt-0">
                                                        <?= htmlspecialchars($comment->username) ?>
                                                    </h5>
                                                    <?= htmlspecialchars($comment->comment_text) ?>
                                                </div>
                                            </div>

                                            <?php if (isset($_SESSION['user']) && $_SESSION['user']->user_id == $comment->user_id): ?>
                                                <form action="goods.php" method="post">
                                                    <input type="hidden" name="comment_id" value="<?= $comment->comment_id ?>">
                                                    <input type="hidden" name="goods_id" value="<?= $goods_id ?>">
                                                    <button type="submit" name="delete_comment"
                                                        class="btn btn-danger">削除</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (isset($_SESSION['user']) && $_SESSION['user']->user_id != $goods->user_id): ?>

                                <form action="purchase.php" method="POST" class="form-inline">
                                    <input type="hidden" name="goods_id" value="<?= $goods->goods_id ?>">
                                    <button type="submit" name="add" class="btn btn-primary" <?= $isPurchased ? 'disabled' : '' ?>>購入</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function showEditPriceForm() {
                document.getElementById('edit-price-link').style.display = 'none';
                document.getElementById('edit-price-form').style.display = 'block';
            }

            function showEditGoodsDetailForm() {
                document.getElementById('edit-goods-detail-form').style.display = 'block';
            }
        </script>

</body>

<?php include('footer.php'); ?>


</html>