<?php

// This class is to initiate the connection to the database 
// Usage: $connection = new DB_Connect();
// Return: a connection to the database
// Author: Liliana Quyen Tang

class DB_Connect {
    private $connection;

    public function connect() {
        require_once 'include_php/db_config.php';
      
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        return $this->connection;
    }
}
 
?>
