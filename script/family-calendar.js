
// This variable will hold the names of the facilitators for this family 
var facilitator_data;

/* Temporary - family id will always be 1 - NEED COOKIES ! */	
var id = 1;

jQuery(document).ready(function($){

	/* This call queries the database and updates the list of facilitators based on who's logged in */
	$.post("../include_php/calendar-get-facilitators.php", { input: id }, function (data) 
		{ 
			// Take the data from the database for this family, and store it in an array
			facilitator_data = data.split(",");
			
			// Get rid of the empty entry at the end
			facilitator_data.pop();
			
		}
	 ); 
 
});