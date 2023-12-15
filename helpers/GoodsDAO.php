<?php
require_once 'DAO.php';

class Goods
{
    public int $goods_id;
    public int $user_id;
    public int $category_id;
    public int $status_id;
    public string $goods_img_large;
    public string $goods_img_small;
    public int $price;
    public int $stock;
    public int $payer;
    public string $origin;
    public int $delivery_days;
    public string $goods_name;
    public ?int $recommend = 0;
    public string $goods_detail;
    public ?string $order_date = null;
    public ?int $buyer_id = null; 

   
}
class Category {
    public int $category_id;
    public string $category_name;
}
class GoodsDAO
{
    public function get_all_goods($page = 1, $perPage = 8, $category_id = null) {
        $dbh = DAO::get_db_connect();
        $offset = ($page - 1) * $perPage;
    
        $sql = "SELECT * FROM [Goods] WHERE stock = 1";
        if ($category_id !== null) {
            $sql .= " AND category_id = :category_id";
        }
        $sql .= " ORDER BY recommend DESC, goods_id ASC OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY";
    
        $stmt = $dbh->prepare($sql);
    
        if ($category_id !== null) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Goods');
    }
    

    // 根据商品ID获取商品信息
    public function get_goods_by_id(int $goods_id)
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [Goods] WHERE goods_id = :goods_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        $stmt->execute();
        $goods= $stmt->fetchObject('Goods');
        return $goods;
    }
    // 更多与商品相关的方法...
    public function add_goods(Goods $goods) {
        $dbh = DAO::get_db_connect();
        $sql = "INSERT INTO [Goods] (user_id, category_id, status_id, goods_img_large, goods_img_small, price, stock, payer, origin, delivery_days, goods_name, goods_detail) 
                VALUES (:user_id, :category_id, :status_id, :goods_img_large, :goods_img_small, :price, :stock, :payer, :origin, :delivery_days, :goods_name, :goods_detail)";
        $stmt = $dbh->prepare($sql);
    
        // 绑定参数到声明
        $stmt->bindValue(':user_id', $goods->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $goods->category_id, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $goods->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':goods_img_large', $goods->goods_img_large, PDO::PARAM_STR);
        $stmt->bindValue(':goods_img_small', $goods->goods_img_small, PDO::PARAM_STR);
        $stmt->bindValue(':price', $goods->price, PDO::PARAM_INT);
        $stmt->bindValue(':stock', $goods->stock, PDO::PARAM_INT);
        $stmt->bindValue(':payer', $goods->payer, PDO::PARAM_INT);
        $stmt->bindValue(':origin', $goods->origin, PDO::PARAM_STR);
        $stmt->bindValue(':delivery_days', $goods->delivery_days, PDO::PARAM_INT);
        $stmt->bindValue(':goods_name', $goods->goods_name, PDO::PARAM_STR);
        $stmt->bindValue(':goods_detail', $goods->goods_detail, PDO::PARAM_STR);
    
        // 执行声明
        return $stmt->execute();
    }
    
     // 获取推荐商品
     public function get_recommended_goods() {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [Goods] WHERE recommend = 1 AND stock = 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Goods');
    }
    public function get_total_goods_count($category_id = null) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT COUNT(*) FROM [Goods] WHERE stock = 1";
        if ($category_id !== null) {
            $sql .= " AND category_id = :category_id";
        }
    
        $stmt = $dbh->prepare($sql);
    
        if ($category_id !== null) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }
    
        $stmt->execute();
    
        return $stmt->fetchColumn(); // 返回第一列的值，即商品总数
    }
   public function get_goods_by_keyword(String $keyword){
    $dbh = DAO::get_db_connect();
    $sql = "SELECT * FROM [Goods] WHERE goods_name LIKE :keyword  ORDER BY recommend DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    $stmt->execute();

    $data = [];
    while ($row = $stmt->fetchObject('Goods')) {
        $data[] = $row;
    }

    return $data;
   }
   public function get_all_categories() {
    $dbh = DAO::get_db_connect();
    $sql = "SELECT * FROM [Category]";
    $stmt = $dbh->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Category');
}
public function update_goods_price(int $goods_id, int $new_price) {
    $dbh = DAO::get_db_connect();
    $sql = "UPDATE [Goods] SET price = :new_price WHERE goods_id = :goods_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':new_price', $new_price, PDO::PARAM_INT);
    $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
    return $stmt->execute();
}

public function update_goods_detail(int $goods_id, string $new_detail) {
    $dbh = DAO::get_db_connect();
    $sql = "UPDATE [Goods] SET goods_detail = :new_detail WHERE goods_id = :goods_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':new_detail', $new_detail, PDO::PARAM_STR);
    $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
    return $stmt->execute();
}
public function get_history_by_seller(int $user_id) {
    $dbh = DAO::get_db_connect();
    $sql = "SELECT g.*, o.order_date, o.user_id as buyer_id
            FROM [Goods] g 
            LEFT JOIN [Orders] o ON g.goods_id = o.goods_id AND g.stock = 0
            WHERE g.user_id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Goods');
}


public function get_goods_and_order_info(int $user_id){
        $dbh = DAO::get_db_connect();
        $sql = "SELECT g.*, o.order_date, o.user_id as buyer_id 
                FROM [Goods] g 
                LEFT JOIN [Orders] o ON g.goods_id = o.goods_id 
                WHERE g.user_id = :user_id ";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Goods');
    }
    public function setStockToZero($goods_id) {
        $dbh = DAO::get_db_connect();
        $sql = "UPDATE [Goods] SET stock = 0 WHERE goods_id = :goods_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
public function is_goods_purchased($goods_id) {
    $dbh = DAO::get_db_connect();
    $sql = "SELECT COUNT(*) FROM [Goods] WHERE goods_id = :goods_id AND stock = 0";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':goods_id', $goods_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}
public function delete_goods(int $goods_id): bool {
    try {
        $dbh = DAO::get_db_connect(); // 假设这是获取数据库连接的方法

        // 准备删除语句
        $stmt = $dbh->prepare("DELETE FROM Goods WHERE goods_id = :goods_id");

        // 绑定参数
        $stmt->bindParam(':goods_id', $goods_id, PDO::PARAM_INT);

        // 执行删除操作
        $stmt->execute();

        // 检查是否有行被删除
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        // 错误处理
        error_log('Database error: ' . $e->getMessage());
        return false;
    }
}
}
?>
