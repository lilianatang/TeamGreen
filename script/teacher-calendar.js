/*----------------------------------------------------------------
* This document contains the javascript & jquery for teacher calendar
* specific functions.
* 
* Author: Komal 
* -----------------------------------------------------------------*/


/* These functions are declared outside ready for access by other files */

var fillModal;
var create_event;

jQuery(document).ready(function($){
	
	/*------------------------------------------------------------------------------------------------------------------
	* create_event
	* This method creates a new DOM element including event data to be inserted into the DOM by the caller
	*
	* Parameters: event_data: An event object with the following fields: 
	*             slot_id - unique slot id in the database 
	*			  classroom_id - unique classroom id 
	*			  date -  the date the event applies to (SQL format)
	*			  time_start & time-end - a date-time string (SQL format)
	*			  facilitators_needed - number of facilitators needed 
	*			  facilitators_signed_up - the facilitators signed up already
	*			  count - the event number on a given day (ex. if one day had 2 events, the first would have count = 1)
	*
	* Return: A new jQuery element created to be inserted into the calendar 
	*-------------------------------------------------------------------------------------------------------------------*/
	create_event = function(event_data) {
		
		/* Indicate how many facilitators have signed up for the given slot */
		var slot_info = event_data['facilitators_signed_up'] + " of " + event_data['facilitators_needed'] + " facilitators signed up";
		
		/* Create a new event node to add to the calendar html file */
		return 	$(" <li class='single-event' slot-id= " + event_data['slot_id'] + " data-start= '" + event_data['time_start'] + "' data-end='" + event_data['time_end'] + 
			"' data-content='facilitation-info' data-event='event-" + event_data['count'] + 
			"'><a href='#0'> <em class='event-name'> Facilitation Slot </em> <br> <strong class = 'positions'>" +
			 slot_info +"</strong> </a> </li>");

		
	}

	/* -------------------------------------------------------------------------------
	* fillModal
	* This method edits the contents of the file loaded into the modal (family-sign-up.html) 
	* to cater to whichever family is logged in
	*
	* Parameters & login: None
	*-----------------------------------------------------------------------------*/
	fillModal = function(slot_id, event_element){
		
		// Find the insertion point in the document
		var $facilitator_list = event_element.modalBody.find("#facilitator-list");

		// Clear any facilitator data that may be present already
		$facilitator_list.html("");
		console.log(slot_id);
		// Go gather a list of facilitators and their notes from the database 
		$.post("../include_php/get-facilitators-from-slot-id.php", { s_id: slot_id }, function (data){
		
			console.log(data);
			var facilitator_info = data.split(",");
			facilitator_info.pop();
			
			/* Check if any facilitators have signed up */
			if (facilitator_info.length === 0){
				
				// If none have signed up, display a message
				$(" <li> No facilitators have signed up for this slot. </li>" ).appendTo($facilitator_list);
			
			}
			else {
				
				// Add each to the list 
				for (var i = 0; i < facilitator_info.length; i ++){
					
					// Create an option for a facilitator
					$(" <li>" + facilitator_info[i] + "</li>" ).appendTo($facilitator_list);
					
				}
			}	
		});
		
	}
 
 
 
});