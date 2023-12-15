<?php
require_once 'DAO.php';
class Favorite{
    public int $user_id;
    public int $goods_id;
}
class FavoriteDAO{
    public function add_to_favorite(int $user_id, int $goods_id) {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "INSERT INTO [Favorite] (user_id, goods_id) VALUES (:user_id, :goods_id)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in add_to_favorite: " . $e->getMessage());
            return false;
        }
    }
    
    // 获取用户的所有收藏
    public function get_favorites_by_user_id(int $user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT g.* FROM [Goods] g JOIN [Favorite] f ON g.goods_id = f.goods_id WHERE f.user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Goods');
    }
    public function is_favorite_exists(int $user_id, int $goods_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT COUNT(*) FROM [Favorite] WHERE user_id = :user_id AND goods_id = :goods_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    public function mark_as_purchased(int $user_id, int $goods_id) {
        $dbh = DAO::get_db_connect();
        $sql = "UPDATE [Favorite] SET is_purchased = 1 WHERE user_id = :user_id AND goods_id = :goods_id";;
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}

?>