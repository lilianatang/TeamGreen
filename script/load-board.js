/*------------------------------------------------
* This file is used to populate a choose-board selector 
* Author: Komal
*------------------------------------------------*/

jQuery(document).ready(function($){
	
	$.post("../include_php/get-board.php", function(data){
		
		// Organize data from the query 
		var board_info = data.split(",");
		board_info.pop();
		
		// Go through each piece of data and create a selection out of it
		for (var i = 0; i < board_info.length; i ++){
			
			// Split data so [0] = user id and [1] = board member username
			var board_specific = board_info[i].split(" ");
			
			// Create selection
			var new_option = $("<option value = '" + board_specific[0] + 
			"'>" + board_specific[1] + " </option>");
			
			// Add selection
			$("#choose-board").append(new_option);
		}
		
	});
	
});