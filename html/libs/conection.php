<?php
require_once '../config/config.php';
class Conection{

    static public function connect(){
        try {
            $conn = new PDO("mysql:host=".constant('HOST').";dbname=".constant('DB'),constant('USER'),constant('PASSWORD'),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            return $conn;
        }
        catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }

    }
}
