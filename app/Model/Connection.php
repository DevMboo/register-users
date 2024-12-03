<?php

namespace App\Model;

use PDO;
use PDOException;

class Connection {

    protected $db;

    public function __construct() 
    {
        if ($this->db === null) {
            $this->db = $this->db();
        }
    }

    private function db()
    {
        try {
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $dbname = getenv('DB_DATABASE');
            $user = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            $connection = new PDO($dsn, $user, $password);
            
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $connection;

        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
