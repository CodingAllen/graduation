<?php
require_once './vendor/autoload.php';
require_once './helpers/GoodsDAO.php';

session_start();

// Stripe Secret Key
$stripe_secret_key = "sk_test_51O4edBCq2Pgxw0Lu6vPTuJ8ZCSpo6ndDPONibAZkaPMmu0PcIlAOBDsFLnKo1MDd0tCsPoK0rzzpaiXo7nbnsJtA002SgJFToU"; // 你的Stripe密钥
\Stripe\Stripe::setApiKey($stripe_secret_key);

// 从 GET 请求中获取商品 ID
$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : null;

if ($goods_id === null) {
    header("Location: error_page.php");
    exit;
}

$goodsDAO = new GoodsDAO();
$goods = $goodsDAO->get_goods_by_id($goods_id);

if ($goods) {
    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment",
        "success_url" => "http://localhost:3000/complete.php?session_id={CHECKOUT_SESSION_ID}",

        "cancel_url" => "http://localhost:3000/purchase.php",
        "line_items" => [[
            "quantity" => 1,
            "price_data" => [
                "currency" => "jpy",
                "unit_amount" => $goods->price, // 商品价格
                "product_data" => [
                    "name" => $goods->goods_name, // 商品名称
                    //"images" => ["https://forexample/images/goodsimagesL/" . $goods->goods_img_large]
                ],
            ],
        ]],
        'metadata' => ['goods_id' => $goods_id]
    ]);

    header("Location: " . $checkout_session->url);
    exit;
} else {
    header("Location: error_page.php");
    exit;
}
?>
