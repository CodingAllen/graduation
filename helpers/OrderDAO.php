<?php
require_once 'DAO.php';
class Orders{
    public int $order_id;
    public int $user_id;
    public int $goods_id;
    public int $order_status_id;
    public string $order_date;
    public int $payment_id;
}
class Order_Status{
    public int $order_status_id;
    public string $orderstatus_name;
}
class OrderDAO{
    public function get_orders_by_buyer($user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [Orders] WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Orders');
    }
    // 获取订单详情
    public function get_order($order_id)
    {
        $orderDetails = new stdClass(); // 创建一个标准类对象用于存储订单详情

        try {
            $dbh = DAO::get_db_connect();
            
            // 获取订单基本信息
            $stmt = $dbh->prepare("SELECT * FROM [Orders] WHERE order_id = :order_id");
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            $order = $stmt->fetchObject('Order');
            
            if (!$order) {
                return null; // 如果没有找到订单，则返回null
            }

            // 获取用户名称
            $stmt = $dbh->prepare("SELECT full_name FROM [User] WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $order->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderDetails->user_name = $user ? $user['full_name'] : 'Unknown';

            // 获取联系地址
            $contactDAO = new ContactDAO();
            $orderDetails->contact_address = $contactDAO->get_address_by_user_id($order->contact_id);

            // 获取商品名称
            $goodsDAO = new GoodsDAO();
            $goods = $goodsDAO->get_goods_by_id($order->goods_id);
            $orderDetails->goods_name = $goods ? $goods->goods_name : 'Unknown';

            // 获取订单状态名称
            $orderStatusDAO = new OrderStatusDAO();
            $orderStatus = $orderStatusDAO->get_all_orderstatuses();
            foreach ($orderStatus as $status) {
                if ($status->order_status_id === $order->order_status_id) {
                    $orderDetails->order_status_name = $status->orderstatus_name;
                    break;
                }
            }

            // 获取支付方式名称
            $paymentDAO = new PaymentDAO();
            $paymentMethods = $paymentDAO->get_all_payment();
            foreach ($paymentMethods as $payment) {
                if ($payment->payment_id === $order->payment_id) {
                    $orderDetails->payment_name = $payment->payment_name;
                    break;
                }
            }

            // 添加其他需要的订单信息
            $orderDetails->order_date = $order->order_date;

            return $orderDetails;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
    public function create_order(Orders $order)
    {
        try {
            $dbh = DAO::get_db_connect();
    
            // 准备 SQL 插入语句
            $stmt = $dbh->prepare("INSERT INTO [Orders] (user_id, goods_id, order_status_id, order_date, payment_id) VALUES (:user_id, :goods_id, :order_status_id, :order_date, :payment_id)");
    
            // 绑定参数
            $stmt->bindParam(':user_id', $order->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':goods_id', $order->goods_id, PDO::PARAM_INT);
            $stmt->bindParam(':order_status_id', $order->order_status_id, PDO::PARAM_INT);
            $stmt->bindParam(':order_date', $order->order_date, PDO::PARAM_STR);
            $stmt->bindParam(':payment_id', $order->payment_id, PDO::PARAM_INT);
    
            // 执行插入操作
            $stmt->execute();
    
            // 获取插入的订单的ID
            $order->order_id = $dbh->lastInsertId();
    
            return $order->order_id;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            throw $e;
        }
    }
}
?>