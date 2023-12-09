<?php
require_once './helpers/UserDAO.php';
require_once './helpers/FavoriteDAO.php';
require_once './helpers/GoodsDAO.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // 未登录用户重定向到登录页面
    exit();
}

if (isset($_SESSION['user']) && isset($_SESSION['user']->user_id)) {
    $favoriteDAO = new FavoriteDAO();
    $favorites = $favoriteDAO->get_favorites_by_user_id($_SESSION['user']->user_id);
    // ... 其余代码
}
// 处理删除请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_goods_id'])) {
    $goodsIdToDelete = $_POST['delete_goods_id'];
    // 调用删除方法，传入商品ID和用户ID
    if ($favoriteDAO->delete_favorite($_SESSION['user']->user_id, $goodsIdToDelete)) {
        // 重定向回同一页面
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        error_log("Error in deleting favorite: user_id=" . $_SESSION['user']->user_id . ", goods_id=" . $goodsIdToDelete);
        // 可以在这里处理错误，例如显示错误消息
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- 引入 Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- 自定义样式 -->
    <style>
        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
        }

        .card-body p {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="container my-4">
        <h2 class="mb-4">お気に入り商品</h2>
        <div class="row">
            <?php foreach ($favorites as $goods) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img class="card-img-top" src="./images/goodsimagesS/<?= $goods->goods_img_small ?>" alt="<?= $goods->goods_name ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $goods->goods_name ?></h5>
                            <p class="card-text">価格: ¥<?= number_format($goods->price) ?></p>
                            <form action="purchase.php" method="POST">
                                <input type="hidden" name="goods_id" value="<?= $goods->goods_id ?>">
                                <button type="submit" class="btn btn-primary">購入</button>
                            </form>
                            <!-- 删除表单 -->
                            <form action="" method="POST">
                                <input type="hidden" name="delete_goods_id" value="<?= $goods->goods_id ?>">
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 引入 Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>