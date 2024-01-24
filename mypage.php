<?php
require_once './helpers/UserDAO.php';
require_once './helpers/GoodsDAO.php';
require_once './helpers/OrderDAO.php';

$userDao = new UserDAO();
$goodsDAO = new GoodsDAO();
$orderDAO = new OrderDAO();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

// アップロード
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $uploadDir = 'images/userIcons/';
    $fileName = $_FILES['avatar']['name'];
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
        $user->icon_image = $fileName;
        $userDao->updateUser($user);
        $_SESSION['user'] = $user;
        header('Location: mypage.php');
        exit;
    } else {
        echo "文件上传失败。";
    }
}

// 名前更新
if (isset($_POST['update_username'])) {
    $newUsername = trim($_POST['new_username']);
    $user->username = $newUsername;
    $userDao->updateUser($user);
    $_SESSION['user'] = $user;
    header('Location: mypage.php');
    exit;
}



// 購入履歴の削除
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_orders'])) {

    foreach ($_POST['delete_orders'] as $order_id) {

        $order_id = (int)$order_id;
        $orderDAO->delete_order($order_id);
    }


    header('Location: mypage.php');
    exit();
}

// 出品履歴の取得
function getSoldGoods($user_id)
{
    global $goodsDAO;
    return $goodsDAO->get_history_by_seller($user_id);
}

// こうにゅう履歴の取得
function getPurchasedGoods($user_id)
{
    global $goodsDAO, $orderDAO;
    $purchasedGoods = [];
    $orders = $orderDAO->get_orders_by_buyer($user_id);
    foreach ($orders as $order) {
        $goods = $goodsDAO->get_goods_by_id($order->goods_id);
        $purchasedGoods[] = [
            'order_id' => $order->order_id,
            'goods' => $goods
        ];
    }
    return $purchasedGoods;
}



$soldGoods = getSoldGoods($user->user_id);
$purchasedGoods = getPurchasedGoods($user->user_id);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>My Page</title>
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .vnav {
            letter-spacing: 2px;
        }

        .marginb {
            margin-bottom: 45px;
        }

        .navbar-brand {
            font-family: 'Pacifico', cursive;
        }

        .header1 {
            background-image: url("https://w.wallhaven.cc/full/we/wallhaven-wem6mp.png");
            /*color: #ffffff;*/
            background-position: center;
            /* 图片居中 */
            background-size: cover;
            /* 覆盖整个区域并保持宽高比 */
            background-repeat: no-repeat;
            /* 不重复图片 */
            image-height: 40%;
            /* 调整背景图片的高度 */
        }



        .container-fluid {
            padding-top: 100px;
            padding-bottom: 100px;
        }

        .h3v {
            font-size: 50px;
            letter-spacing: 7px;
            color: #f8f8f8;
        }

        .h3v2 {
            font-size: 25px;
            letter-spacing: 4px;
            color: #e7e7e7;
        }

        .h3v3 {
            font-size: 15px;
            letter-spacing: 3px;
        }

        .h4v {
            font-size: 12px;
            letter-spacing: 3px;
            line-height: 1.8;
        }

        .pimg {
            width: 250px;
            height: auto;
        }

        .fav {
            padding-left: 15px;
            padding-right: 15px;
            font-size: 22px;
            color: #525252;
            text-decoration: none;
        }

        .footer {
            padding: 30px 0 30px 0;
            background-color: #f8f8f8;
            border-top: solid 1px #e7e7e7;
        }

        a.cap2 {
            text-decoration: none;
        }

        .fa:hover {
            opacity: 0.7;
        }

        .thumbnail:hover {
            box-shadow: 1px 1px 9px grey;
        }
    </style>

</head>

