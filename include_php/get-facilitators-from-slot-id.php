<?php

/*------------------------------------------------------------------------------------------
* This script retrieves all the facilitators signed up for a given slot.
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

// Gets the booking information from the caller
$calendar = new DB_Calendar();

// Ensure that slot_id is not empty
if ($_POST['s_id']){
	
	// Update the database 
	$calendar->getFacilitators($_POST['s_id']);
	
}
else {
	echo "issue";
} 

?>