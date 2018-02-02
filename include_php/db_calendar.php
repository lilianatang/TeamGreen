

<?php

/* This file is used to update the modal window with applicable facilitators. */


class DB_Calendar {

	private $connection;

	// the construction is to initiate the connection in DB_Calendar class
	// Author: Liliana Quyen Tang
	function __construct() {
		require_once 'db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();
	}

	function __destruct() {

		$this->connection->close();
	}

	/*-------------------------------------
	* checkFamilyId
	* This method takes the given family id and echos all the facilitator ids and names
	*-------------------------------------*/
	public function checkFamilyId( $id ){
		
		$query = 
			"SELECT facilitator_id, CONCAT(first_name, \" \", last_name) as name
			 FROM facilitator
			 WHERE family_id = $id";
		
		
		$result = $this->connection->query($query) or die ("ERROR");
		
		while ($row = $result->fetch_assoc()){
			echo $row["facilitator_id"] . "," . $row["name"] . ",";
		}
	}
}
	// Gets the id passed from jQuery segment and retrieves matching facilitator names
	$calendar = new DB_Calendar();
	if ($_POST['input']){
		$id = $_POST['input'];
		$calendar->checkFamilyId($id);
		
	}
	else {
		echo "issue";
	} 
	
?>
