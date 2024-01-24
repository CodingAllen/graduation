<?php
require_once 'DAO.php';
class Comment{
    public int $comment_id;
    public int $user_id;
    public int $goods_id;
    public string $comment_text;
}
class CommentDAO{
    public function get_comments_by_goods_id(int $goods_id) {
        $dbh = DAO::get_db_connect(); 

       
        $sql = "SELECT c.comment_id, c.user_id, c.goods_id, c.comment_text, u.username, u.icon_image 
                FROM Comment c 
                JOIN [User] u ON c.user_id = u.user_id 
                WHERE c.goods_id = :goods_id";
                
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        $stmt->execute();

        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function add_comment(Comment $comment) {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO Comment (user_id, goods_id, comment_text) VALUES (:user_id, :goods_id, :comment_text)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':user_id', $comment->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':goods_id', $comment->goods_id, PDO::PARAM_INT);
        $stmt->bindValue(':comment_text', $comment->comment_text, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function delete_comment($comment_id, $user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "DELETE FROM [Comment] WHERE comment_id = :comment_id AND user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

   public function getCommentById($comment_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM Comment WHERE comment_id = :comment_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function get_comments_by_goods_id2($goods_id) {
        $dbh = DAO::get_db_connect();

        // 假设商品表名为 goods，用户表名为 users
        $sql = "SELECT c.comment_id, c.user_id, c.goods_id, c.comment_text, g.goods_name, u.full_name ,u.username
                FROM comment c 
                JOIN goods g ON c.goods_id = g.goods_id 
                JOIN [User] u ON c.user_id = u.user_id 
                WHERE c.goods_id = :goods_id AND comm_read = 0";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function markCommentAsRead($comment_id) {
        $dbh = DAO::get_db_connect();
        $sql = "UPDATE Comment SET comm_read = 1 WHERE comment_id = :comment_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function make_recommend(){
        $dbh = DAO::get_db_connect();
    
        // 先重置所有商品的推荐状态
        $dbh->exec("UPDATE Goods SET recommend = 0");
    
        // 获取每个商品的评论数（排除商品发布者的评论）
        $sql = "SELECT TOP 10 g.goods_id, COUNT(*) as comment_count 
                FROM Comment c 
                JOIN Goods g ON c.goods_id = g.goods_id 
                WHERE c.user_id != g.user_id 
                GROUP BY g.goods_id 
                ORDER BY comment_count DESC";
    
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $topGoods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // 遍历这些商品并更新其推荐状态
        foreach ($topGoods as $goods) {
            $sqlUpdate = "UPDATE Goods SET recommend = 1 WHERE goods_id = :goods_id";
            $stmtUpdate = $dbh->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':goods_id', $goods['goods_id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
    }
    
    
    
}

?>