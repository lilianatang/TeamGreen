<?php


class DB_Calendar {

	private $connection;

	// the construction is to initiate the connection in DB_Calendar class
	// Author: Liliana Quyen Tang
	function __construct() {
		require_once 'include_php/db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();
	}

	function __destruct() {

	}





	//add your extra functions here 


}

?>
