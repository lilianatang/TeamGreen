<?php

/*------------------------------------------------------------------------------ 
* This file is for all database operations involving the family calendar page. 
*
* Authors: Liliana & Komal 
*----------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------
* This class is used to establish a connection with the database and perform 
* specific queries based on the needs of the family calendar 
-------------------------------------------------------------------------------*/
class DB_Calendar {

	private $connection;

	// The construction is to initiate the connection in DB_Calendar class
	// Author: Liliana Quyen Tang
	function __construct() {
		require_once 'db_connect.php';

		$db = new DB_Connect();
		$this->connection = $db->connect();
	}

	/*-----------------------------------------------------------------------------
	* __destruct 
	* This method closes the database connection
	*
	* parameters & return: None
	*-----------------------------------------------------------------------------*/
	function __destruct() {

		$this->connection->close();
	}

	/*------------------------------------------------------------------------------------
	* checkFamilyId
	* This method takes the given family id and retrieves all the facilitator ids and names
	*
	* Parameters: A valid family id
	*
	* Return: None
	*
	* NOTE: This method echoes out the family information for access by the caller script
	*-------------------------------------------------------------------------------------*/
	public function checkFamilyId( $id ){
		
		/* Create query to retrieve facilitator information */
		$query = 
			"SELECT facilitator_id, CONCAT(first_name, \" \", last_name) as name
			 FROM facilitator
			 WHERE family_id = $id";
		
		
		/* Perform the query */
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		/* Retrieve and echo results */
		while ($row = $result->fetch_assoc()){
			echo $row["facilitator_id"] . "," . $row["name"] . ","; // The commas are added to make parsing simpler on the caller end
		}
	}
	
	/*---------------------------------------------------------------------------------
	* findEvents
	* This method takes a date (Ex. 2018-01-01) and fetches the slot information of any 
	* matching slots on that date
	*
	* Parameters: date -> a date in SQL date format 
	* 			  id -> a classroom_id
	*
	* Return: None
	* 
	* NOTE: This method echoes out the event information for access by the caller script
	*------------------------------------------------------------------------------------------*/
	function findEvents ($date, $id) {
		
		/* Create query to retrieve event information */
		$query = 
			"SELECT slot_id, classroom_id, time_start, time_end, facilitators_needed
			 FROM facilitation_times
			 WHERE date_scheduled = '$date' and classroom_id = '$id'";
		
		/* Perform the query */
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		/* Retrieve and echo results */
		while ($row = $result->fetch_assoc()){
			
			$slot_id = $row["slot_id"];
			
			/* Perform a sub-query to determine how many facilitators signed up for this slot_id already */
			 $query = 
			"SELECT count(*) as total 
			 FROM facilitating 
			 WHERE slot_id = $slot_id";
			
			/* Perform the query */
			$sub_result = $this->connection->query($query) or die ("An error occurred.");
			
			$sub_row = $sub_result->fetch_assoc();
			
			echo $slot_id . "," . $row["classroom_id"] . "," . $row["time_start"] . // The commas & tilde are added to make parsing simpler on the caller end
				"," . $row["time_end"] . "," . $row["facilitators_needed"] . "," . $sub_row["total"] . "~";  
		}
	}
	
	/*---------------------------------------------------------------------------------
	* bookFacilitation
	* This method takes booking information and updates the database 
	*
	* ISSUE IS IN THIS METHOD!!!!! - sometimes it says there's an error even though the database is updated successfully... 
	*
	* Parameters: facilitator_id -> The id of a facilitator in a family 
	* 			  notes -> Any additional booking notes provided
	*			  slot_id -> The slot id of a facilitation slot
	*
	* Return: None
	* 
	* NOTE: This method echoes out the success or failure messages for access by the caller script
	*------------------------------------------------------------------------------------------*/
	function bookFacilitation ($facilitator_id, $notes, $slot_id){

		/*Variable declaration*/
		$time_start = "";
		$time_end = "";
		$same_times = -1;
		$num_facilitators = "";
		$f_needed = "";
		
		/* Get number of people facilitating  */
		$query = "SELECT count(*) as num_facilitators from facilitating where slot_id = $slot_id";
		
		/* Run Query */
		$result = $this->connection->query($query) or die ("An error occurred 1.");
		
		/* Retrieve the data */
		while ($row = $result->fetch_assoc()){
			$num_facilitators = $row["num_facilitators"];
		}
		
		/* Get the time information from the slot already booked */
		$query = 
		" SELECT time_start, time_end, facilitators_needed
		  FROM facilitation_times 
		  WHERE slot_id = $slot_id";
		
		/* Run the first Query */
		$result = $this->connection->query($query) or die ("An error occurred 1.");
		
		while ($row = $result->fetch_assoc()){
			$time_start = $row["time_start"];
			$time_end = $row["time_end"];
			$f_needed = $row["facilitators_needed"];
		};
		
		//Make sure the slot isn't full
		if ($f_needed <= $num_facilitators){
				echo "This slot is full - you cannot sign up.";
				return false;
		}
		
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
		
		/* Retrieve the data */
		while ($row = $result->fetch_assoc()){
			$same_times = $row["same_times"];
		}
		
		/* 
			Check if the facilitator has already signed up for a slot at the same time.
			If they have, don't book them and don't update the database.
		*/
		if ($same_times == 0){
			
			/* Include the note in the booking if applicable */
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
			
			/* Update the database with the booking */
			$result = $this->connection->query($query) or die ("An error occurred. 3");
		
			echo "Sign up successful!";
			return;
		}
		else {
			
			echo "Sign up unsuccessful due to time conflicts.";
		}
	}
	
