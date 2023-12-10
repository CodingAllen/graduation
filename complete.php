<?php
require_once './helpers/GoodsDAO.php';
require_once './helpers/UserDAO.php';
require_once './helpers/ContactDAO.php';
require_once './helpers/OrderDAO.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// PHPMailer的读み込みパス
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';

session_start();

// 检查用户是否登录
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: purchase.php');
    exit;
}

// 获取用户信息
$userDAO = new UserDAO();
$user = $userDAO->get_user_by_id($_SESSION['user']->user_id);

// 从 POST 请求获取商品 ID
$goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : null;

// 获取商品信息
$goodsDAO = new GoodsDAO();
$goods = $goodsDAO->get_goods_by_id($goods_id);

// 创建新的订单对象
$orderDAO = new OrderDAO();
$order = new Orders();
$order->user_id = $_SESSION['user']->user_id;
$order->goods_id = $goods_id;
$order->order_status_id = 1; // 假设 1 是订单状态 "已创建"
$order->order_date = date('Y-m-d H:i:s');
$order->payment_id = 1; // 假设支付方式 ID 为 1

// 存储订单信息
$order_id = $orderDAO->create_order($order);

// 邮件发送函数
function sendThankYouEmail($to, $username, $goodsName, $goodsImg, $price)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'utf-8';

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = '22jn0119@jec.ac.jp'; // SMTP username
        $mail->Password   = '52n4Q9Ncf2tV'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('22jn0119@jec.ac.jp', '日電フリ事務局');
        $mail->addAddress($to); // Add a recipient

        // Add embedded image
        $cid = 'goodsimage'; // Content ID
        $mail->AddEmbeddedImage($goodsImg, $cid);

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Thank you for your purchase!';
        $mail->Body    = $username . '様' . ',<br>ご購入ありがとうございました。' . '<br>' . $goodsName . '.<br><img src="cid:' . $cid . '" alt="' . $goodsName . '"><br>価格： ' . $price . '円';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$order_id = $orderDAO->create_order($order);
// 发送邮件
if ($order_id) {
    $goodsDAO->setStockToZero($goods_id);
    sendThankYouEmail($user->email, $user->username, $goods->goods_name, './images/goodsimagesL/' . $goods->goods_img_large, $goods->price);
    $orderDAO->removeDuplicateOrders();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>購入完了</title>
    <link rel="stylesheet" type="text/css" href="./css/custom-style.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container mt-4 text-center">
        <h2>ご購入ありがとうございました。</h2>
        <div>
            <a href="mypage.php" class="btn btn-primary">マイページへ</a>
            <a href="index.php" class="btn btn-secondary">トップページへ</a>
        </div>
    </div>

    <?php include('footer.php'); ?>
    <!-- JS 脚本 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>