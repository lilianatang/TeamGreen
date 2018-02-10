<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'admin_family_stats.php';

$family_stats = new Family_Stats();

// checks to see if u_id is empty
if ($_POST['u_id']){

$userID = $_POST['u_id'];

//runs php function to get family id based on user id.
$family_stats->getFamilyID($userID);
}
//echo issue if id is empty
else{
		echo "issue";
}

?>