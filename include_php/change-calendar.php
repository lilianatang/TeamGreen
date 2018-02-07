<?php

/*------------------------------------------------------------------------------------------
* This script retrieves the facilitation days available from the database. (used by week-picker2.js)
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

$calendar = new DB_Calendar();

if ($_POST['days']){
	
	// Extract the days to query for from the caller
	$days = $_POST['days'];
	$id = $_POST['classid'];
	
	$days_split = explode(" ", $days);
	
	// Find facilitation events for each day 
	foreach ($days_split as $day) {
		
		$calendar->findEvents($day, $id);
	}
}

else {
	echo "issue";
}
	
	
?>
