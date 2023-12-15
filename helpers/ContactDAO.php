<?php
require_once 'DAO.php';

class Contact
{
    public int $contact_id;
    public int $user_id; // 外键，指向User表的主键
    public string $address; // 联系地址
}

class ContactDAO
{
    // 根据user_id获取用户的地址
    public function get_addresses_by_user_id($user_id) {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT address FROM [User] WHERE user_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // 修改这里以返回关联数组
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            // 这里可以加入更多的错误处理逻辑
        }
    }
   

}
