

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
		
		
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		while ($row = $result->fetch_assoc()){
			echo $row["facilitator_id"] . "," . $row["name"] . ",";
		}
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
		
		
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		while ($row = $result->fetch_assoc()){
			
			$slot_id = $row["slot_id"];
			
			/* Determine how many facilitators signed up for this slot_id already */
			 $query = 
			"SELECT count(*) as total 
			 FROM facilitating 
			 WHERE slot_id = $slot_id";
			
			$sub_result = $this->connection->query($query) or die ("An error occurred.");
			
			$sub_row = $sub_result->fetch_assoc();
			
			echo $slot_id . "," . $row["classroom_id"] . "," . $row["time_start"] .
				"," . $row["time_end"] . "," . $row["facilitators_needed"] . "," . $sub_row["total"] . "~";
		}
	}
	
	function bookFacilitation ($facilitator_id, $notes, $slot_id){
		
		/*Variable declaration*/
		$time_start = "";
		$time_end = "";
		$same_times = -1;
		
		/* Get the time information from the slot already booked */
		$query = 
		" SELECT time_start, time_end 
		  FROM facilitation_times 
		  WHERE slot_id = $slot_id";
		
		/* Run the first Query */
		$result = $this->connection->query($query) or die ("An error occurred 1.");
		
		while ($row = $result->fetch_assoc()){
			$time_start = $row["time_start"];
			$time_end = $row["time_end"];
		};
		
		/* Check if the facilitator is already booked at the same time */
		$query = 
		" SELECT count(*) as same_times
		  FROM facilitating, facilitation_times
		  WHERE 
			facilitating.slot_id = facilitation_times.slot_id and
			facilitator_id = $facilitator_id and 
			time_start < '$time_end' and 
			time_end > '$time_start'"; 
			
		/* Run the second Query */
		$result = $this->connection->query($query) or die ("An error occurred. 2");
		
		while ($row = $result->fetch_assoc()){
			$same_times = $row["same_times"];
		}
		
		if ($same_times == 0){
			
			if ($notes != null){
				$query = 
				"INSERT INTO facilitating (slot_id, facilitator_id, notes)
				 VALUES ($slot_id, $facilitator_id, '$notes')
				";
			}
			else {
				$query = 
				"INSERT INTO facilitating (slot_id, facilitator_id)
				 VALUES ($slot_id, $facilitator_id)
				";
			}
			
			$result = $this->connection->query($query) or die ("An error occurred. 3");
		
			echo "Sign up successful!";
			return;
		}
		else {
			echo "Sign up unsuccessful due to time conflicts.";
		}
	}
	
	function getClasses (){
		
		$query = 
			"SELECT classroom_id, class_color
			 FROM classroom";
		
		
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		while ($row = $result->fetch_assoc()){
			echo $row['classroom_id'] . " " . $row['class_color'] . ",";
		};
		
	}
	
}
	
	
?>
