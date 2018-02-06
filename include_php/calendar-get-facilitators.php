<?php

/*------------------------------------------------------------------------------------------
* This script retrieves the facilitators of a given family from the database (used by calendar-main.js)
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

$calendar = new DB_Calendar();

// Gets the id passed from jQuery segment
if ($_POST['input']){
	
	// Retrieve ID and run query 
	$id = $_POST['input'];
	$calendar->checkFamilyId($id);
	
}
else {
	echo "issue";
} 

?>