/*********************
Alterado em 02/02/2017
*********************/
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

var columns=0;
var lines=0;
var colWidth=67;
var linHeight=58;
var arrLines;
var arrBoard;
var selectedBox='';
var inputOnFocus='';
var direction="horizontal";
var currCol;
var currLine;
var arrClickedCell=[];
var inputToChange;
var inputAllowed = true;
var inputTimeout;
var clicking = false;
var clickTouchTimer;
var alertInterval;
var swipe = false;
var interactionType, interactionType2;
var arrTimer = [0,0,0];
var timeCounter = ["00","00","00"];

// local storage
var today = new Date();
var day = today.getDay() + "_" + today.getMonth() + "_" + today.getYear();


$(document).ready(function(e) {
	if(isMobile==false){ $('#btZoom').addClass('hidden'); }
	$("body").noDoubleTapZoom();

	$(window).bind('resize', adjustLayout);
	$(window).bind('keydown', onActualKeyPress);

	$.get('dados/cruzada.txt', function(data) {

		showAlert();

		arrLines = data.split('\n');
		lines = arrLines.length;
		var splitLine;

		for(var i=0; i<lines; i++){
		   arrLines[i] = arrLines[i].replace(/[\r\n]/g, "");
		   if(arrLines[i].substr(-1,1)==" "){
			   arrLines[i] = arrLines[i].substr(0,columns-1);
		   }
		   if(columns==0 || arrLines[i].length < columns){
			   columns = arrLines[i].length;
		   }
		}

		colWidth = ($('#puzzleInputs').innerWidth()-columns)/columns;
		linHeight = colWidth*0.87;
		$('#puzzleHolder img.crosswords').css({'width':((columns*colWidth))+'px', 'height':((lines*linHeight))+'px'});
		// $('body').height(((lines*linHeight)+2) + 300);

		arrBoard = [];
		for(var i=0; i<lines; i++){
			var col=0;
			arrLines[i] = arrLines[i].replace(/[\r\n]/g, "");
			splitLine = arrLines[i].split("");

			arrBoard[i] = [];
			for(var j=0; j<columns; j++){
				if(splitLine[col]=="-"){
					arrBoard[i][j] = "";
					col++;
				}else if(splitLine[col+1] && splitLine[col+2] && splitLine[col+1]=="/"){
					arrBoard[i][j] = splitLine[col]+'/'+splitLine[col+2];
					col+=3;
				}else if(splitLine[col+1] && splitLine[col+2] && splitLine[col+1]=="\\"){
					arrBoard[i][j] = splitLine[col]+'\\'+splitLine[col+2];
					col+=3;
				}else{
					arrBoard[i][j] = splitLine[col];
					col++;
				}
			}
		}
		generateBoard();
	});

	$('*').bind('touchend', function(e) {
	  e.preventDefault();
	});

	$('*').bind('touchmove', function(e) {
		if(e.touches.length > 1){
			e.preventDefault();
			showAlert("Use o botão de lupa para ampliar.");
		}
	});

	initTimer();

});

function initTimer(){
	timer = setInterval(timeTick, 1000);
}

function timeTick(event){
	// arrTimer = [hours, minutes, seconds];

	if(arrTimer[0] >= 99){
		clearInterval(timer);
		return;
	}

	if(arrTimer[1] >= 59){
		arrTimer[0]++; // hours+1
		arrTimer[1] = 0; // minutes=0
		arrTimer[2] = 0; // seconds=0
	}else{
		if(arrTimer[2] >= 59){
			arrTimer[1]++; // minutes+1
			arrTimer[2] = 0;
		}else{
			arrTimer[2]++; // seconds+1
		}
	}

	timeCounter[0] = arrTimer[0].toString();
	timeCounter[1] = arrTimer[1].toString();
	timeCounter[2] = arrTimer[2].toString();

	for(var i=0;i<3;i++){
		if(timeCounter[i].length < 2){
			timeCounter[i] = "0" + timeCounter[i];
		}
	}

	$('#timeCounter span').html(timeCounter[0]+":"+timeCounter[1]+":"+timeCounter[2]);
}

