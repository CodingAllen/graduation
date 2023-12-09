<?php
require_once './helpers/UserDAO.php';
require_once './helpers/GoodsDAO.php';


// 确保用户已登录
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // 如果未登录，则重定向到登录页面
    exit();
}

$user_id = $_SESSION['user']->user_id; // 获取当前登录用户的ID
$goodsDAO = new GoodsDAO();
$userDAO = new UserDAO();


// 获取该用户售出的所有商品
$soldGoods = $goodsDAO->get_history_by_seller($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell History</title>
    <!-- 添加Bootstrap或其他CSS框架的链接，如果需要的话 -->
</head>

<body>
    <div class="card my-3">
        <div class="card-header">
            <h4>出品履歴</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($soldGoods)) : ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($soldGoods as $goods) : ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($goods->goods_name) ?> - ¥<?= htmlspecialchars($goods->price) ?>
                            <?php if ($goods->stock == 0) : ?>
                                - 販売済み
                                <?php if (isset($goods->order_date)) : ?>
                                    - 時間: <?= htmlspecialchars($goods->order_date) ?>
                                <?php endif; ?>
                                <?php
                                // 获取买家信息
                                if (isset($goods->buyer_id)) {
                                    $buyer = $userDAO->get_user_by_id($goods->buyer_id);
                                    if ($buyer) {
                                        echo "- 買手: " . htmlspecialchars($buyer->username);
                                    }
                                }
                                ?>
                            <?php else : ?>
                                - 出品中
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>

                </ul>
            <?php else : ?>
                <p class="text-center">販売記録はありません。</p>
            <?php endif; ?>
        </div>
    </div>


</body>

</html>