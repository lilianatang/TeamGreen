<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'admin_family_stats.php';

$family_stats = new Family_Stats();

// checks to see if u_id is empty
if ($_POST['f_id'] && $_POST['month'] && $_POST['year']){

$famID = $_POST['f_id'];
$month = $_POST['month'];
$year = $_POST['year'];

//runs php function to get family id based on user id.
$family_stats->getFamilyHistory($famID,$year,$month);
}
//echo issue if id is empty
else{
		echo "issue";
}

?>