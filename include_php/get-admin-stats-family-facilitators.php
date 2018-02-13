<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'admin_family_stats.php';

$family_stats = new Family_Stats();

// checks to see if u_id is empty
if ($_POST['f_id']){

$famID = $_POST['f_id'];

//runs php function to get family id based on user id.
$family_stats->getFamilyFacilitators($famID);
}
//echo issue if id is empty
else{
		echo "issue";
}

?>