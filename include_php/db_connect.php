<?php

// This class is to initiate the connection to the database 
// Usage: $connection = new DB_Connect();
// Return: a connection to the database
// Author: Liliana Quyen Tang

class DB_Connect {
    private $connection;

    public function connect() {
		// changed this in order to connect to DB on Joe's computer, need to generalize it
        require_once 'db_config.php';
      
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        return $this->connection;
    }
}
 
?>
