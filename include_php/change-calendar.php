<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'db_calendar.php';

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
