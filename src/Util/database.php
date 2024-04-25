<?php

namespace Util;

class Database {
    public static function getConnection() {
        $config = require_once(__DIR__ . '/../../config/config.php');

        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
        $user = $config['db_user'];
        $password = $config['db_password'];

        try {
            $connection = new \PDO($dsn, $user, $password);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (\PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
            die();
        }
    }
}