/*------------------------------------------------
* This file is used to populate a choose-teacher selector 
* Author: Komal
*------------------------------------------------*/

jQuery(document).ready(function($){
	
	$.post("../include_php/get-teachers.php", function(data){
		
		// Organize data from the query 
		var teacher_info = data.split(",");
		teacher_info.pop();
		
		// Go through each piece of data and create a selection out of it
		for (var i = 0; i < teacher_info.length; i ++){
			
			// Split data so [0] = user id and [1] = teacher name (fullname)
			var teacher_specific = teacher_info[i].split("~");
			
			// Create selection
			var new_option = $("<option value = '" + teacher_specific[0] + 
			"'>" + teacher_specific[1] + " </option>");
			
			// Add selection
			$("#choose-teacher").append(new_option);
		}
		
	});
	
});