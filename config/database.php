<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct(){
        $host = '146.235.32.190';
        $db= 'bd_repcampos';
        $user = 'campos-user';
        $pass = 'Cps*oyt123';
        $charset = 'utf8mb4';

    $dns = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dns,$user,$pass,$options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(),(int)$e->getCode());
        }
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}