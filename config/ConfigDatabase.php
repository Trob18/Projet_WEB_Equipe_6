<?php

namespace app\Config;

use PDO;
use PDOException;

class ConfigDatabase {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $options;

    public function __construct($host = 'localhost', $dbname = 'projet_web', $username = 'root', $password = '', $options = []) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options + [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    }

    public function getConnectionString() {
        return "mysql:host={$this->host};dbname={$this->dbname}";
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getOptions() {
        return $this->options;
    }

    public function connect() {
        try {
            $pdo = new PDO(
                $this->getConnectionString(),
                $this->getUsername(),
                $this->getPassword(),
                $this->getOptions()
            );
            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException("Erreur de connexion : " . $e->getMessage());
        }
    }
}
?>
