<?php
require_once './vendor/autoload.php';


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
\Stripe\Stripe::setApiKey('sk_test_51O4edBCq2Pgxw0Lu6vPTuJ8ZCSpo6ndDPONibAZkaPMmu0PcIlAOBDsFLnKo1MDd0tCsPoK0rzzpaiXo7nbnsJtA002SgJFToU');

// 检查用户是否登录
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// 检查是否有 Stripe session ID
if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $goods_id = isset($session->metadata->goods_id) ? $session->metadata->goods_id : null;

        if ($goods_id === null) {
            throw new Exception('商品IDなし');
        }


        // 获取商品和用户信息
        $goodsDAO = new GoodsDAO();
        $goods = $goodsDAO->get_goods_by_id($goods_id);
        $userDAO = new UserDAO();
        $user = $userDAO->get_user_by_id($_SESSION['user']->user_id);

        // 现在你可以创建订单
        $orderDAO = new OrderDAO();
        $order = new Orders();
        $order->user_id = $user->user_id;
        $order->goods_id = $goods_id;
        $order->order_status_id = 1;
        $order->order_date = date('Y-m-d H:i:s');
        $order->payment_id = 1; // 根据实际情况设置

        // 存储订单信息
        $order_id = $orderDAO->create_order($order);

        if ($order_id) {
            sendThankYouEmail($user->email, $user->username, $goods->goods_name, './images/goodsimagesL/' . $goods->goods_img_large, $goods->price, $order_id);
            $goodsDAO->setStockToZero($goods_id);
        } else {
            echo '订单创建失败。';
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'Stripe API 错误: ' . $e->getMessage();
    }
} else {
    echo '无法获取 Stripe 会话信息。';
}

// 邮件发送函数
function sendThankYouEmail($to, $username, $goodsName, $goodsImg, $price,$order_id)
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
        $mail->Body    = $username . '様' . ',<br>ご購入ありがとうございました。' . '<br>' . '商品名'.$goodsName . '.<br><img src="cid:' . $cid . '" alt="' . $goodsName . '"><br>価格： ' . $price . '円'. "<br>オーダーID: ". $order_id ;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$order_id = $orderDAO->create_order($order);
// 发送邮件
// if ($order_id) {
//     $goodsDAO->setStockToZero($goods_id);
//     sendThankYouEmail($user->email, $user->username, $goods->goods_name, './images/goodsimagesL/' . $goods->goods_img_large, $goods->price, $order_id);
//     $orderDAO->removeDuplicateOrders();
// }

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