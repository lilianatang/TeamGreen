<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'admin_family_stats.php';

$family_stats = new Family_Stats();
$family_stats->getFamilyDropDown();

?>