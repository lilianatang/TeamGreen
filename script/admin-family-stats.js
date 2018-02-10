/*--------------------------------------------------------------------------------------------------------
* This file containers JS and JQ for interacting with the adminFamilyStatistics URI
*
* Author Wesley
*--------------------------------------------------------------------------------------------------------*/



/*
* Goes into database and find all the users(families log in data) and updates the select family 
* drop down menu.
*/
$.post("../include_php/get-admin-stats-family.php",function (data) 
	{

		/* Find the Family Selector ID*/
		var family_selector = $("#family-selection");

		// Extract family names 
		var family_names_array = data.split(",");
		family_names_array.pop();
		
		/* Go through the data retrieved from the database and create selections for each class */
		for (var i = 0; i < family_names_array.length; i ++){

			// Create a new family selection to add
			var add_family = $("<option value =" + family_names_array[i] + ">" + 
				family_names_array[i] + "</option>");
				
			// Add selection to the selector 
			family_selector.append(add_family);
		}
	}
);











/*test case when family name is selected and January and year is selected.*/
var table_data = "2017/01/01 2017/01/07 1.0 1.2,2017/01/08 2017/01/14 3 1.2,2017/01/15 2017/01/21 0.5 1.2,2017/01/22 2017/01/28 3.5 1.2,";

		/*Find the Table selector*/
		var table_selector = $("#stats-table");

		/*extract weekly data -> creates array of strings with weekly data information inside in string*/
		var weekly_data = table_data.split(",");
		weekly_data.pop();

		/*Go through the weekly data retrieved from the database and populate html table*/
		for (var i = 0; i < weekly_data.length; i ++){

			/*select the table body to add onto.*/
			var table = $("#stats-table-body");
			// Extract weekyly info
			var weekly_info = weekly_data[i].split(" ");

			/*before building html string to add to table check if weekly hours are completed*/
			/*completed > required -> if statement*/
			if (parseInt(weekly_info[2]) > parseInt(weekly_info[3]) ){

				var add_week = $("<tr><td>" + weekly_info[0] + " to " + weekly_info[1] + "</td><td>" + weekly_info[2] + "</td><td>"  + "&#10003;" + "</td></tr");
			}
			else{
				var add_week = $("<tr><td>" + weekly_info[0] + " to " + weekly_info[1] + "</td><td>" + weekly_info[2] + "</td><td>"  + "&#10005;" + "</td></tr");
			}

			console.log(add_week.html());
			table.append(add_week);

		}


		/*
		<script>
			var table = $("#stats-table-body");
			var add_week = $("<tr><td>jan 34 to feb 34</td><td>jan 34 to feb 34</td><td>jan 34 to feb 34</td></tr");
			console.log(add_week.html());
			table.append(add_week);
				


		</script>>


		*/



