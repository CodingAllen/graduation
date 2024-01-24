<?php
require_once './helpers/FavoriteDAO.php';
require_once './helpers/UserDAO.php';

// セッションを開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログインしているユーザーの情報を取得
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_id = $user->user_id;
} else {
    // ログインしていない場合は適切な処理を行う（リダイレクトなど）
    // 例: header("Location: ログインページのURL");
    exit("ログインしていません");
}

// 商品IDが送信されてきたか確認
if (isset($_POST['goods_id'])) {
    $goods_id = $_POST['goods_id'];

    // FavoriteDAO インスタンスを作成
    $favoriteDAO = new FavoriteDAO();

    // お気に入りに追加する前に、すでに追加されているか確認
    if (!$favoriteDAO->favorite_exists($user_id, $goods_id)) {
        // お気に入りに追加する
        $favoriteDAO->insertFavorite($user_id, $goods_id);

        // 成功メッセージを表示
        echo json_encode(['status' => 'success', 'message' => 'お気に入りに追加しました']);
    } else {
        // すでにお気に入りに入っている場合はエラーメッセージを表示
        echo json_encode(['status' => 'error', 'message' => 'すでにお気に入りに入っています']);
    }
} else {
    // 商品IDが送信されていない場合のエラー処理
    echo json_encode(['status' => 'error', 'message' => 'エラーが発生しました']);
}
?>
