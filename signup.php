<?php
require_once './helpers/UserDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number1 = $_POST['phone_number1'];
    $phone_number2 = $_POST['phone_number2'];
    $phone_number3 = $_POST['phone_number3'];
    $zipcode = $_POST['zipcode'];
    $address = $_POST['address'];
    $password_user = $_POST['password_user'];
    $password_user1 = $_POST['password_user1'];

    $userDAO = new UserDAO();
    $errs = [];
    //メールアドレスの形式チェック
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errs['email'] = 'メールアドレスの形式が正しくありません。';
    } elseif ($userDAO->email_exists($email)) {
        $errs['email'] = 'このメールアドレスはすでに登録されています。';
    }
    //パスワードの文字数をチェック
    if (!preg_match('/\A.{4,}\z/', $password_user)) {
        $errs['password_user'] = 'パスワードは４文字以上で入力してください。';
    }
    //パスワードの一致チェック
    elseif ($password_user !== $password_user1) {
        $errs['password_user'] = 'パスワードが一致しません。';
    }
    //名前の入力チェック
    if ($full_name === '') {
        $errs['full_name'] = 'お名前を入力してください。';
    }
    //郵便番号の形式チェック
    if (!preg_match("/^\d{3}-\d{4}$/", $zipcode)) {
        $errs['zipcode'] = '郵便番号は3桁ー4桁で入力してください。';
    }
    //住所の入力チェック
    if ($address === '') {
        $errs['address'] = '住所を入力してください。';
    }
    //電話番号の桁数チェック
    if (
        !preg_match('/\A(\d{2,5})?\z/', $phone_number1) || !preg_match('/\A(\d{1,4})?\z/', $phone_number2)
        || !preg_match('/\A(\d{4})?\z/', $phone_number3)
    ) {
        $errs['phone_number'] = '電話番号は半角英数字２～５桁、1～4桁で入力してください';
    }

    $user = new User();

    if (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] === UPLOAD_ERR_OK) {

        $target_dir = "images/userIcons/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }


        $file_tmp_path = $_FILES['icon_image']['tmp_name'];
        $file_name = $_FILES['icon_image']['name'];
        $file_size = $_FILES['icon_image']['size'];
        $file_type = $_FILES['icon_image']['type'];
        $error = $_FILES['icon_image']['error'];


        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            $errs['icon_image'] = 'アイコン画像はJPEG、PNG、またはGIF形式である必要があります。';
        } else {

            $new_file_name = uniqid('user_', true) . strrchr($file_name, '.');


            $target_file_path = $target_dir . $new_file_name;
            if (move_uploaded_file($file_tmp_path, $target_file_path)) {

                $user->icon_image = $new_file_name;
            } else {
                $errs['icon_image'] = 'ファイルのアップロードに失敗しました。';
            }
        }
    } else {

        $user->icon_image = 'default_icon.png';
    }

    if (empty($errs)) {

        $user->username = $username;
        $user->full_name = $full_name;

        $user->email = $email;
        $user->phone_number = '';
        if ($phone_number1 !== '' && $phone_number2 !== '' && $phone_number3 !== '') {
            $user->phone_number = "{$phone_number1}-{$phone_number2}-{$phone_number3}";
        }
        $user->zipcode = $zipcode;
        $user->address = $address;
        $user->password_user = $password_user;

        $userDAO->insert($user);

        header('Location: signupEnd.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
        }

        .registration-container {
            max-width: 500px;
            /* Set a max-width for the form card */
            margin: 30px auto;
            /* Center the card with automatic margin */
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Add some shadow for depth */
        }

        .card {
            background-color: #FFEE99;
        }

        .card-header {
            background-color: #f7f7f7;
            border-bottom: none;
            color: #333;
            text-align: center;
        }

        .card-body {
            background-color: white;
            padding: 20px;
        }

        .form-group label {
            color: #555;
            font-weight: bold;
        }

        .form-control {
            background-color: #ADD8E6;
            border: 1px solid #90cee6;
        }

        .form-control:focus {
            background-color: #ADD8E6;
            border: 1px solid #559EDF;
            box-shadow: none;
        }

        .error {
            color: red;
            font-size: 0.8em;
        }

        .btn-custom {
            background-color: #FFEE99;
            color: black;
        }

        .btn-custom:hover {
            background-color: #e2d890;
        }

        .btn btn-outline-secondary {
            background-color: #FFEE99;
            color: red;
        }
    </style>
</head>


<body>
    <?php include 'header.php'; ?>
    <div class="container registration-container">
        <div class="card">
            <div class="card-header">
                <h1>会員登録</h1>
            </div>
            <div class="card-body">
                <P>
                    以下の項目を入力し、登録ボタンをクリックしてください(*は必須)
                </P>
                <form action="" method="post" enctype="multipart/form-data"> <!-- Add enctype attribute for file upload -->

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">ユーザー名＊</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                        <?php if (isset($errs['username'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['username']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email"> メールアドレス＊</label>
                        <input type="email" class="form-control" name="email" id="email" required autofocus>
                        <?php if (isset($errs['email'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['email']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password_user">パスワード(4文字以上)＊</label>

                        <input type="password" class="form-control" name="password_user" id="password_user" pattern=".{4,}" required>
                        <?php if (isset($errs['password_user'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['password_user']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_user1">パスワード(再入力)＊</label>

                        <input type="password" class="form-control" name="password_user1" id="password_user1">
                        <?php if (isset($errs['password_user'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['password_user']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Member Name -->
                    <div class="form-group">
                        <label for="full_name">お名前＊</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                        <?php if (isset($errs['full_name'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['full_name']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Zipcode -->
                    <div class="form-group">
                        <label for="zipcode">郵便番号＊</label>

                        <input type="text" class="form-control" name="zipcode" id="zipcode" pattern="\d{3}-\d{4}" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="fillAddress">住所検索</button>
                        </div>
                        <?php if (isset($errs['zipcode'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['zipcode']; ?></p>
                        <?php endif; ?>
                    </div>



                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">住所＊</label>
                        <input type="text" class="address" name="address" id="address" required>
                        <?php if (isset($errs['address'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['address']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Phone Number -->
                    <tr>
                        <td>電話番号</td>
                        <td>
                            <input type="phone_number" name="phone_number1" size="4"> -
                            <input type="phone_number" name="phone_number2" size="4"> -
                            <input type="phone_number" name="phone_number3" size="4">
                            <?php if (isset($errs['phone_number'])) : ?>
                                <p style="color: red; display:inline;"><?php echo $errs['phone_number']; ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Icon Image -->
                    <div class="form-group">
                        <label for="icon_image">アイコン画像</label>
                        <input type="file" class="form-control-file" name="icon_image" accept="image/*">
                        <!-- PHP Error for Icon Image here -->
                        <?php if (isset($errs['icon_image'])) : ?>
                            <p style="color: red; display:inline;"><?php echo $errs['icon_image']; ?></p>
                        <?php endif; ?>
                    </div>

                    <input type="submit" class="btn btn-custom btn-block" value="登録する">
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('fillAddress').addEventListener('click', function() {
            var zipcode = document.getElementById('zipcode').value;
            if (zipcode) {
                fetch('https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + zipcode)
                    .then(response => response.json())
                    .then(data => {
                        if (data.results) {
                            var result = data.results[0];
                            var address = result.address1 + result.address2 + result.address3;
                            document.getElementById('address').value = address;
                        } else {
                            alert('該当する郵便番号が見つかりませんでした。');
                        }
                    });
            } else {
                alert('郵便番号を入力してください。');
            }
        });
    </script>
    <?php include('footer.php'); ?>
</body>

</html>