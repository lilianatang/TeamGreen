<?php

require_once 'db_calendar.php';

// Gets the booking information 
$calendar = new DB_Calendar();
if ($_POST['s_id'] && $_POST['f_id']){
	
	if ($_POST['comments']){
		$notes = $_POST['comments'];
	}
	else {
		$notes = null;
	}
	
	$slot_id = $_POST['s_id'];
	$facilitator_id = $_POST['f_id'];

	$calendar->bookFacilitation($facilitator_id, $notes, $slot_id);
	
}
else {
	echo "issue";
} 

?>