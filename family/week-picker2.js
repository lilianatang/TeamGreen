/*Source: https://gist.github.com/chengscott/20510d77b4f5038e22cb */

jQuery(document).ready(function() {  


	/* -------------- Added by Komal ---------------*/

	/* ------------------------------------------------------------------
	* dateString Function
	* This function takes a string in the form of YYYY-MM-DD and 
	* converts it to a date with the month as a word. 
	*
	* Parameters: date - A string in the form of YYYY-MM-DD
	* Returns: stringDate - A string version of date the month in words
	*--------------------------------------------------------------------*/
	var dateString = function (date){
		
		/* Split the date format into month, day, & year */
		var months = ["January", "February", "March", "April", "May", "June", "July", "August" ,"September", "October", "November", "December"];
		var nums = date.split("-");
		var stringDate = "";
		
		/* Find the month */
		stringDate = months[parseInt(nums[1] - 1)];
		stringDate += " ";
		/* Find the day */
		stringDate += nums[2];
		stringDate += " ";
		/* Find the year */
		stringDate += nums[0];
		
		return stringDate;
	}
	

	/* ---------------------------------------------*/
	
	/* From the original author */
    var startDate, endDate;
	
    var selectCurrentWeek = function () {
        window.setTimeout(function () {
            $('.ui-weekpicker').find('.ui-datepicker-current-day a').addClass('ui-state-active').removeClass('ui-state-default');
        }, 1);
    }
    var setDates = function (input) {
        var $input = $(input);
        var date = $input.datepicker('getDate');
        if (date !== null) {

            var firstDay = $input.datepicker("option", "firstDay");
            var dayAdjustment = (date.getDay() - firstDay + 7) % 7;

            var inst = $input.data('datepicker');
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
			
			/* -------------------------------------------------------------------------------------------------------------------
				This code is used to change the large calendar according to the week selected in the small calendar. 
				Added by Komal
			*/
			
			var weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
			
			/* Get all the event group tags from the index file - the event group tags correspond to each day of the week */
			var days = $(".events-group");
			
			/* This string will hold all the dates in the selected week in SQL format so that that we can query the database for events */
			var all_days = "";
		
			/* Cycle through each day of the week and display it accordingly */
			for (var i = 1; i < 6; i++){
				
				/* Get a date of the week in various formats */
				var current_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment + i); // Get the current date
				var date_picker_date = $.datepicker.formatDate( dateFormat, current_date, inst.settings ); // same as SQL date format
				var string_date = dateString(date_picker_date); // String format (ex. Thursday February 01 2018)
				
				
				/* Convert the date to a String and display it */
				$(days[i-1]) // Go through each event-group tag and apply the following: 
				
					/* Add a name = 'date' attribute to each events-group component */
					.attr("name", date_picker_date)
					
					/* Change the name of the date to the string_date we got earlier*/
					.find(".top-info .date")
					.text(weekDays[i - 1] + " " + string_date)
					
					/* Style it */
					.css("margin-bottom", "10px")
					.css("text-align", "center");
			
				/* Clear the calendar of events so that we can query the database and update the display*/
				$(days[i-1]).find("ul").html("");
				
				/* Compile a string of all dates for this week for querying purposes */
				all_days += date_picker_date;
				all_days += " ";
				
			}
			
			/*-----------------------------------------------------------------------------------------------------------------*/
			
			/* From the original author GET RID OF THIS LATER! */
            $('#startDate').text($.datepicker.formatDate(dateFormat, startDate, inst.settings));
            $('#endDate').text($.datepicker.formatDate(dateFormat, endDate, inst.settings));
            $('#weekNo').text($.datepicker.iso8601Week(date));
			
    
			/*----------------
				This code queries the database for events and then updates the calendar accordingly
				RIGHT NOW THE CLASS ID IS SET TO 1 - CHANGE THIS LATER 
			*/
			
			$.post("../include_php/change-calendar.php", { days: all_days, classid: 1 }, function(data)
				{ 
					console.log(data); 
				
					/* Take the data retrieved from the query and extract the event data from it */
					var new_days = data.split("~");
					new_days.pop();

					for (var i = 0; i < new_days.length; i ++){
						
						/* Extract event data */
						var event = new_days[i].split(",");
						var slot_id = event[0];
						var classroom_id = event[1];
						var date = event[2].split(" ")[0];
						var time_start = event[2].split(" ")[1].slice(0,-3);
						var time_end = event[3].split(" ")[1].slice(0,-3);
						var facilitators_needed = event[4];
						
						/* Create a new event node to add to the calendar html file */
						var day = 
						$(" <li class='single-event' data-start= '" + time_start + "' data-end='" + time_end + 
							"' data-content='facilitation-sign-up' data-event='event-1" +
							"'> <a href='#0'> <em class='event-name'> Facilitation Slot </em> <br> <strong class = 'positions'>" +
							"...... </strong> </a> </li>");
						
						/* Add the new event to the calendar */
						$("[name='" + date + "']").find("ul").append(day);
					
						/* Create new SchedulePlan objects for each date (as specified in calendar-main.js) */
						start();
						$(window).trigger('resize');
					}
					
				} 
			);
			
			/*-----------------------*/
	
			
        }
    }
	
	
    $('.week-picker').datepicker({
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        beforeShow: function () {
            $('#ui-datepicker-div').addClass('ui-weekpicker');
            selectCurrentWeek();
        },
        onClose: function () {
            $('#ui-datepicker-div').removeClass('ui-weekpicker');
        },
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function (dateText, inst) {
            setDates(this);
            selectCurrentWeek();
            $(this).change();
        },
        beforeShowDay: function (date) {
            var cssClass = '';
            if (date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function (year, month, inst) {
            selectCurrentWeek();
        }
    });
    
    setDates('.week-picker');
    var $calendarTR = $('.ui-weekpicker .ui-datepicker-calendar tr');
    $calendarTR.on('mousemove', function () {
        $(this).find('td a').addClass('ui-state-hover');
    });
    $calendarTR.on('mouseleave', function () {
        $(this).find('td a').removeClass('ui-state-hover');
    });
});
