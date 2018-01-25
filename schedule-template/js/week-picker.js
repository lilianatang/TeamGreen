/*--------------------------------------------------------------------------------------------------------
* This document contains all the code for the week picker
* Source: http://www.tikalk.com/incubator/week-picker-using-jquery-ui-datepicker/
* 
* Additional alterations to the original code have been made to accommodate the facilitation sign up process.
* Edited by Komal 
*--------------------------------------------------------------------------------------------------------*/

// From the original author 
$(function() {
	
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
			var days = $(".top-info .date");
			
			/* Cycle through each day of the week and display it accordingly */
			for (var i = 1; i < 6; i++){
				
				/* Get a date of the week */
				var d = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + i);
				
				/* Convert the date to a String and display it */
				$(days[i-1])
					.text(weekDays[i - 1] + " " + dateString($.datepicker.formatDate( dateFormat, d, inst.settings )))
					.css("margin-bottom", "10px")
					.css("text-align", "center");
				$(days[i-1]).find(".single-event").attr('date', weekDays[i - 1] + " " + dateString($.datepicker.formatDate( dateFormat, d, inst.settings )));
			}
			
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

