<?php

/* This file will be used to update the facilitation days available on the index screen */


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

	/*-------------------
	* This method takes a date (Ex. 2018-01-01) and fetches the slot information of any 
	* matching slots on that date
	----------------------*/
	function findEvents ($date, $id) {
		
		$query = 
			"SELECT slot_id, classroom_id, time_start, time_end, facilitators_needed
			 FROM facilitation_times
			 WHERE date_scheduled = '$date' and classroom_id = '$id'";
		
		
		$result = $this->connection->query($query) or die ("ERROR");
		
		while ($row = $result->fetch_assoc()){
			
			echo $row["slot_id"] . "," . $row["classroom_id"] . "," . $row["time_start"] .
				"," . $row["time_end"] . "," . $row["facilitators_needed"] . "~";
		}
	}
	
}
	$calendar = new DB_Calendar();
	if ($_POST['days']){
		$days = $_POST['days'];
		$id = $_POST['classid'];
		
		$days_split = explode(" ", $days);
		
		foreach ($days_split as $day) {
			
			$calendar->findEvents($day, $id);
		}
	}
	else {
		echo "issue";
}
	
	
?>
