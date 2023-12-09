<?php
require_once 'DAO.php';

class Payment
{
    public int $payment_id;
    public string $payment_name;
}

class PaymentDAO
{
    public function get_all_payment()
    {
        try {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT * FROM [Payment]"; 
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Payment'); 
        } catch (PDOException $e) {
            
            error_log('Database error: ' . $e->getMessage());
            
            throw $e;
        }
    }
 
}
?>