<?php
require_once 'DAO.php';

class Status
{
    public int $status_id;
    public string $status_name;
}

class StatusDAO
{
    // 获取所有状态信息
    public function get_all_statuses()
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [Status]";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Status');
    }
    // 更多与状态相关的方法...
}
?>