function adjustLayout(event){
	colWidth = $('#puzzleInputs').width()/columns;
	linHeight = colWidth*0.87;

	$('#puzzleHolder img.crosswords').css({'width':((columns*colWidth)+2)+'px', 'height':((lines*linHeight)+2)+'px'});
	$('#puzzleHolder').height(((lines*linHeight)+70)+'px');
	$('.puzzleField, .box').width(colWidth);
	$('.puzzleField, .box').height(linHeight);
	$('.puzzleField.half2').css({ width:colWidth/2, height:linHeight, paddingTop: linHeight/3});
	$('.puzzleField.half1').css({ width:colWidth/2, height:linHeight, paddingBottom: linHeight/3});
}

function btClearClick(event) {

	localStorage.clear();

	$('.puzzleField').each(function(){
		$(this).html('');
	});

	$('.wrongAnswer').removeClass('wrongAnswer');
};

function btCheckClick(event){
	var complete = true;
	var winHalfWidth = ($(window).width()/2);
	var winHalfHeight = ($('#puzzleHolder').height()/2);
	var alertHalfWidth;
	var alertHalfHeight;
	var typedText = '';

	$('.puzzleField').each(function(){
		var currId = $(this).attr('id');
		$(this).removeClass('highlighted');
		$(this).removeClass('current');

		typedText += $(this).html();

		if($(this).html() != $('#answer-'+currId).html()){
			$(this).addClass('wrongAnswer');
			complete = false;
		}
	});

	if(complete){
		clearInterval(timer);
		showAlert('Você acertou todas as respostas!');
	}else{
		$('#alertBox #alertContent').html();
		showAlert('Respostas incorretas estão destacadas na cor amarela');
	}

	if( typedText.toLowerCase().indexOf('jedirdead') > -1 ){
		showAlert('"I find your lack of faith disturbing."');
	}
}

function showAlert(msg){
	var fadeDelay;

	if(msg){
		$('#alertBox #alertContent').html(msg);
	}
	fadeDelay = $('#alertBox #alertContent').html().length*60;

	$('#alertBox').addClass('visible');
	$('#puzzleContainer').addClass('alert');

	clearInterval(alertInterval);
	alertInterval = setInterval(function(){
		$('#puzzleContainer, .title h1').removeClass('alert');
		$('#alertBox').removeClass('visible');
	}, fadeDelay);
}

function generateBoard(){
	var answerBoard='';
	var inputBoard='';
	var fakeInputBoard='';
	var splitCell;
	var splitCell2;
	var firstClass;

	if(lines>0 && columns > 0){

		for(var i=0; i<lines; i++){
			firstClass = ' first';

			for(var j=0; j<columns; j++){
				if(j != 0){
					firstClass = '';
				}

				splitCell = arrBoard[i][j].split('/');
				splitCell2 = arrBoard[i][j].split('\\');

				if(splitCell.length == 1 && splitCell2.length == 1){
					answerBoard += '<div id="answer-l'+i+'c'+j+'" class="box'+firstClass+'">'+splitCell[0]+'</div>';

					if(splitCell[0]!=''){
						inputBoard += '<div id="l'+i+'c'+j+'" class="line'+i+' col'+j+' puzzleField'+firstClass+'"></div>'
					}else{
						inputBoard += '<div id="l'+i+'c'+j+'" class="box'+firstClass+'"></div>';
					}

				}else{
					if(splitCell.length > 1){
						answerBoard += '<div id="answer-l'+i+'c'+j+'-a" class="box half1'+firstClass+'">'+splitCell[0]+'</div>';
						answerBoard += '<div id="answer-l'+i+'c'+j+'-b" class="box half2">'+splitCell[1]+'</div>';

						if(splitCell[0]!=''){
							inputBoard += '<div id="l'+i+'c'+j+'-a" class="line'+i+' col'+j+' puzzleField half1'+firstClass+'"></div>';
							inputBoard += '<div id="l'+i+'c'+j+'-b" class="line'+i+' col'+j+' puzzleField half2"></div>';

						}else{
							inputBoard += '<div id="l'+i+'c'+j+'" class="box'+firstClass+'"></div>';

						}
					}else if( splitCell2.length > 1){
						answerBoard += '<div id="answer-l'+i+'c'+j+'-a" class="box half1">'+splitCell2[0]+'</div>';
						answerBoard += '<div id="answer-l'+i+'c'+j+'-b" class="box half2">'+splitCell2[1]+'</div>';

						if(splitCell[0]!=''){
							inputBoard += '<div id="l'+i+'c'+j+'-a" class="line'+i+' col'+j+' puzzleField half2'+firstClass+'"></div>';
							inputBoard += '<div id="l'+i+'c'+j+'-b" class="line'+i+' col'+j+' puzzleField half1"></div>';

						}else{
							inputBoard += '<div id="l'+i+'c'+j+'" class="box'+firstClass+'"></div>';

						}
					}
				}
			}
		}
	}

	$('#puzzleAnswers').html(answerBoard);
	$('#puzzleInputs').html(inputBoard);
	$('#puzzleFakeInputs').hide();
	adjustLayout();

	interactionType = "touchstart";
	interactionType2 = "touchend";

	if(isMobile==false){
		interactionType = "click";
		interactionType2 = "mouseup";
	}


	$('.puzzleField').on("touchmove", onPuzzleboxMove);
	$('.puzzleField').on(interactionType2, onPuzzleboxRelease);
	//$('.puzzleField').on(interactionType, onPuzzleboxClick);
	$('li.key').on(interactionType, onKeyPress);
	$('li.delete').on(interactionType, onKeyPress);
	$('li.hide').on(interactionType, function(event){
		$("#keyboardContainer").removeClass("open");
	});
	$('#btCheck').on(interactionType, btCheckClick);
	$('#btClear').on(interactionType, btClearClick);
	$('#btZoom').on(interactionType, onZoomTap);

	$('.puzzleField, .box').width(colWidth);
	$('.puzzleField, .box').height(linHeight);

	$('.puzzleField.half2').css({ width:colWidth/2, height:linHeight, paddingTop: linHeight/3});
	$('.puzzleField.half1').css({ width:colWidth/2, height:linHeight, paddingBottom: linHeight/3});

	if(this.supports_html5_storage()){
			initLocalStorage();
	}
	adjustLayout();
}

