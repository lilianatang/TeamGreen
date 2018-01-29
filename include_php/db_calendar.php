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

		msqli_close($connection);
	}

	function checkFamilyId( $id ){
		
		$query = "SELECT * FROM family WHERE family_id = $id";
		$result = mysqli_query($connection, $query);
			or die ("ERROR");
		
		while ($row = msqli_fetch_array($result) {
			echo $row['family_id'];
		}
	}


}

$calendar = new DB_Calender();

$id = $_POST['input-id'];
echo $id;

//checkFamilyId($id);


?>
