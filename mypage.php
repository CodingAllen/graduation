<?php

require_once './helpers/UserDAO.php';
$userDao = new UserDAO();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header('Location: login.php');
    exit;
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $userDao = new UserDAO();
    $user = $_SESSION['user'];

    $uploadDir = 'images/userIcons/'; // 指定上传目录
    $fileName = $_FILES['avatar']['name'];
    $filePath = $uploadDir . $fileName;

    // 文件类型和大小的检查可以在这里进行
    // 例如，检查文件大小不超过一个特定的限制，文件类型为图片等

    // 将文件移动到指定目录
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
        // 更新数据库中的头像信息
        $user->icon_image = $fileName;

        // 更新数据库
        // 这里你可能需要添加一个方法到UserDAO类来更新用户信息
        $userDao->updateUser($user);
        // 具体实现取决于你的UserDAO类的设计

        // 更新会话中的用户信息
        $_SESSION['user'] = $user;

        // 重定向回mypage
        header('Location: mypage.php');
        exit;
    } else {
        echo "文件上传失败。";
    }
}

// 处理用户名更新
if (isset($_POST['update_username'])) {
    $newUsername = trim($_POST['new_username']);
    // 对新用户名进行验证和处理
    $user->username = $newUsername;
    $userDao->updateUser($user);
    $_SESSION['user'] = $user;

    header('Location: mypage.php');
    exit;
}
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
    <?php include 'header.php'; ?>

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
        <div class="row">
            <div class="col-lg-12">
                <!-- 用户欢迎信息 -->

            </div>
        </div>

        <!-- 售出履历和购入履历 -->
        <div class="row">
            <div class="col-lg-6">
                <?php include "sell_history.php"; ?>
            </div>
            <div class="col-lg-6">
                <?php include "buy_history.php"; ?>
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