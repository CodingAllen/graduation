<?php
require_once 'DAO.php';

class OrderStatus
{
    public int $order_status_id;
    public string $orderstatus_name;
}

class OrderStatusDAO
{
    public function get_all_orderstatuses()
    {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT * FROM [Order_Status]"; 
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'OrderStatus'); 
        } catch (PDOException $e) {
            
            error_log('Database error: ' . $e->getMessage());
            
            throw $e;
        }
    }
 
}
?>