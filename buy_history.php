<?php
require_once './helpers/UserDAO.php';
require_once './helpers/OrderDAO.php';
require_once './helpers/GoodsDAO.php';


// 确认用户已登录
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // 如果未登录，则重定向到登录页面
    exit();
}

$user_id = $_SESSION['user']->user_id; // 获取当前登录用户的ID

$goodsDAO = new GoodsDAO();
$orderDAO = new OrderDAO();
$sellHistory = [];

// 获取该用户作为卖家的所有订单
$orders = $orderDAO->get_orders_by_buyer($user_id); // 此方法需要在 OrderDAO 中实现
foreach ($orders as $order) {
    $goods = $goodsDAO->get_goods_by_id($order->goods_id);
    array_push($sellHistory, $goods);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy History</title>
    <!-- Bootstrap CSS 可能需要链接 -->
</head>

<body>
    <div class="card my-3">
        <div class="card-header">
            <h4>買い物履歴</h4>
        </div>
        <div class="card-body">
            <?php if (count($sellHistory) > 0) : ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($sellHistory as $item) : ?>
                        <a href="goods.php?goods_id=<?= htmlspecialchars($item->goods_id) ?>">
                            <?= htmlspecialchars($item->goods_name) ?> - ¥<?= htmlspecialchars($item->price) ?>
                        </a>

                    <?php endforeach; ?>

                </ul>
            <?php else : ?>
                <p class="text-center">購入記録はありません。</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>