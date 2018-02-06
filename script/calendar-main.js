/*--------------------------------------------------------------------------------------------------------
* This document contains event handling objects and methods for the CodyHouse Schedule template
* Source: https://codyhouse.co/gem/schedule-template/
* 
* Additional alterations to the original code have been made to accommodate the facilitation sign up process.
* Edited by Komal 
*--------------------------------------------------------------------------------------------------------*/

// This variable will hold the names of the facilitators for this family 
var facilitator_data;

/* Temporary - family id will always be 1 - NEED COOKIES ! */	
var id = 1;

/* This call queries the database and updates the list of facilitators based on who's logged in */
$.post("../include_php/calendar-get-facilitators.php", { input: id }, function (data) 
	{ 
		// Take the data from the database for this family, and store it in an array
		facilitator_data = data.split(",");
		
		// Get rid of the empty entry at the end
		facilitator_data.pop();
		
	}
 ); 
 
 
/* This call queries the database and updates the list of classrooms to populate the class selector */
$.post("../include_php/get-classes.php",function (data) 
	{ 
		// Take the data from the database for this family, and store it in an array
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
 
	 
/* From the original author */
var transitionEnd = 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend';
var transitionsSupported = ( $('.csstransitions').length > 0 );
//if browser does not support transitions - use a different event to trigger them
if( !transitionsSupported ) transitionEnd = 'noTransition';

//should add a loading while the events are organized 

// From the original author
function SchedulePlan( element ) {
	this.element = element;
	this.timeline = this.element.find('.timeline');
	this.timelineItems = this.timeline.find('li');
	this.timelineItemsNumber = this.timelineItems.length;
	this.timelineStart = getScheduleTimestamp(this.timelineItems.eq(0).text());
	//need to store delta (in our case half hour) timestamp
	this.timelineUnitDuration = getScheduleTimestamp(this.timelineItems.eq(1).text()) - getScheduleTimestamp(this.timelineItems.eq(0).text());

	this.eventsWrapper = this.element.find('.events');
	this.eventsGroup = this.eventsWrapper.find('.events-group');
	this.singleEvents = this.eventsGroup.find('.single-event');
	this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();

	this.modal = this.element.find('.event-modal');
	this.modalHeader = this.modal.find('.header');
	this.modalHeaderBg = this.modal.find('.header-bg');
	this.modalBody = this.modal.find('.body'); 
	this.modalBodyBg = this.modal.find('.body-bg'); 
	this.modalMaxWidth = 800;
	this.modalMaxHeight = 480;

	this.processingBooking = false; // Komal added this to avoid the double clicking issue
	this.animating = false;

	this.initSchedule();
}

// From the original author
SchedulePlan.prototype.initSchedule = function() {

	this.scheduleReset();
	this.initEvents();
};

// From the original author
SchedulePlan.prototype.scheduleReset = function() {
	var mq = this.mq();
	if( mq == 'desktop' && !this.element.hasClass('js-full') ) {
		//in this case you are on a desktop version (first load or resize from mobile)
		this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();
		this.element.addClass('js-full');
		this.placeEvents();
		this.element.hasClass('modal-is-open') && this.checkEventModal();
	} else if(  mq == 'mobile' && this.element.hasClass('js-full') ) {
		//in this case you are on a mobile version (first load or resize from desktop)
		this.element.removeClass('js-full loading');
		this.eventsGroup.children('ul').add(this.singleEvents).removeAttr('style');
		this.eventsWrapper.children('.grid-line').remove();
		this.element.hasClass('modal-is-open') && this.checkEventModal();
	} else if( mq == 'desktop' && this.element.hasClass('modal-is-open')){
		//on a mobile version with modal open - need to resize/move modal window
		this.checkEventModal('desktop');
		this.element.removeClass('loading');
	} else {
		this.element.removeClass('loading');
	}
};

// From the original author
SchedulePlan.prototype.initEvents = function() {
	var self = this;

	this.singleEvents.each(function(){

		//create the duration_label for each event
		var durationLabel = '<span class="event-date">'+$(this).data('start')+' - '+$(this).data('end')+'</span>';
		$(this).children('a').prepend($(durationLabel)); 


		//detect click on the event and open the modal
		$(this).on('click', 'a', function(event){
			event.preventDefault();
			if( !self.animating ) self.openModal($(this));
		});
	});

	//close modal window
	this.modal.on('click', '.close', function(event){
		event.preventDefault();
		if( !self.animating ) self.closeModal(self.eventsGroup.find('.selected-event'));
	});
	this.element.on('click', '.cover-layer', function(event){
		if( !self.animating && self.element.hasClass('modal-is-open') ) self.closeModal(self.eventsGroup.find('.selected-event'));
	});
};

// From the original author
SchedulePlan.prototype.placeEvents = function() {
	var self = this;
	this.singleEvents.each(function(){
		//place each event in the grid -> need to set top position and height
		var start = getScheduleTimestamp($(this).attr('data-start')),
			duration = getScheduleTimestamp($(this).attr('data-end')) - start;

		var eventTop = self.eventSlotHeight*(start - self.timelineStart)/self.timelineUnitDuration,
			eventHeight = self.eventSlotHeight*duration/self.timelineUnitDuration;
		
		$(this).css({
			top: (eventTop -1) +'px',
			height: (eventHeight+1)+'px'
		});
		
		
	});

	this.element.removeClass('loading');
};

// From the original author
 SchedulePlan.prototype.openModal = function(event) {
	var self = this;
	
	var slot_info = event.find('.positions').text();
	
	// Exit if the slot is full 
	if (slot_info === "SLOT FULL"){
		return;
	}
	
	var mq = self.mq();
	this.animating = true;
	
	//update event name and time
	this.modalHeader.find('.event-name').text(event.find('.event-name').text());
	this.modalHeader.find('.event-date').text(event.find('.event-date').text());
	this.modalHeader.find('.positions').text(slot_info);
	this.modal.attr('data-event', event.parent().attr('data-event'));
	
	/* Retrieve the slot id */
	var slot_id = event.parent().attr('slot-id');
	
	
	/* 
		This bit of code brings in the facilitation sign up form and sends data to the database when the user
		clicks 'Book Facilitation'
	*/
	
	//update event content based on an html file
	this.modalBody.find('.event-info').load("../family/facilitation-sign-up.html", function(data){
		
		//once the event content has been loaded
		self.element.addClass('content-loaded');
		
		// Clear any facilitator data that may be present already
		self.modalBody.find("#select-facilitator").html("");
		
		// Go through all facilitators from the query and add them as a drop-down option
		for (var i = 0; i < facilitator_data.length; i += 2){
			
			// Create an option for a facilitator 
			var $selection = $(" <option value = " + facilitator_data[i] + ">" + 
				facilitator_data[i+1] + "</option>" );
			
			// Add the option to the form 
			$selection.appendTo(self.modalBody
				.find("#select-facilitator"));
			
		}
		
		
		
		/* Creates an action event to send data from the form to the database */
		$("#submit-booking").click(function (event) {
			
			/* Prevent the page from reloading */			
			event.preventDefault();
			
			if (self.processingBooking === false) {
				
				// Indicate that a booking is currently being processed 
				self.processingBooking = true;
			
				/* Slot id is already stored in slot_id */
				
				/* Get notes */
				var notes = $(' #comments').text();
				
				/* Get facilitator id */
				var facilitator_id = $("#select-facilitator").val();
				
				/* Initiate query to update the database */
				$.post("../include_php/calendar-book-facilitation.php", { s_id: slot_id, comments: notes, f_id : facilitator_id }, function (data) 
					{ 
						/* Close the modal window */
						self.closeModal(self.eventsGroup.find('.selected-event'));
						
						/* Display successful or unsuccessful */
						$('#user-message').text(data).css("font-weight", "bold");
						
						/* Update calendar */
						updateCalendar();
						
						// Indicate that a booking is no longer being processed
						self.processingBooking = false;
					}
				); 
			}
		});
			
		
	});

	/* From the original author */
	this.element.addClass('modal-is-open');

	setTimeout(function(){
		//fixes a flash when an event is selected - desktop version only
		event.parent('li').addClass('selected-event');
	}, 10);

	if( mq == 'mobile' ) {
		self.modal.one(transitionEnd, function(){
			self.modal.off(transitionEnd);
			self.animating = false;
		});
	} else {
		var eventTop = event.offset().top - $(window).scrollTop(),
			eventLeft = event.offset().left,
			eventHeight = event.innerHeight(),
			eventWidth = event.innerWidth();

		var windowWidth = $(window).width(),
			windowHeight = $(window).height();

		var modalWidth = ( windowWidth*.8 > self.modalMaxWidth ) ? self.modalMaxWidth : windowWidth*.8,
			modalHeight = ( windowHeight*.8 > self.modalMaxHeight ) ? self.modalMaxHeight : windowHeight*.8;

		var modalTranslateX = parseInt((windowWidth - modalWidth)/2 - eventLeft),
			modalTranslateY = parseInt((windowHeight - modalHeight)/2 - eventTop);
		
		var HeaderBgScaleY = modalHeight/eventHeight,
			BodyBgScaleX = (modalWidth - eventWidth);

		//change modal height/width and translate it
		self.modal.css({
			top: eventTop+'px',
			left: eventLeft+'px',
			height: modalHeight+'px',
			width: modalWidth+'px',
		});
		transformElement(self.modal, 'translateY('+modalTranslateY+'px) translateX('+modalTranslateX+'px)');

		//set modalHeader width
		self.modalHeader.css({
			width: eventWidth+'px',
		});
		//set modalBody left margin
		self.modalBody.css({
			marginLeft: eventWidth+'px',
		});

		//change modalBodyBg height/width ans scale it
		self.modalBodyBg.css({
			height: eventHeight+'px',
			width: '1px',
		});
		transformElement(self.modalBodyBg, 'scaleY('+HeaderBgScaleY+') scaleX('+BodyBgScaleX+')');

		//change modal modalHeaderBg height/width and scale it
		self.modalHeaderBg.css({
			height: eventHeight+'px',
			width: eventWidth+'px',
		});
		transformElement(self.modalHeaderBg, 'scaleY('+HeaderBgScaleY+')');
		
		self.modalHeaderBg.one(transitionEnd, function(){
			//wait for the  end of the modalHeaderBg transformation and show the modal content
			self.modalHeaderBg.off(transitionEnd);
			self.animating = false;
			self.element.addClass('animation-completed');
		});
	}

	//if browser do not support transitions -> no need to wait for the end of it
	if( !transitionsSupported ) self.modal.add(self.modalHeaderBg).trigger(transitionEnd);
	
	
};

// From the original author
SchedulePlan.prototype.closeModal = function(event) {
	var self = this;
	var mq = self.mq();

	this.animating = true;

	if( mq == 'mobile' ) {
		this.element.removeClass('modal-is-open');
		this.modal.one(transitionEnd, function(){
			self.modal.off(transitionEnd);
			self.animating = false;
			self.element.removeClass('content-loaded');
			event.removeClass('selected-event');
		});
	} else {
		var eventTop = event.offset().top - $(window).scrollTop(),
			eventLeft = event.offset().left,
			eventHeight = event.innerHeight(),
			eventWidth = event.innerWidth();

		var modalTop = Number(self.modal.css('top').replace('px', '')),
			modalLeft = Number(self.modal.css('left').replace('px', ''));

		var modalTranslateX = eventLeft - modalLeft,
			modalTranslateY = eventTop - modalTop;

		self.element.removeClass('animation-completed modal-is-open');

		//change modal width/height and translate it
		this.modal.css({
			width: eventWidth+'px',
			height: eventHeight+'px'
		});
		transformElement(self.modal, 'translateX('+modalTranslateX+'px) translateY('+modalTranslateY+'px)');
		
		//scale down modalBodyBg element
		transformElement(self.modalBodyBg, 'scaleX(0) scaleY(1)');
		//scale down modalHeaderBg element
		transformElement(self.modalHeaderBg, 'scaleY(1)');

		this.modalHeaderBg.one(transitionEnd, function(){
			//wait for the  end of the modalHeaderBg transformation and reset modal style
			self.modalHeaderBg.off(transitionEnd);
			self.modal.addClass('no-transition');
			setTimeout(function(){
				self.modal.add(self.modalHeader).add(self.modalBody).add(self.modalHeaderBg).add(self.modalBodyBg).attr('style', '');
			}, 10);
			setTimeout(function(){
				self.modal.removeClass('no-transition');
			}, 20);

			self.animating = false;
			self.element.removeClass('content-loaded');
			event.removeClass('selected-event');
		});
	}

	//browser do not support transitions -> no need to wait for the end of it
	if( !transitionsSupported ) self.modal.add(self.modalHeaderBg).trigger(transitionEnd);
}

SchedulePlan.prototype.mq = function(){
	//get MQ value ('desktop' or 'mobile') 
	var self = this;
	return window.getComputedStyle(this.element.get(0), '::before').getPropertyValue('content').replace(/["']/g, '');
};

SchedulePlan.prototype.checkEventModal = function(device) {
	this.animating = true;
	var self = this;
	var mq = this.mq();

	if( mq == 'mobile' ) {
		//reset modal style on mobile
		self.modal.add(self.modalHeader).add(self.modalHeaderBg).add(self.modalBody).add(self.modalBodyBg).attr('style', '');
		self.modal.removeClass('no-transition');	
		self.animating = false;	
	} else if( mq == 'desktop' && self.element.hasClass('modal-is-open') ) {
		self.modal.addClass('no-transition');
		self.element.addClass('animation-completed');
		var event = self.eventsGroup.find('.selected-event');

		var eventTop = event.offset().top - $(window).scrollTop(),
			eventLeft = event.offset().left,
			eventHeight = event.innerHeight(),
			eventWidth = event.innerWidth();

		var windowWidth = $(window).width(),
			windowHeight = $(window).height();

		var modalWidth = ( windowWidth*.8 > self.modalMaxWidth ) ? self.modalMaxWidth : windowWidth*.8,
			modalHeight = ( windowHeight*.8 > self.modalMaxHeight ) ? self.modalMaxHeight : windowHeight*.8;

		var HeaderBgScaleY = modalHeight/eventHeight,
			BodyBgScaleX = (modalWidth - eventWidth);

		setTimeout(function(){
			self.modal.css({
				width: modalWidth+'px',
				height: modalHeight+'px',
				top: (windowHeight/2 - modalHeight/2)+'px',
				left: (windowWidth/2 - modalWidth/2)+'px',
			});
			transformElement(self.modal, 'translateY(0) translateX(0)');
			//change modal modalBodyBg height/width
			self.modalBodyBg.css({
				height: modalHeight+'px',
				width: '1px',
			});
			transformElement(self.modalBodyBg, 'scaleX('+BodyBgScaleX+')');
			//set modalHeader width
			self.modalHeader.css({
				width: eventWidth+'px',
			});
			//set modalBody left margin
			self.modalBody.css({
				marginLeft: eventWidth+'px',
			});
			//change modal modalHeaderBg height/width and scale it
			self.modalHeaderBg.css({
				height: eventHeight+'px',
				width: eventWidth+'px',
			});
			transformElement(self.modalHeaderBg, 'scaleY('+HeaderBgScaleY+')');
		}, 10);

		setTimeout(function(){
			self.modal.removeClass('no-transition');
			self.animating = false;	
		}, 20);
	}
};


/* Edit by Komal - added code to a function start for use by the week-picker.js script - the function is called at the end of this script */
start = function (){
	var schedules = $('.cd-schedule');
	 objSchedulesPlan = [],
		windowResize = false;
	
	if( schedules.length > 0 ) {
		schedules.each(function(){
			
			//create SchedulePlan objects
			var new_event = new SchedulePlan($(this));
			objSchedulesPlan.push(new_event);
			
			/* Fixes the slot size problem - replaces the events in the right positions */
			new_event.placeEvents();
			
		});
	}
}

/* From the original author */
$(window).on('resize', function(){
	if( !windowResize ) {
		windowResize = true;
		(!window.requestAnimationFrame) ? setTimeout(checkResize) : window.requestAnimationFrame(checkResize);
	}
});

$(window).keyup(function(event) {
	if (event.keyCode == 27) {
		objSchedulesPlan.forEach(function(element){
			element.closeModal(element.eventsGroup.find('.selected-event'));
		});
	}
});

function checkResize(){
	objSchedulesPlan.forEach(function(element){
		element.scheduleReset();
	});
	windowResize = false;
}

function getScheduleTimestamp(time) {
	//accepts hh:mm format - convert hh:mm to timestamp
	time = time.replace(/ /g,'');
	var timeArray = time.split(':');
	var timeStamp = parseInt(timeArray[0])*60 + parseInt(timeArray[1]);
	return timeStamp;
}

function transformElement(element, value) {
	element.css({
		'-moz-transform': value,
		'-webkit-transform': value,
		'-ms-transform': value,
		'-o-transform': value,
		'transform': value
	});
}

start();
