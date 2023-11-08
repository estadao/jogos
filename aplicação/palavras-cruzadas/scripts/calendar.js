////////////////////////////////
// calendar.js
// version: 1.0.0
// author: Carol X.
// email: carolinex@gmail.com
////////////////////////////////

// Class Calendar
//   Pass options through these parameters:
//     selector: "#element-id" or ".element-class"
//     startDate: "yyyy/mm/dd",
//     lang: "pt-BR" (Default: en-US),
//     monthNames: ["january", "february", "march",...,"november", "december"]
//     period: ["yyyy/mm/dd", "yyyy/mm/dd"] (startDate, endDate)
//     onDateClick: function(event)

function Calendar( options ){
	var calendar;
	var selector = '#calendar';
	var today = new Date();
	var startDate = new Date( today.getFullYear(), today.getMonth(), today.getDate()+1 );
	var monthNames = {}, dayNames = {};
	var lang = 'en-US';
	var selectedDate = new Date( today.getFullYear(), today.getMonth(), today.getDate()+1 );
	var titleDiv, currentYear, prevLink, nextLink, currentMonth, weekDays, monthDays, dayLi;
	var enabledPeriod = [];
	var $this = this;
	$this.element;

	var init = function(){
		
		if( options ){
			startDate = parseDate( options.startDate ) || startDate;

			if( selector.indexOf( '#' ) > -1 ){
				selector = options.selector || selector;
				lang = options.lang || lang;

				var enabledDateStart, enabledDateEnd;
				enabledDateStart = (options.enabledPeriod[0] || '1500/01/01').toString().split('/');
				enabledDateEnd = (options.enabledPeriod[1] || '2200/01/01').toString().split('/');

				if( enabledDateStart.length == 3 && enabledDateEnd.length == 3 ){
					enabledPeriod = [
						new Date(enabledDateStart[0],enabledDateStart[1]-1,enabledDateStart[2], 0),
						new Date(enabledDateEnd[0],enabledDateEnd[1]-1,enabledDateEnd[2], 23)
					];
				}

			}
		}
		
		calendar = document.querySelector( selector );
		$this.element = calendar;

		if( !calendar ){ return; }
		
		// important for css styles
		calendar.classList.add( 'calendar' );

		// create the title bar
		titleDiv = create( 'div' );
		currentYear = create( 'span' );
		prevLink = create( 'a' );
		currentMonth = create( 'span' );
		nextLink = create( 'a' );

		titleDiv.className = 'cal-title';
		currentYear.className = 'cal-year';
		prevLink.className = 'cal-prev-month';
		currentMonth.className = 'cal-month';
		nextLink.className = 'cal-next-month';

		currentYear.innerHTML = startDate.getFullYear();
		currentMonth.innerHTML = monthNames[ lang ][ startDate.getMonth() ];

		prevLink.setAttribute('href', 'javascript:;');
		nextLink.setAttribute('href', 'javascript:;');

		titleDiv.appendChild( currentYear );
		titleDiv.appendChild( prevLink );
		titleDiv.appendChild( currentMonth );
		titleDiv.appendChild( nextLink );

		// create days of the week
		weekDays = create( 'ul' );
		monthDays = create( 'ul' );
		weekDays.className = 'cal-week-days';
		monthDays.className = 'cal-month-days';

		for( var i=0; i < 7; i++ ){
			dayLi = create( 'li' );
			dayLi.innerHTML = dayNames[ lang ][ i ];
			weekDays.appendChild( dayLi );
		}

		// apend new elements to calendar div
		calendar.appendChild( titleDiv );
		calendar.appendChild( weekDays );
		calendar.appendChild( monthDays );
		
		buildMonth( startDate );
		
		prevLink.addEventListener('click', onPrevClick);
		nextLink.addEventListener('click', onNextClick);
	}

	var buildMonth = function(day){

		var fDay = new Date(day.getFullYear(), day.getMonth(), 1);
		var newDay = new Date(fDay.getFullYear(), fDay.getMonth(), fDay.getDate());
		var calendarTitle = monthNames[ lang ][ day.getMonth() ];
		var currCell = 0;

		// console.log( enabledPeriod );
		calendar.querySelector( '.cal-month-days' ).innerHTML = '';
		selectedDate = new Date( fDay.getFullYear(), fDay.getMonth(), fDay.getDate() );

		//console.log( calendar.querySelector( '.cal-month' ) );
		calendar.querySelector( '.cal-month' ).innerHTML = calendarTitle;
		calendar.querySelector( '.cal-year' ).innerHTML = day.getFullYear();
		calendar.setAttribute( 'data-month', day.getMonth() );

		while( newDay.getMonth() == day.getMonth() ) {
	
			dayLi = create( 'li' );

			if(	currCell >= newDay.getDay() ){
				dayLi.innerHTML = newDay.getDate();

				if( newDay.getDate() == today.getDate()
						&& newDay.getMonth() == today.getMonth()
						&& newDay.getFullYear() == today.getFullYear() ){
					dayLi.classList.add('today');
				} else if( enabledPeriod.length == 2 &&
					 	(newDay.getTime() < enabledPeriod[0].getTime() ||
					 	newDay.getTime() > enabledPeriod[1].getTime()) ){
					dayLi.classList.add('disabled');
				}
				newDay.setDate( newDay.getDate() + 1 );
			}else{
				dayLi.classList.add('disabled');
			}
			
			// console.log( newDay.getTime(), enabledPeriod[0].getTime(), enabledPeriod[1].getTime() );
			calendar.querySelector( '.cal-month-days' ).appendChild( dayLi );
			currCell++;

			if ( currCell >= 35 ) { break; }

		}

		if( options && options.onDateClick ){
			$this.onDateClick( options.onDateClick );
		}

	}

	var onNextClick = function( event ){
		startDate = new Date( startDate.getFullYear(), startDate.getMonth()+1,1,12 );
		buildMonth( startDate );
	}

	var onPrevClick = function( event ){
		startDate = new Date( startDate.getFullYear(), startDate.getMonth()-1, 1, 12 );
		buildMonth( startDate );
	}

	this.toggleVisibility = function( show ){
		if( show==true || !calendar.classList.contains('visible') ){
			$this.showCalendar();
		}else{
			$this.hideCalendar();
		}
	}

	this.hideCalendar = function(){
		calendar.classList.remove('visible');
	}

	this.showCalendar = function(){
		calendar.classList.add('visible');
		//I'm using "click" but it works with any event
	}

	this.onDateClick = function( clickFunction ){
		var activeDays = calendar.querySelectorAll( '.cal-month-days li' );

		for( var i=0; i < activeDays.length; i++ ){
			activeDays[i].addEventListener( 'click', clickFunction);
		}
	}

	this.getSelectedDate = function(){
		return selectedDate;
	}

	// shortcut for selecting element
	var el = function( selector, from ){
		var element = [];
		var parent = from || document;

		if( !selector ){ return; }

		if( selector.indexOf('#') > -1 ){
			element = [ parent.getElementById( selector.replace("#","") ) ];
		}else if( selector.indexOf('.') > -1 ){
			element = parent.getElementsByClassName(selector.replace(".",""));
		}else{
			element = parent.getElementsByTagName(selector);
		}
		return element;
	}

	// shortcut for creating element
	var create = function(tag){
		var newEl;

		if(tag){
			newEl = document.createElement(tag);
		}
		return newEl;
	}

	// verifies if variable is of type function
	var isFunction = function( varToCheck ) {
		var getType = {};
		return varToCheck && getType.toString.call(varToCheck) === '[object Function]';
	}

	// Turns date string into object Date()
	// parameter: string "yyyy/mm/dd"
	// returns: object Date()
	var parseDate = function(strDate){
		var date;
		var arrStrDate = strDate ? strDate.split("/") : null;

		if( arrStrDate && arrStrDate.length == 3 ){
			date = new Date( arrStrDate[2], arrStrDate[1], arrStrDate[0] );
		}
		return date;
	}

	// months
	monthNames["en-US"] = [
		"january",
		"february",
		"march",
		"april",
		"may",
		"june",
		"july",
		"august",
		"september",
		"october",
		"november",
		"december"
	];
	monthNames["pt-BR"] = [
		"janeiro",
		"fevereiro",
		"março",
		"abril",
		"maio",
		"junho",
		"julho",
		"agosto",
		"setembro",
		"outubro",
		"novembro",
		"dezembro"
	];

	// week days (abrev.)
	dayNames["en-US"] = [
		"S",
		"M",
		"T",
		"W",
		"T",
		"F",
		"S"
	];
	dayNames["pt-BR"] = [
		"D",
		"S",
		"T",
		"Q",
		"Q",
		"S",
		"S"
	];

	init();
}