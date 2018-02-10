/*------------------------------------------------
* This file is used to populate a choose-family selector 
* Author: Komal
*------------------------------------------------*/

jQuery(document).ready(function($){
	
	// This code populates the family username selection 
	$.post("../include_php/get-families.php", function(data){
		
		// Organize data from the query 
		var family_info = data.split(",");
		family_info.pop();
		
		// Go through each piece of data and create a selection out of it
		for (var i = 0; i < family_info.length; i ++){
			
			// Split data so [0] = family id and [1] = family username
			var family_specific = family_info[i].split(" ");
			
			// Create selection
			var new_option = $("<option value = '" + family_specific[0] + 
			"'>" + family_specific[1] + " </option>");
			
			// Add selection
			$("#choose-family").append(new_option);
		}
		
	});
	
});