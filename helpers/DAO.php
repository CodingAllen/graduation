<?php
require_once 'config.php';

class DAO
{
    private static $dbh;

    public static function get_db_connect()
    {
        try {
            if (self::$dbh === null) {
                $dsn = DSN . ";TrustServerCertificate=true";
                self::$dbh = new PDO($dsn, DB_USER, DB_PASSWORD);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
        return self::$dbh;
    }
}
