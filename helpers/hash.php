<?php
//パスワードをハッシュ化するための
require_once 'DAO.php';

$dao = new DAO(); 
$dbh = $dao->get_db_connect(); 

$users = $dbh->query("SELECT user_id, password_user FROM [22jn01_J].[dbo].[User]");

$update_stmt = $dbh->prepare("UPDATE [22jn01_J].[dbo].[User] SET password_hash = :password_hash WHERE user_id = :user_id");

foreach ($users as $user) {
    $password_hash = password_hash($user['password_user'], PASSWORD_DEFAULT);
    $update_stmt->bindParam(':password_hash', $password_hash);
    $update_stmt->bindParam(':user_id', $user['user_id']);
    $update_stmt->execute();
}
