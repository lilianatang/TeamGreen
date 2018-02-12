<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'admin_family_stats.php';

$family_stats = new Family_Stats();

// checks to see if u_id is empty
if ($_POST['f_id'] && $_POST['year']){

$famID = $_POST['f_id'];
$year = $_POST['year'];

//runs php function to get family id based on user id.
$family_stats->getYearlyHours($famID,$year);
}
//echo issue if id is empty
else{
		echo "issue";
}

?>