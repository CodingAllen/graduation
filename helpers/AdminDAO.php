<?php
require_once 'DAO.php';

class Admin{
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
}
    // 获取管理员信息的函数
    //public function get_admin(string $admin_name, string $password_manager) {
       // $dbh = DAO::get_db_connect();
        //$sql = "SELECT * FROM Admin WHERE admin_name = :admin_name";
       // $stmt = $dbh->prepare($sql);
      //  $stmt->bindValue(':admin_name', $admin_name, PDO::PARAM_STR);
       // $stmt->execute();
       // $admin = $stmt->fetchObject('Admin');
       // if ($admin !== false) {
         //   if (password_verify($password_manager, $admin->password_manager)) {
           //     return $admin;
           // }
       // }
       // return false;
   //// }
//}
?>