function onKeyPress(event){
	var inputValue = '';
	var inputId;
	var splitId;
	var idExtra = '';
	var baseId;
	var idStr;
	var cSplitId;
	var test;

	if(inputAllowed && inputOnFocus){

		$('#keyboardContainer').addClass("open");

		inputAllowed = false;
		clearTimeout(inputTimeout);
		inputTimeout = setTimeout(function(){
			inputAllowed = true;
		},20);

		inputId = inputOnFocus.attr('id');
		splitId = inputId.split('-');
		inputValue = $(this).text().substr(-1,1).toUpperCase();

		if( splitId.length > 1 ){
			idExtra = "-"+splitId[1];
		}

		baseId = inputToChange.replace('f','');

		if(direction=="horizontal"){

			if( $(this).hasClass('delete') == false ){// event.which != 8
				nextCell = $("#"+inputToChange).nextAll(".line"+currLine).eq(0).attr("id");
			} else {
				nextCell = $("#"+inputToChange).prevAll(".line"+currLine).eq(0).attr("id");
				inputValue = ' ';
			}

		}else{

			if( $(this).hasClass('delete') == false ){
				nextCell = $("#"+inputToChange).nextAll(".col"+currCol).eq(0).attr("id");
			} else{
				nextCell = $("#"+inputToChange).prevAll(".col"+currCol).eq(0).attr("id");
				inputValue = ' ';
			}
		}

		$('#'+baseId).html(inputValue);
		$('#'+baseId).removeClass('wrongAnswer');

		localStorage.setItem(day+'_crosswords_'+baseId, inputValue);

		if( nextCell ){
			inputToChange = nextCell;
			baseId = (nextCell).replace('f','');
			idStr = baseId.replace('-a','').replace('-b','');
			cSplitId = idStr.split('c');
			currCol = cSplitId[1];
			currLine = (cSplitId[0]).substr(1,cSplitId[0].length-1);

			$('.puzzleField').removeClass('current');
			$('#'+baseId).addClass('current');
		}

	}

}

function onActualKeyPress(e){
	var char;
	var key = e.which || e.keyCode || e.charCode;

	if( key == 8 || key == 46){
		$(".delete").trigger("click");
	}else if(key !== 0 && !e.ctrlKey && !e.metaKey && !e.altKey ){
		char = String.fromCharCode(key).toLowerCase();

		if(char!="" && $("li.l"+char).length > 0){
			//console.log(key, char);
			$("li.l"+char).trigger("click");
		}
	}

}

function rearrangeFields(){
	var tBaseId;
	var idStr;
	var splitId;
	var arrCell = [];

	$('.puzzleField').each(function(){
		tBaseId = ($(this).attr('id')).replace('f','');
		idStr = tBaseId.replace('-a','').replace('-b','');
		splitId = idStr.split('c');
	});
}


