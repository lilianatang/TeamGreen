<?php

/* This file will be used to update classrooms list */

require_once 'db_calendar.php';

$calendar = new DB_Calendar();
$calendar->getClasses();

?>