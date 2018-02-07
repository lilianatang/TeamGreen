<?php

/*------------------------------------------------------------------------------------------
* This script inserts a new facilitation booking into the database (used by calendar-main.js)
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

// Gets the booking information from the caller
$calendar = new DB_Calendar();

// Ensure that the fields are empty
if ($_POST['s_id'] && $_POST['f_id']){
	
	// Check if the comment field was left empty
	if ($_POST['comments']){
		$notes = $_POST['comments'];
	}
	else {
		$notes = null;
	}
	
	$slot_id = $_POST['s_id'];
	$facilitator_id = $_POST['f_id'];

	// Update the database 
	$calendar->bookFacilitation($facilitator_id, $notes, $slot_id);
	
}
else {
	echo "issue";
} 

?>