<body>


    <!-- header -->
    <div class="container-fluid header1 text-center marginb">
        <h3 class="h3v marginb">こんにちは！</h3>
        <form action="mypage.php" method="post" enctype="multipart/form-data">
            <label for="avatar-upload" class="custom-file-upload">
                <img class="img-responsive rounded-circle center-block pimg marginb" src="images/userIcons/<?= htmlspecialchars($user->icon_image ?? 'default_icon.png', ENT_QUOTES, 'UTF-8') ?>" alt="User Icon" style="cursor: pointer;">
            </label>
            <input id="avatar-upload" type="file" name="avatar" style="display: none;" onchange="this.form.submit()">
        </form>

        <h3 class="h3v2" id="username-display" onclick="editUsername()">
            <?= htmlspecialchars($user->username ?? 'Unknown User', ENT_QUOTES, 'UTF-8') ?>
        </h3>
        <form id="username-form" style="display: none;" action="mypage.php" method="post">
            <input type="text" name="new_username" value="<?= htmlspecialchars($user->username ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="update_username" value="1">
            <input type="submit" value="Update">
        </form>
    </div>
    <div class="container my-4">
         <!-- 通知页面跳转按钮 -->
    <div class="text-center mb-4">
        <a href="notifications.php" class="btn btn-primary">お知らせ履歴</a>
    </div>
        <!-- 售出履历部分 -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card my-3">
                    <div class="card-header">
                        <h4>出品履歴</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($soldGoods)) : ?>
                            <ul class="list-group">
                                <?php foreach ($soldGoods as $goods) : ?>
                                    <li class="list-group-item d-flex align-items-center">
                                        <img src="./images/goodsimagesS/<?= htmlspecialchars($goods->goods_img_small) ?>" alt="<?= htmlspecialchars($goods->goods_name) ?>" class="mr-3" style="width: 50px; height: auto;">
                                        <div>
                                            <a href="goods.php?goods_id=<?= htmlspecialchars($goods->goods_id) ?>">
                                                <?= htmlspecialchars($goods->goods_name) ?> - ¥<?= htmlspecialchars($goods->price) ?>
                                            </a>
                                            <?php if ($goods->stock == 0) : ?>
                                                - 販売済み
                                                <?php if (isset($goods->order_date)) : ?>
                                                    - 時間: <?= htmlspecialchars($goods->order_date) ?>
                                                <?php endif; ?>

                                            <?php else : ?>
                                                - 出品中
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-center">販売記録はありません。</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 购入履历部分 -->
            <div class="col-lg-6">
                <div class="card my-3">
                    <div class="card-header">
                        <h4>買い物履歴</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="mypage.php">
                            <?php if (!empty($purchasedGoods)) : ?>
                                <ul class="list-group">
                                    <?php foreach ($purchasedGoods as $item) : ?>
                                        <li class="list-group-item d-flex align-items-center">
                                            <input type="checkbox" name="delete_orders[]" value="<?= htmlspecialchars($item['order_id']) ?>" class="mr-3">
                                            <img src="./images/goodsimagesS/<?= htmlspecialchars($item['goods']->goods_img_small) ?>" alt="<?= htmlspecialchars($item['goods']->goods_name) ?>" class="mr-3" style="width: 50px; height: auto;">
                                            <a href="goods.php?goods_id=<?= htmlspecialchars($item['goods']->goods_id) ?>">
                                                <?= htmlspecialchars($item['goods']->goods_name) ?> - ¥<?= htmlspecialchars($item['goods']->price) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="submit" class="btn btn-danger mt-3">削除</button>
                            <?php else : ?>
                                <p class="text-center">購入記録はありません。</p>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                // Add smooth scrolling to all links
                $("a").on('click', function(event) {

                    // Make sure this.hash has a value before overriding default behavior
                    if (this.hash !== "") {
                        // Prevent default anchor click behavior
                        event.preventDefault();

                        // Store hash
                        var hash = this.hash;

                        // Using jQuery's animate() method to add smooth page scroll
                        // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                        $('html, body').animate({
                            scrollTop: $(hash).offset().top
                        }, 800, function() {

                            // Add hash (#) to URL when done scrolling (default click behavior)
                            window.location.hash = hash;
                        });
                    } // End if
                });
            });
        </script>

        <script>
            function editUsername() {
                // 隐藏显示用户名的元素
                document.getElementById('username-display').style.display = 'none';
                // 显示编辑表单
                document.getElementById('username-form').style.display = 'block';
            }
        </script>
</body>
<?php include('footer.php'); ?>

</html>