function getColAndLine(str){
	var baseId;
	var idStr;
	var splitId;
	var arrCell=[];

	baseId = (str).replace('f','');
	idStr = baseId.replace('-a','').replace('-b','');
	splitId = idStr.split('c');
	arrCell[0] = splitId[1];
	arrCell[1] = (splitId[0]).substr(1,splitId[0].length-1);

	return arrCell;
}

function onZoomTap(event){
	$('body').toggleClass('zoomed');
	$(this).toggleClass('zoomout');

	if(inputOnFocus && $('body').hasClass('zoomed')){//
		var diffX = ( $('#puzzleHolder').width() - $('#puzzleContainer').width() )/2;
		var newPosX = $(inputOnFocus).offset().left - diffX;
		var diffY = ( $('#puzzleHolder').height() - ($(window).height() - $('#keyboard').height()) )/2;
		var newPosY = $(inputOnFocus).position().top - diffY + 100;

		//console.log( newPosX, newPosY );

		$('#page').scrollLeft(newPosX);
		$('#page').scrollTop(newPosY);
	}else{
		$('#page').scrollLeft(0);
		$('#page').scrollTop(0);
	}
}

function onPuzzleboxMove(event){
	swipe = true;
}

function onPuzzleboxRelease(event){
	if(!swipe){
		$("#keyboardContainer").addClass("open");
	}

	if(!swipe && clicking == false){
		clicking = true;

		showKeyboard = true;
		hideKeyboard = false;

		var target = $(this);
		var tBaseId;
		var baseId;
		var idStr;
		var splitId;
		var targetColumn;
		var thisColumn;
		var currColumnStr;
		var targetLine;
		var thisLine;
		var currLineStr;

		inputToChange = $(this).attr('id');
		inputOnFocus = $(this);

		rearrangeFields();

		baseId = ($(this).attr('id')).replace('f','');
		idStr = baseId.replace('-a','').replace('-b','');
		splitId = idStr.split('c');
		targetColumn = splitId[1];
		targetLine = (splitId[0]).substr(1,splitId[0].length-1);
		currCol = targetColumn;
		currLine = targetLine;

		arrClickedCell = [currCol,currLine];

		if(selectedBox == baseId){
			direction="vertical";
			selectedBox = '';
			//activeCol = Number(arrCellPos[1]);

			$('.puzzleField').each(function(){
				tBaseId = ($(this).attr('id')).replace('f','');
				idStr = tBaseId.replace('-a','').replace('-b','');
				splitId = idStr.split('c');
				thisColumn = splitId[1];

				$('#'+tBaseId).removeClass('current');
				//$('#'+baseId).attr('tabIndex', thisColumn);

				if(targetColumn==thisColumn){
					$('#'+tBaseId).addClass('highlighted');
				}else{
					$('#'+tBaseId).removeClass('highlighted');
				}
			});

		}else{

			direction="horizontal";
			selectedBox = baseId;

			$('.puzzleField').each(function(){
				tBaseId = ($(this).attr('id')).replace('f','');
				idStr = tBaseId.replace('-a','').replace('-b','');
				splitId = idStr.split('c');
				thisLine = (splitId[0]).substr(1,splitId[0].length-1);

				$('#'+tBaseId).removeClass('current');
				if(targetLine==thisLine){
					$('#'+tBaseId).addClass('highlighted');
				}else{
					$('#'+tBaseId).removeClass('highlighted');
				}
			});
		}

		$('#'+baseId).addClass('current');

		clickTouchTimer = setTimeout(function(){
			clicking = false;
		}, 20);
	}
	swipe = false;
}

var initLocalStorage = function() {
	$('.puzzleField').each(function(){
		if(localStorage[day + '_crosswords_'+$(this).attr('id')]){
			$(this).html(localStorage.getItem(day + '_crosswords_' + $(this).attr('id')));
		}
	});
}

var supports_html5_storage = function() {
	try {
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}

var count = 0;
$.fn.noDoubleTapZoom = function() {
    $(this).bind('touchstart', function preventZoom(e){
        var t2 = e.timeStamp;
        var t1 = $(this).data('lastTouch') || t2;
        var dt = t2 - t1;
        var fingers = e.originalEvent.touches.length;
        $(this).data('lastTouch', t2);
        if (!dt || dt > 500 || fingers > 1){
            return; // not double-tap
        }
        e.preventDefault(); // double tap - prevent the zoom
        // also synthesize click events we just swallowed up
        $(e.target).trigger('click');
    });
};
