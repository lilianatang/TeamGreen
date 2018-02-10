<?php

/*------------------------------------------------------------------------------------------
* This script retrieves all family usernames 
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

$calendar = new DB_Calendar();

$calendar->getTeachers();

?>