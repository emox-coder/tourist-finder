<?php
namespace Infrastructure\Database;

class PDOConnection {
    private $host;
    private $dbname;
    private $user;
    private $pass;

    public function __construct() {
        $config = require __DIR__ . "/../../config/config.php";

        $this->host = $config['db']['host'];
        $this->dbname = $config['db']['dbname'];
        $this->user = $config['db']['user'];
        $this->pass = $config['db']['pass'];
    }

    public function connect() {
        try {
            $pdo = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->user,
                $this->pass
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;

        } catch (\PDOException $e) {
            die("PDO Connection Error: " . $e->getMessage());
        }
    }
}
