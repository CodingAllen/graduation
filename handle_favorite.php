<?php
// handle_favorite.php

require_once './helpers/FavoriteDAO.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ログインユーザーのIDを取得
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // ログインしていない場合はエラーメッセージを出力して終了
    if ($userId === null) {
        echo json_encode(['message' => 'お気に入りの追加に失敗しました。']);
        exit;
    }

    $goodsId = $_POST['goods_id'];

    // デバッグ用にログに出力
    error_log("Goods ID: $goodsId, User ID: $userId");

    // TODO: FavoriteDAOを使用してデータベースにお気に入り情報を追加
    $favoriteDAO = new FavoriteDAO();
    $success = $favoriteDAO->addFavorite($userId, $goodsId);

    if ($success) {
        echo json_encode(['message' => 'お気に入りに追加しました!!']);
    } else {
        echo json_encode(['message' => 'お気に入りの追加に失敗しました。']);
    }
}
?>