	/*---------------------------------------------------------------------------------
	* getClasses
	* This method retrieves all the classes and class ids from the database 
	*
	* Parameters & Return: None
	* 
	* NOTE: This method echoes out class information for access by the caller script
	*------------------------------------------------------------------------------------------*/
	function getClasses (){
		
		$query = 
			"SELECT classroom_id, class_color
			 FROM classroom";
		
		
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		while ($row = $result->fetch_assoc()){
			echo $row['classroom_id'] . " " . $row['class_color'] . ","; // The commas and spaces are added to make parsing simpler on the caller end
		};
		
	}
	
	/*------------------------------------------
	* getFamilies
	* This method gets the usernames of all families in the system 
	*
	* NOTE: This method echoes out family information for access by the caller script
	*-------------------------------------------*/
	function getFamilies() 
	{
		/* Create query to retrieve family information */
		$query = 
			"SELECT family_id, username 
			 FROM users, family
			 WHERE role_id = 2 and users.user_id = family.user_id";
		
		/* Perform the query */
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		/* Retrieve and echo results */
		while ($row = $result->fetch_assoc()){
			echo $row['family_id'] . " " . $row['username'] . ",";
		};
	}
	
	/*------------------------------------------
	* getTeachers
	* This method gets the usernames and names of all teachers in the system
	*
	* NOTE: This method echoes out family information for access by the caller script
	*-------------------------------------------*/
	function getTeachers() 
	{
		/* Create query to retrieve family information */
		$query = 
			"SELECT user_id, CONCAT(first_name, \" \", last_name) as name  
			 FROM teachers";
		
		/* Perform the query */
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		/* Retrieve and echo results */
		while ($row = $result->fetch_assoc()){
			echo $row['user_id'] . "~" . $row['name'] . ",";
		};
	}
	
	/*--------------------------------------------------------------------------
	* getBoardMembers
	* This method gets the usernames and ids of all board members in the system
	*
	* NOTE: This method echoes out family information for access by the caller script
	*--------------------------------------------------------------------------------*/
	function getBoardMembers()
	{
		/* Create query to retrieve family information */
		$query = 
			"SELECT user_id, username from users where role_id = 3";
		
		/* Perform the query */
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		/* Retrieve and echo results */
		while ($row = $result->fetch_assoc()){
			echo $row['user_id'] ." " . $row['username'] . ",";
		};
		
	}
	
	/*---------------------------------------------------------------------------------
	* getFacilitators
	* This method retrieves all the facilitators facilitating for a given slot_id
	*
	* Parameters & Return: None
	* 
	* NOTE: This method echoes out facilitator information for access by the caller script
	*------------------------------------------------------------------------------------------*/
	function getFacilitators($slot_id){
		
		$query = 
			"SELECT CONCAT(first_name, \" \", last_name) as name, notes
			 FROM facilitating, facilitator
			 WHERE 
				facilitating.facilitator_id = facilitator.facilitator_id and 
				slot_id = $slot_id";
		
		$result = $this->connection->query($query) or die ("An error occurred.");
		
		while ($row = $result->fetch_assoc()){
			if ($row['notes'] == null){
				echo $row['name'] . ",";
			}
			else {
				echo $row['name'] . " (note: " . $row['notes'] . "),"; // The commas are added to make parsing simpler on the caller end	
			}
			
		};
	}
	
}
	
	
?>
