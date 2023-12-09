<?php
require_once 'DAO.php';

class Message{
   public int $notification_id;
   public int $user_id;
   public int $admin_id;
   public string $content;
   public string $goods_id;
   public string $allnf;
   public string $allnf_id;
}
class MessageDAO {
    public static function getContentByUserId($user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM Notification WHERE user_id = :user_id AND notif_read = 0";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getGoodsIdsByUserId($user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT goods_id FROM goods WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    public static function markAsRead($notification_id) {
        $dbh = DAO::get_db_connect();
        $sql = "UPDATE Notification SET notif_read = 1 WHERE notification_id = :notification_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':notification_id', $notification_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    
        public static function getAllNotifications($user_id) {
            $dbh = DAO::get_db_connect();
            // 更新 SQL 查询以包含 allnfstatus 表的左连接
            $sql = "SELECT allnf.* FROM allnf 
                    LEFT JOIN allnfstatus ON allnf.allnf_id = allnfstatus.allnf_id AND allnfstatus.user_id = :user_id
                    WHERE allnfstatus.allnf_id IS NULL"; // 选择未读的通知
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    
    
    
    public static function markGlobalNotificationAsRead($user_id, $allnf_id) {
        $dbh = DAO::get_db_connect();
        $sql = "INSERT INTO allnfstatus (user_id, allnf_id) VALUES (:user_id, :allnf_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':allnf_id', $allnf_id, PDO::PARAM_INT);
        $stmt->execute();
    }
        

    }
    

