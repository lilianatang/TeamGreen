<?php

/* This file will be used to update the facilitation days available on the index screen */

require_once 'db_calendar.php';

$calendar = new DB_Calendar();
$calendar->getClasses();

?>