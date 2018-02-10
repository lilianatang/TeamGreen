<?php

/*------------------------------------------------------------------------------------------
* This script retrieves all board member usernames where applicable 
*
* Author: Komal 
*------------------------------------------------------------------------------------------*/

require_once 'db_calendar.php';

$calendar = new DB_Calendar();

$calendar->getBoardMembers();

?>