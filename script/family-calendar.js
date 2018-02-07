/*----------------------------------------------------------------
* This document contains the javascript & jquery for family calendar
* specific functions.
* 
* Author: Komal 
* -----------------------------------------------------------------*/


/* These functions are declared outside ready for access by other files */

var fillModal;
var create_event;

jQuery(document).ready(function($){
	
	// This variable will hold the names of the facilitators for this family 
	var facilitator_data;
	
	/* Temporary - family id will always be 1 - NEED COOKIES ! */	
	id = 1;

	/* This call queries the database and updates the list of facilitators based on who's logged in */
	$.post("../include_php/calendar-get-facilitators.php", { input: id }, function (data) 
		{ 
			// Take the data from the database for this family, and store it in an array
			facilitator_data = data.split(",");
			console.log(facilitator_data);
			
			// Get rid of the empty entry at the end
			facilitator_data.pop();
			
		}
	 ); 
	
	
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
		var slot_info;
		if (event_data['facilitators_signed_up'] === event_data['facilitators_needed']){
			slot_info = "SLOT FULL";
		}
		else {
			slot_info = event_data['facilitators_needed'] - event_data['facilitators_signed_up'] + " of " + event_data['facilitators_needed'] + " positions available";
		}
		
		/* Create a new event node to add to the calendar html file */
		return 	$(" <li class='single-event' slot-id= " + event_data['slot_id'] + " data-start= '" + event_data['time_start'] + "' data-end='" + event_data['time_end'] + 
			"' data-content='facilitation-sign-up' data-event='event-" + event_data['count'] + 
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
		var select_facilitator = event_element.modalBody.find("#select-facilitator");
		
		// Clear any facilitator data that may be present already
		select_facilitator.html("");
		
		// Go through all facilitators from the query and add them as a drop-down option
		for (var i = 0; i < facilitator_data.length; i += 2){
			
			// Create an option for a facilitator 
			var $selection = $(" <option value = " + facilitator_data[i] + ">" + 
				facilitator_data[i+1] + "</option>" );
			
			// Add the option to the form 
			$selection.appendTo(select_facilitator);
			
		}
		
		/* Creates an action event to send data from the form to the database */
		$("#submit-booking").click(function (event) {
			
			/* Prevent the page from reloading */			
			event.preventDefault();
			
			if (event_element.processingBooking === false) {
				
				// Indicate that a booking is currently being processed 
				event_element.processingBooking = true;
			
				/* Slot id is already stored in slot_id */
				
				/* Get notes */
				var notes = $(' #comments').text();
				
				/* Get facilitator id */
				var facilitator_id = $("#select-facilitator").val();
				
				/* Initiate query to update the database */
				$.post("../include_php/calendar-book-facilitation.php", { s_id: slot_id, comments: notes, f_id : facilitator_id }, function (data) 
					{ 
						/* Close the modal window */
						event_element.closeModal(event_element.eventsGroup.find('.selected-event'));
						
						/* Display successful or unsuccessful */
						$('#user-message').text(data).css("font-weight", "bold");
						
						/* Update calendar */
						updateCalendar();
						
						// Indicate that a booking is no longer being processed
						event_element.processingBooking = false;
					}
				); 
			}
		});
			
		
	}
 
 
 
});