/*------------------------------------------------
* This file is used to populate a choose classroom selector 
* Author: Komal
*------------------------------------------------*/

jQuery(document).ready(function($){

	/* This call queries the database and updates the list of classrooms to populate the class selector */
	$.post("../include_php/get-classes.php",function (data) 
		{ 
			// Take the data from the database for this class, and store it in an array
			var classes = data.split(",");
			classes.pop();
			
			/* Find the class selector */
			var class_selector = $("#class-select");
			
			/* Go through the data retrieved from the database and create selections for each class */
			for (var i = 0; i < classes.length; i ++){
				
				// Extract classes 
				var class_info = classes[i].split(" ");
				
				// Create a new class selection
				var new_class = $("<option value =" + class_info[0] + ">" + 
					class_info[1] + "</option>");
					
				// Add selection to the selector 
				class_selector.append(new_class);
			}
			
		}
	 ); 
	 
		 
});