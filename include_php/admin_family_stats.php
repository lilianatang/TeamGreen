<?php
class Family_Stats
{
	private $connection;
	// The construction is to initiate the connection in DB_Calendar class
	// Author: Liliana Quyen Tang
	function __construct() {
		require_once 'db_connect.php';
		$db = new DB_Connect();
		$this->connection = $db->connect();
	}
	/**
	* __destruct 
	* This method closes the database connection
	*
	* parameters & return: None
	*/
	function __destruct() {
		$this->connection->close();
	}
	
	/**
	* getFamilyDropDown gets all the usernames for user to populate a 
	* dropdown box 
	*
	* parameters, return: none
	*/
	function getFamilyDropDown()
	{
		$query = "Select username, user_id
				  from users
				  where role_id = 2";
		
		$results = $this->connection->query($query) or die ("Error getting family names");
		
		while($row = $results->fetch_assoc())
		{
			echo $row["username"] . ',';
		}
	}
	/**
	* getFamilyID gets a family id based on a corresponding user id
	*
	* parameters: userID to look for
	* return: none
	*/
	function getFamilyID($userID)
	{
		$query = "Select family_id
				  from family
				  where family.user_id = $userID";
				  
		$results = $this->connection->query($query) or die ("Error get facilitators");
		
		while($row = $results->fetch_assoc())
		{
			echo $row["family_id"] . ',';
		}
	}
	
	/**
	*
	* getFamilyFacilitators gets all the facilitators that belong to a 
	* specific family id
	*
	* parameters: family_id
	* return: none
	*/
	function getFamilyFacilitators($famID)
	{
		$query = "Select CONCAT (first_name,last_name) as Name
				  from facilitator
				  where family_id = $famID";
		
		$results = $this->connection->query($query) or die ("Error");
		
		while($row = $results->fetch_assoc())
		{
			echo $row["Name"] . ',';
		}
	}
	
	/**
	*
	* getFamilyStudents gets all the students that belong to a 
	* family id
	*
	* parameters: family_id
	* return: none
	*/
	function getFamilyStudents($famID)
	{
			$query = "Select CONCAT(first_name, last_name) as Name
					  from students
					  where students.family_id = $famID";
			
			$results = $this->connection->query($query) or die ("Error getting students.");
			
			while($row = $results->fetch_assoc())
			{
				echo $row["Name"] . ',';
			}
		
	}
	
	/**
	*
	* getFamilyHistory gets all the data from the history table
	* that pertains to a family
	*
	* parameters: family_id, year to check in, month to check in
	* return: none
	*/
	function getFamilyHistory($famID,$year,$month)
	{
		$query = "select start_date, end_date, required_hours, completed_hours
				 from history
				 where year(history.start_date) = $year 
				 and month(history.start_date) = $month
				 and history.family_id = $famID";
				 
		$results = $this->connection->query($query) or die ("Error getting students.");
			
		while($row = $results->fetch_assoc())
		{
			echo $row["start_date"] . ',' . $row["end_date"] . ',' . $row["required_hours"] . ',' . $row["completed_hours"] . '~' ;
		}
	}
}

/* Testing to make sure that core functions work
$new = new Family_Stats(); - creation works
$new->getFamilyDropDown(); - data pulled
$new->getFamilyID('11'); - data pulled
$new->getFamilyFacilitators('5'); - data pulled
$new->getFamilyHistory('1','2018','02'); - data pulled
*/

?>