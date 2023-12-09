<?php
require_once './helpers/GoodsDAO.php';
require_once './helpers/UserDAO.php';
require_once './helpers/ContactDAO.php';
require_once './helpers/OrderDAO.php';


session_start();

// 检查用户是否登录
if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) {
    header("Location: login.php");
    exit;
}

// 从 POST 请求获取商品 ID
$goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : null;

// 验证商品 ID
if ($goods_id === null) {
    header("Location: error_page.php");
    exit;
}

// 获取商品信息
$goodsDAO = new GoodsDAO();
$goods = $goodsDAO->get_goods_by_id($goods_id);
if (!$goods) {
    header("Location: error_page.php");
    exit;
}

// 获取用户地址
$contactDAO = new ContactDAO();
$addresses = $contactDAO->get_addresses_by_user_id($_SESSION['user']->user_id);

// 创建新的订单对象
$orderDAO = new OrderDAO();

// 处理购入确认
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_purchase'])) {
    $order = new Orders();
    $order->user_id = $_SESSION['user']->user_id;
    $order->goods_id = $goods_id;
    $order->order_status_id = 1; // 假设 1 是订单状态 "已创建"
    $order->order_date = date('Y-m-d H:i:s');
    $order->payment_id = 1; // 假设支付方式 ID 为 1

    // 存储订单信息
    $order_id = $orderDAO->create_order($order);

    if ($order_id) {
        
        header("Location: complete.php?order_id=" . $order_id);
        $goodsDAO->setStockToZero($goods_id);
        exit;
    } else {
        header("Location: error_page.php");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>購入ページ</title>
    <link rel="stylesheet" type="text/css" href="./css/custom-style.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">購入確認ページ</h2>
        <div class="row">
            <div class="col-md-8">
                <h3>商品情報</h3>
                <?php if ($goods): ?>
                    <img src="./images/goodsimagesL/<?= $goods->goods_img_large ?>" alt="<?= $goods->goods_name ?>" class="img-fluid">
                    <p>商品名: <?= $goods->goods_name ?></p>
                    <p>価格: ¥<?= $goods->price ?></p>
                <?php else: ?>
                    <p>商品が見つかりません。</p>
                <?php endif; ?>

                <h3>配送先住所</h3>
                <!-- 配送地址 -->
                <?php if (!empty($addresses)): ?>
                    <form action="complete.php?goods_id=<?= htmlspecialchars($goods_id) ?>" method="post">
                        <?php foreach ($addresses as $address): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="address_id" id="address" value="<?= htmlspecialchars($address['address']) ?>" checked>
                                <label class="form-check-label" for="address">
                                    <?= htmlspecialchars($address['address']) ?>
                                </label>
                            </div>
                            <?php break; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="goods_id" value="<?= $goods_id ?>">
                        <input type="hidden" name="confirm_purchase" value="1">
                        <button type="submit" class="btn btn-primary mt-3">購入確認</button>
                    </form>
                <?php else: ?>
                    <p>住所が見つかりません。</p>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <h3>購入情報</h3>
                <?php if ($goods): ?>
                    <p>商品価格: ¥<?= $goods->price ?></p>
                    <p>送料: ¥0</p>
                    <p>合計: ¥<?= $goods->price ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include('footer.php'); ?>
</body>
</html>