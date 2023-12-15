 
<?php
require_once './helpers/UserDAO.php';
require_once './helpers/AdminDAO.php';
//require_once 'vendor/autoload.php';
//use Google\Service\Oauth2 as Google_Service_Oauth2;

$email = '';
$errs = [];
session_start();

/*$client = new Google_Client();
$client->setClientId('323378874098-4gn8rjjapqnsbeljqlbtrqfoaqi4ackr.apps.googleusercontent.com'); // 替换为您的Google Client ID
$client->setClientSecret('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'); // 替换为您的Google Client Secret
$client->setRedirectUri('http://localhost:3000'); // 替换为您的Google Redirect URI
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();

/// Googlecallback
if (isset($_GET['code'])) {
    echo 'Inside Google OAuth block';
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    //var_dump($token);
    $client->setAccessToken($token);

    try {
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        var_dump($google_account_info);
    } catch (Exception $e) {
        error_log($e->getMessage());
       
    }
    $email = $google_account_info->email;

    $userDAO = new UserDAO();
    $user = $userDAO->get_user_by_email($email);
    var_dump($user); 

    if ($user) {
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        var_dump($_SESSION); 
        header('Location: index.php');
        exit;
    } else {
        $errs[] = '他のログイン方でログインください、或いはGmailでアカウントを作ってください';
    }
}


var_dump($_SESSION);*/
if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password_user = $_POST['password_user'];

    // 先尝试从 AdminDAO 获取管理员
    $adminDAO = new AdminDAO();
    $admin = $adminDAO->get_admin($email, $password_user);

    if ($admin !== false) {
        // 如果是管理员，则设置 session 并跳转到 Admin.php
        session_regenerate_id(true);
        $_SESSION['admin'] = $admin;
        header('Location: Admin.php');
        exit;
    }

    // 针对普通用户的邮箱格式验证
    if ($email === '') {
        $errs[] = 'メールアドレスを入力してください。';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errs[] = 'メールアドレスの形式に誤りがあります。';
    }

    if ($password_user === '') {
        $errs[] = 'パスワードを入力してください。';
    }

    if (empty($errs)) {
        $userDAO = new UserDAO();
        $user = $userDAO->get_user($email, $password_user);

        if ($user !== false) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $errs[] = 'メールアドレスまたはパスワードに誤りがあります。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #FFEE99;
            /* Set the background color */
        }

        #wrapper {
            display: flex;
            align-items: center;
            /* Vertical alignment */
            justify-content: center;
            /* Horizontal alignment */
            height: 100%;
        }

        .login-container {
            width: 100%;
            /* Full width */
            max-width: 400px;
            /* Maximum width */
            padding: 20px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Shadow for style */
            background: white;
            /* Background for the container */
            border-radius: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #FFEE99;
            /* Button color */
            color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            opacity: 0.9;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        #wrapper {
            background-image: url('https://w.wallhaven.cc/full/gp/wallhaven-gpmlw3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<?php include "header.php"; ?>

<body>
    <div id="wrapper">
        <div class="login-container">
            <!-- Display errors here -->
            <?php foreach ($errs as $err) : ?>
                <div class="error"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>

            <!-- 登录表单 -->
        <form action="login.php" method="post" novalidate>
            <div>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div>
            <label for="password_user">パスワード</label>
            <input type="password" id="password_user" name="password_user" required>
        </div>
        <div>
            <input type="submit" value="ログイン">
        </div>
        </form>

            


            <!-- Link to registration page -->
            <div style="text-align: center; margin-top: 20px;">
                <a href="signup.php">新規会員登録はこちら</a>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>