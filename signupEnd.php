<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signupEnd.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3pV0qg7VpSzjwbvmVjPtGFwJVCNk0U5rNYvuhCw+R/c89eOGCkmtT1cwtT" crossorigin="anonymous">
    <title>会員ようこそ</title>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="alert custom-alert" role="alert">
                    <h4 class="alert-heading">会員登録完了！</h4>
                    <p>会員の登録をしました。下記のボタンからログインページへ移動して、買い物を続けてください。</p>
                    <hr>
                    <p class="mb-0"><a class="btn custom-btn" href="login.php" role="button">ログインする</a></p>
                </div>
            </div>
        </div>
    </div>


    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-Ltrj3ptHC+7hXkfQU+xPTBkypIJEuZsYYbxlYYpcj+u6IV8iZf+2k0Kz0rE5PliS" crossorigin="anonymous"></script>
-->
    <!-- Option 2: Separate Popper and Bootstrap JS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" integrity="sha384-Q6E+vgIrzP6Fx4f2SQ8lTB4laKov4Tzk6sE2r3J+Fm25stFzNarcFVo5F2h5V6O0" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shcSTO4FuXJcZ1bjj7Z5eVUgvf1VHfFIMyjHi" crossorigin="anonymous"></script>
    <?php include('footer.php'); ?>
</body>

</html>