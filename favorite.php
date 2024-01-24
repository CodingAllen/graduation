<?php
require_once './helpers/FavoriteDAO.php';
require_once './helpers/UserDAO.php';

session_start();

// ログインユーザーのIDを取得
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ログインユーザーのIDを取得
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // ログインしていない場合はエラーメッセージを出力して終了
    if ($user_id === null) {
        echo json_encode(['message' => 'お気に入りの追加に失敗しました。']);
        exit;
    }

    // goods_id は適切な方法で取得してください
    $goods_id = isset($_POST['goods_id']) ? $_POST['goods_id'] : null;

    // FavoriteDAOのインスタンス化
    $favoriteDAO = new FavoriteDAO();

    // お気に入りの商品を追加
    if ($favoriteDAO->insertFavorite($user_id, $goods_id)) {
        // 成功時のレスポンス
        echo json_encode(['message' => 'お気に入りに追加しました!!']);
    } else {
        // エラー時のレスポンス
        echo json_encode(['message' => 'お気に入りの追加に失敗しました。']);
    }
} else {
    // POST リクエスト以外はエラーメッセージを返す（適宜修正が必要かもしれません）
    echo json_encode(['message' => '不正なリクエストです。']);
}

$favoriteDAO = new FavoriteDAO();
$favorites = $favoriteDAO->get_favorite_by_user_id($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入りページ</title>
</head>

<body>
    <?php
    include "header.php";
    
    // お気に入りの商品を取得

    // お気に入りの商品を表示する
    if (empty($favorites)) {
        echo '<p>お気に入りに商品はありません。</p>';
    } else {
        foreach ($favorites as $favorite) {
            echo '<div style="border: 1px solid #ddd; margin-bottom: 10px; padding: 10px;">';
            // 商品情報の表示
            echo '<img src="' . $favorite->goods_img_large . '" alt="' . $favorite->goods_name . '" style="max-width: 100px; max-height: 100px;">';
            echo '<a href="product_detail.php?goods_id=' . $favorite->goods_id . '">' . $favorite->goods_name . '</a>';
            echo '<p>単価: ' . $favorite->price . '円</p>';
            echo '</div>';
        }
    }
    ?>
</body>

</html>
