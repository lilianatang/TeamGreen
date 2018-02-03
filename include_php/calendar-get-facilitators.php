<?php

require_once 'db_calendar.php';

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