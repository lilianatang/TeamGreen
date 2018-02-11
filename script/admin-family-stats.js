/*--------------------------------------------------------------------------------------------------------
* This file containers JS and JQ for interacting with the adminFamilyStatistics URI
*
* Author Wesley
*--------------------------------------------------------------------------------------------------------*/



/*
* When page first loads up.
* Goes into database and find all the users(families log in data) and updates the select family 
* drop down menu. also loads default values that are in the selection boxes: family,Month,Year
*/

$.post("../include_php/get-admin-stats-family.php",function (data) 
	{


		/* Find the Family Selector ID*/
		var family_selector = $("#family-selection");

		// Extract family names and ID
		var family_names_array = data.split(",");
		family_names_array.pop();
		
		/* Go through the data retrieved from the database and create selections for each class */
		for (var i = 0; i < family_names_array.length; i ++){

			// split the "family name ID string" into an array of ["family name","ID"] to extract into selector.
			family_info = family_names_array[i].split(" ");

			// Create a new family selection to add
			var add_family = $("<option value =" + family_info[1] + ">" + family_info[0] + "</option>");
				
			// Add selection to the selector 
			family_selector.append(add_family);
		}

		var userID = $('#family-selection').val();
		//console.log(userID);

		// Take the  userID and get the family ids associated with userid
		$.post("../include_php/get-admin-stats-family-id.php", {u_id: userID} ,function (data) 
			{
			//gets the family id based on user id.
			var f_info = data.split(",");
			f_info.pop();


			// extracts family id from array
			var family_id = f_info[0];

			/*
			* updates the family information facilitator list based on default family selected value.
			*
			*/
			$.post("../include_php/get-admin-stats-family-facilitators.php", {f_id: family_id} ,function (data) 
			{
			//gets familitators based on family_id
			console.log(data);
			var facilitators = data.split(",");
			facilitators.pop();

			//find un-ordered list tag
			f_list = $("#Facilitators-list");


			for (var i = 0; i < facilitators.length; i ++){

				// Create a new facilitator to add to info list
				var add_facilitator = $("<li>" + facilitators[i] + "</li>");
					
				// Add facilitator name to info list 
				f_list.append(add_facilitator);
			}

			//end of callback function
			}
			// end of third post request
			);

			/*
			* updates the family information student list based on default family select value.
			*
			*/
			$.post("../include_php/get-admin-stats-family-students.php", {f_id: family_id} ,function (data) 
			{
			//gets familitators based on family_id
			console.log(data);
			var students = data.split(",");
			students.pop();

			//find un-ordered list tag
			s_list = $("#Students-list");


			for (var i = 0; i < students.length; i ++){

				// Create a new facilitator to add to info list
				var add_student = $("<li>" + students[i] + "</li>");
					
				// Add facilitator name to info list 
				s_list.append(add_student);
			}

			//end of callback function
			}
			// end of fourth post request
			);

			/*
			* updates HTML table based on default values of selectors -> family,month,year.
			*
			*/

			// extract month selected
			var month_selector = $('#month-selection').val();
			console.log(month_selector);
			// exctract year selected
			var year_selector = $('#year-selection').val();
			console.log(year_selector);

			$.post("../include_php/get-admin-stats-family-history.php", {f_id: family_id, month: month_selector, year: year_selector} ,function (data) 
			{

			/*Find the Table selector*/
			var table_selector = $("#stats-table");

			/*extract weekly data -> creates array of strings with weekly data information inside in string*/
			var weekly_data = data.split("~");
			weekly_data.pop();

			//month totaly to add to family information.
			var monthly_total = 0;

			/*Go through the weekly data retrieved from the database and populate html table*/
			for (var i = 0; i < weekly_data.length; i ++){
				/*select the table body to add onto.*/
				var table = $("#stats-table-body");
				// Extract weekyly info
				var weekly_info = weekly_data[i].split(",");

				monthly_total += parseInt(weekly_info[3]);

				/*before building html string to add to table check if weekly hours are completed*/
				/*completed > required -> if statement*/
				if (parseInt(weekly_info[2]) > parseInt(weekly_info[3]) ){

					var add_week = $("<tr><td>" + weekly_info[0] + " to " + weekly_info[1] + "</td><td>" + weekly_info[3] + "</td><td>"  + "&#10003;" + "</td></tr");
				}
				else{
					var add_week = $("<tr><td>" + weekly_info[0] + " to " + weekly_info[1] + "</td><td>" + weekly_info[3] + "</td><td>"  + "&#10005;" + "</td></tr");
				}
				table.append(add_week);
			} // end of for loop

			console.log(monthly_total);

			//find selector for monthly total and update it.
			month_span = $('#monthly-total');
			month_span.append(monthly_total);
			
			}
			// end of fifth post request
			);

		//end of second post call back function
		}
		// end of second post request
		);
	// end of first post callback function
	}
// end of first post
);








