jQuery(document).ready(function() {
	
	
	
	var startDate;
	var endDate;
	
	/* ------------------------------------------------------------------
	* dateString Function
	* This function takes a string in the form of MM/DD/YYYY and 
	* converts it to a date with the month as a word. 
	*
	* Parameters: date - A string in the form of MM/DD/YYYY
	* Returns: stringDate - A string version of date the month in words
	*
	* Author: Komal Aheer
	*--------------------------------------------------------------------*/
	var dateString = function (date){
		
		/* Split the date format into month, day, & year */
		var months = ["January", "February", "March", "April", "May", "June", "July", "August" ,"September", "October", "November", "December"];
		var nums = date.split("/");
		var stringDate = "";
		
		/* Find the month */
		stringDate = months[parseInt(nums[0] - 1)];
		stringDate += " ";
		/* Find the day */
		stringDate += nums[1];
		stringDate += " ";
		/* Find the year */
		stringDate += nums[2];
		
		return stringDate;
	}
	
	/* ------------------------------------------------------------------
	* sqlDateFormat Function
	* This function takes a string in the form of MM/DD/YYYY and 
	* converts it to a date in sql format (YYYY-MM-DD) 
	*
	* Parameters: date - A string in the form of MM/DD/YYYY
	* Returns: sqlDate - A sql string version of date 
	*
	* Author: Komal Aheer
	*--------------------------------------------------------------------*/
	var sqlDateFormat = function (date) {
	
		/* Split the date format into month, day, & year */
		var nums = date.split("/");
		var sqlDate = "";
		
		/* Find the year */
		sqlDate = nums[2];
		sqlDate += "-";
		/* Find the month */
		sqlDate += nums[0];
		sqlDate += "-";
		/* Find the day */
		sqlDate += nums[1];
		
		return sqlDate;
	}
	
	// From the original author
	var selectCurrentWeek = function() {
		window.setTimeout(function () {
			$('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
		}, 1);
		
	}
	
	// From the original author
	$('.week-picker').datepicker( {
		showOtherMonths: true,
		selectOtherMonths: true,
		onSelect: function(dateText, inst) { 
		
			var date = $(this).datepicker('getDate');
			var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
			
			/* 
				This code is used to change the large calendar according to the week selected in the small calendar. 
				Added in by Komal
			*/
			var weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
			/* Get all the date tags from the index file */
			var days = $(".events-group");
			var sqlDays = "";
			/* Cycle through each day of the week and display it accordingly */
			for (var i = 1; i < 6; i++){
				
				/* Get a date of the week */
				var d = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + i);
				
				/* Convert the date to a String and display it */
				$(days[i-1])
					/* Add a name = 'date' attribute to each events-group component */
					.attr("name", sqlDateFormat($.datepicker.formatDate( dateFormat, d, inst.settings )))
					/* Change the name of the date */
					.find(".top-info .date")
					.text(weekDays[i - 1] + " " + dateString($.datepicker.formatDate( dateFormat, d, inst.settings )))
					.css("margin-bottom", "10px")
					.css("text-align", "center");
			
				$(days[i-1]).find("ul").html("");
				
				var newSqlDay = sqlDateFormat($.datepicker.formatDate( dateFormat, d, inst.settings ))
				sqlDays += newSqlDay;
				sqlDays += " ";
				
				

			}
			
			
			// THIS IS WHERE WE USE PHP TO GET EVENTS UPDATED -----------------
			
			
			$.post("../include_php/change-calendar.php", { days: sqlDays, classid: 1 }, function(data)
				{ 
					console.log(data); // right now this just prints the slot id that results from the week selected
				
					var new_days = data.split("~");
					new_days.pop();

					for (var i = 0; i < new_days.length; i ++){
						var event = new_days[i].split(",");
						var slot_id = event[0];
						var classroom_id = event[1];
						var date = event[2].split(" ")[0];
						var time_start = event[2].split(" ")[1].slice(0,-3);
						var time_end = event[3].split(" ")[1].slice(0,-3);
						var facilitators_needed = event[4];
						
						var day = 
						$(" <li class='single-event' data-start= '" + time_start + "' data-end='" + time_end + 
							"' data-content='facilitation-sign-up' data-event='event-1'> <a href='#0'> <em class='event-name'> Facilitation Slot </em> <br> <strong class = 'positions'>" +
							"...... </strong> </a> </li>");
						
						$("[name='" + date + "']").find("ul").html("").append(day);
					
						start();
					}

					
				} 
			);
			// -----------------------------------------------------------------
			
			selectCurrentWeek();
		},
		beforeShowDay: function(date) {
			var cssClass = '';
			if(date >= startDate && date <= endDate)
				cssClass = 'ui-datepicker-current-day';
			return [true, cssClass];
		},
		onChangeMonthYear: function(year, month, inst) {
			selectCurrentWeek();
		}
		
	});
	
	
	$('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
	$('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });

});