<?php
require_once 'DAO.php';

class Admin{
    public $admin_id;
    public string $admin_name;
    public string $password_manager;
}

class AdminDAO{ 
    public function get_admin(string $admin_name, string $password_manager) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM Admin WHERE admin_name = :admin_name";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':admin_name', $admin_name, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetchObject('Admin');

        if ($admin !== false) {
            // 直接比较字符串密码
            if ($admin->password_manager === $password_manager) {
                return $admin;
            }
        }
        return false;
    }

    public function getUsers($search = '') {
        $dbh = DAO::get_db_connect();
        // 初始化基本的 SQL 查询
        $sql = "SELECT * FROM [User]";

        if (!empty($search)) {
            // 根据 $search 参数的类型构建不同的 SQL 查询
            if (is_numeric($search)) {
                // 如果搜索条件是数字，按 ID 搜索
                $sql .= " WHERE user_id = :search";
            } else {
                // 如果搜索条件是文本，按用户名搜索
                $sql .= " WHERE username LIKE :search";
                $search = "%$search%";
            }
        }
        $stmt = $dbh->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getGoods($search = '') {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [Goods]";

        if (!empty($search)) {
            if (is_numeric($search)) {
                $sql .= " WHERE goods_id = :search";
            } else {
                $sql .= " WHERE goods_name LIKE :search";
                $search = "%$search%";
            }
        }
        $stmt = $dbh->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function sendPersonalNotification($adminId, $userId, $content) {
        $dbh = DAO::get_db_connect();
        $sql = "INSERT INTO Notification (admin_id, user_id, content, date) VALUES (:adminId, :userId, :content, GETDATE())";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':adminId', $adminId, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function sendAllNotification($content) {
        $dbh = DAO::get_db_connect();
        // 假设 allnf_id 是自动增长的，不需要在插入时指定
        $sql = "INSERT INTO [22jn01_J].[dbo].[allnf] (allnf) VALUES (:content)";
    
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    
        $stmt->execute();
    }
    public function deleteUser($userId) {
        $dbh = DAO::get_db_connect();
        $sql = "DELETE FROM [User] WHERE user_id = :userId";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteGood($goodId) {
        $dbh = DAO::get_db_connect();
        $sql = "DELETE FROM [Goods] WHERE goods_id = :goodId";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goodId', $goodId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// 简单的路由
if (isset($_GET['action']) && $_GET['action'] == 'getUsers') {
    $dao = new AdminDAO();
    $users = $dao->getUsers();
    echo json_encode($users);
}

    
?>