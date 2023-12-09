<?php
require_once 'DAO.php';
class User
{
    public int $user_id;
    public string $username = '';
    public string $full_name = '';
    public string $password_user;
    public string $icon_image;
    public string $email;
    public string $phone_number;
    public string $address;
    public string $zipcode;
}
class UserDAO
{
    //emailでユーザーを取得する
    //ログイン用
    public function get_user(string $email, string $password_user)
    {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [User] WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchObject('User');
        if ($user !== false) {
            if (password_verify($password_user, $user->password_user)) {
                return $user;
            }
        }
        return false;
    }
    //User insert 登録とプロフィル用
    public function insert(User $user)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO [User] (username,full_name,icon_image,email,phone_number,zipcode,address,password_user)
         VALUES (:username,:full_name,:icon_image,:email,:phone_number,:zipcode,:address,:password_user)";

        $stmt = $dbh->prepare($sql);

        $password_user = password_hash($user->password_user, PASSWORD_DEFAULT);


        $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindValue(':full_name', $user->full_name, PDO::PARAM_STR);
        $stmt->bindValue(':icon_image', $user->icon_image, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $user->phone_number, PDO::PARAM_STR);
        $stmt->bindValue(':zipcode', $user->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(':address', $user->address, PDO::PARAM_STR);
        $stmt->bindValue(':password_user', $password_user, PDO::PARAM_STR);
        $stmt->execute();
    }
    public function email_exists(string $email)
    {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM [User] WHERE email = :email";
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->fetch() !== false) {
            return true;
        } else {
            return false;
        }
    }
    public function get_user_by_id($user_id) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [User] WHERE user_id = :user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject('User');
    }
    public function updateUser(User $user) {
        $dbh = DAO::get_db_connect();

        // 准备SQL语句更新用户信息
        $sql = "UPDATE [User] SET 
                    username = :username, 
                    full_name = :full_name, 
                    icon_image = :icon_image, 
                    email = :email, 
                    phone_number = :phone_number, 
                    zipcode = :zipcode, 
                    address = :address, 
                    password_user = :password_user 
                WHERE user_id = :user_id";

        $stmt = $dbh->prepare($sql);

        // 绑定参数到语句
        $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindValue(':full_name', $user->full_name, PDO::PARAM_STR);
        $stmt->bindValue(':icon_image', $user->icon_image, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $user->phone_number, PDO::PARAM_STR);
        $stmt->bindValue(':zipcode', $user->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(':address', $user->address, PDO::PARAM_STR);
        $stmt->bindValue(':password_user', $user->password_user, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user->user_id, PDO::PARAM_INT);

        // 执行语句
        $stmt->execute();
    }
    public function get_user_by_email($email) {
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * FROM [User] WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject('User');
    }
    
}
