/*********************
Alterado em 12/02/2017
*********************/
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

var columns=0;
var lines=0;
var colWidth=60;
var linHeight=60;
var arrLines;
var arrBoard;
var currbox;
var selectedBox='';
var inputOnFocus='';
var direction="horizontal";
var currCol;
var currLine;
var arrClickedCell=[];
var arrCellPos=[];
var arrCells;
var activeCol;
var inputToChange;
var inputAllowed = true;
var clicking=false;
var clickTouchTimer;
var alertInterval;
var swipe = false;
var interactionType, interactionType2;
var arrTimer = [0,0,0];
var timeCounter = ["00","00","00"];
var calendar;

// local storage
var today = new Date();
var arrLocation = window.location.href.toString().split('/');
var dateFolder = arrLocation[arrLocation.length-3] || null;
var dayStr;

if(dateFolder.split("_").length == 3){
	dayStr = dateFolder;
}else{
	dayStr = today.getDay() + "_" + today.getMonth() + "_" + today.getYear();
}

$(document).ready(function(e) {
	if(isMobile==false){ $('#btZoom').addClass('hidden'); }
	$("body").noDoubleTapZoom();

	$(window).bind('resize', adjustLayout);
	$(window).bind('keydown', onActualKeyPress);

	share();

	$.get('dados/sudoku.txt?v='+today.getTime(), function(data) {

		showAlert();

		arrLines = data.split('\n');
		lines = arrLines.length;
		var splitLine;

		for(var i=0; i<lines; i++){

		   if(columns==0 || arrLines[i].length < columns){
			   columns = arrLines[i].length;
		   }
		}

		colWidth = ($('#puzzleInputs').innerWidth()-columns)/columns;
		linHeight = colWidth*0.96;
		$('#puzzleHolder img.crosswords').css({'width':((columns*colWidth)+2)+'px', 'height':((lines*linHeight)+2)+'px'});

		arrBoard = [];
		for(var i=0; i<lines; i++){
			var col=0;
			splitLine = arrLines[i].split("");

			arrBoard[i] = [];

			for(var j=0; j<columns; j++){
				if(splitLine[col]=="-"){
					arrBoard[i][j] = "";
					col++;
				}else if(splitLine[col+1] && splitLine[col+1]=="/"){
					arrBoard[i][j] = splitLine[col]+'/'+splitLine[col+2];
					col+=3;
				}else{
					arrBoard[i][j] = splitLine[col];
					col++;
				}
			}
		}

		generateBoard();

		calendar = new Calendar( {
				lang: 'pt-BR',
				enabledPeriod: ["2017/02/16", today.getFullYear()+"/"+(today.getMonth()+1)+"/"+today.getDate()],
				onDateClick: function( event ){

					if( !this.classList.contains( 'disabled' ) ){
						var date = normalizeNumberStr(event.target.innerHTML, 2);
						var month = normalizeNumberStr(calendar.getSelectedDate().getMonth()+1, 2);
						var year = normalizeNumberStr(calendar.getSelectedDate().getFullYear(), 2);
						var clickedDate = new Date( year, month-1, date, 23, 59 );
						var url;

						if( clickedDate.getTime() <= today.getTime() ){
							url = "https://infograficos.estadao.com.br/jogos/"+year+"_"+month+"_"+date+"/sudoku/";
							window.top.location = url;
						}
					}

				}
	 	});

		document.addEventListener('click', function(event) {
			var isInsideCalendar = calendar.element.contains(event.target);
			var isInsideBtn = document.getElementById('more-btn').contains(event.target);

			if ( !isInsideCalendar && !isInsideBtn ) {
				calendar.hideCalendar();
			}else{
				//console.log( 'inside' );
			}
		});

		$('#more-btn').on(interactionType, function(){
			calendar.toggleVisibility();
		});
	});

	$('*').bind('touchend', function(e) {
		e.stopPropagation();
	});

	$('*').bind('touchmove', function(e) {
		if(e.touches.length > 1){
			e.stopPropagation();
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


function showAlert(msg){
	var fadeDelay;

	if(msg){
		$('#alertBox #alertContent').html(msg);
	}
	fadeDelay = $('#alertBox #alertContent').html().length*60;

	$('#alertBox').addClass('visible');
	//$('#puzzleContainer').addClass('alert');
	$('#pubBanner').addClass('alert');

	clearInterval(alertInterval);
	alertInterval = setInterval(function(){
		//$('#puzzleContainer, .title h1').removeClass('alert');
		$('#pubBanner, .title h1').removeClass('alert');
		$('#alertBox').removeClass('visible');
	}, fadeDelay);
}

function btClearClick(event) {
	event.stopPropagation();

	localStorage.clear();

	$('.puzzleField').each(function(){
		$(this).html('');
	});
	$('.wrongAnswer').removeClass('wrongAnswer');
}

function btCheckClick(event){
	event.stopPropagation();

	var complete = true;
	var winHalfWidth = ($(window).width()/2);
	var winHalfHeight = ($('#puzzleHolder').height()/2);
	var alertHalfWidth;
	var alertHalfHeight;

	$('.puzzleField').each(function(){
		var currId = $(this).attr('id');
		//console.log('#answer-'+currId);
		$(this).removeClass('highlighted');
		$(this).removeClass('current');
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
}


function generateBoard(){
	var answerBoard='';
	var inputBoard='';
	var fakeInputBoard='';
	var splitCell;
	var firstClass;

	arrCells = [];
	arrCellPos = [];

	if(lines>0 && columns > 0){

		for(var i=0; i<lines; i++){
			arrCells[i] = [];
			firstClass = ' first';
			for(var j=0; j<columns; j++){
				if(j != 0){
					firstClass = '';
				}
				splitCell = arrBoard[i][j];

				answerBoard += '<div id="answer-l'+i+'c'+j+'" class="box'+firstClass+'">'+splitCell[0]+'</div>';

				if(splitCell!=''){
					inputBoard += '<div id="l'+i+'c'+j+'" class="puzzleField'+firstClass+'" maxlength="1" size="1"></div>'
					arrCells[i].push('l'+i+'c'+j);
				}else{
					inputBoard += '<div id="l'+i+'c'+j+'" class="box'+firstClass+'"></div>';
					arrCells[i].push('');
				}

			}
		}
	}

	$('#puzzleAnswers').html(answerBoard);
	$('#puzzleInputs').html(inputBoard);
	$('#puzzleFakeInputs').hide();

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

	if(this.supports_html5_storage()){
			initLocalStorage();
	}

	adjustLayout();

}

function onKeyPress(event){
	var inputValue;
	var inputId = inputOnFocus.attr('id');
	var splitId = inputId.split('-');
	var idExtra = '';
	var baseId;
	var idStr;
	var cSplitId;

	if(inputOnFocus){

		inputValue = $(this).text().substr(-1,1);

		if(splitId.length>1){
			idExtra = "-"+splitId[1];
		}

		baseId = (arrCells[arrCellPos[0]][arrCellPos[1]]);

		$('#'+baseId).html(inputValue);
		$('#'+baseId).removeClass('wrongAnswer');
		//$(this).attr('value', inputValue);


		if(direction=="horizontal"){
			if( $(this).hasClass('delete') == false ){//event.which != 0 &&
				if(arrCellPos[1] < arrCells[arrCellPos[0]].length-1){
					arrCellPos[1] = arrCellPos[1] + 1;
				}else{
					arrCellPos[1] = 0;
				}

				while( arrCells[arrCellPos[0]][arrCellPos[1]]=='' && arrCellPos[1] < arrCells[arrCellPos[0]].length-1 ){
					arrCellPos[1] = arrCellPos[1] + 1;
				}

			}else{
				if(arrCellPos[1] > 0){
					arrCellPos[1] = arrCellPos[1] - 1;
				}

				while( arrCells[arrCellPos[0]][arrCellPos[1]]=='' && arrCellPos[1] > 0 ){
					arrCellPos[1] = arrCellPos[1] - 1;
				}

			}
		}else{
			if( $(this).hasClass('delete') == false ){//event.which != 0 &&
				if(arrCellPos[0] < arrCells.length-1){
					arrCellPos[0] = arrCellPos[0] + 1;
				}else{
					arrCellPos[0] = 0;
				}

				while( arrCells[arrCellPos[0]][arrCellPos[1]]=='' && arrCellPos[0] < arrCells.length-1 ){
					arrCellPos[0] = arrCellPos[0] + 1;
				}

			}else{
				if(arrCellPos[0] > 0){
					arrCellPos[0] = arrCellPos[0] - 1;
				}

				while( arrCells[arrCellPos[0]][arrCellPos[1]]=='' && arrCellPos[0] > 0 ){
					arrCellPos[0] = arrCellPos[0] - 1;
				}

			}
		}

		localStorage.setItem(dayStr+'_sudoku_'+baseId, inputValue);

		baseId = (arrCells[arrCellPos[0]][arrCellPos[1]]).replace('f','');
		idStr = baseId.replace('-a','').replace('-b','');
		cSplitId = idStr.split('c');
		currCol = cSplitId[1];
		currLine = (cSplitId[0]).substr(1,cSplitId[0].length-1);

		$('.puzzleField').removeClass('current');
		$('#'+baseId).addClass('current');
		(inputOnFocus).css(getCellPosition(inputOnFocus, [currLine,currCol]));

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
		arrCell[0] = splitId[1];
		arrCell[1] = (splitId[0]).substr(1,splitId[0].length-1);

		$(this).css(getCellPosition($(this),arrCell));
	});
}

function getCellPosition(target, arrCell){
	if( target.attr('id').toString().indexOf('-b') > 0 ){
		return {'top':(Number(arrCell[1])*linHeight)+'px','left':(colWidth*0.5)+(Number(arrCell[0])*colWidth)+'px'};
	}else{
		return {'top':(Number(arrCell[1])*linHeight)+'px','left':(Number(arrCell[0])*colWidth)+'px'};
	}
}

function getFirstInputInCol(col){
	var cellFound;

	$('.puzzleField').each(function(){
		if(($(this).attr('id')).indexOf('c'+col) > -1){
			cellFound = $(this);
			return false;
		}

	});

	return getColAndLine(cellFound.attr('id'))[1];
}

function getFirstInputInLine(line){
	var cellFound;

	$('.puzzleField').each(function(){
		if(($(this).attr('id')).indexOf('l'+line) > -1){
			cellFound = $(this);
			return false;
		}
	});

	return getColAndLine(cellFound.attr('id'))[0];
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

function onPuzzleboxMove(event){
	swipe = true;
}

function onPuzzleboxRelease(event){
	if(!swipe){
		$("#keyboardContainer").addClass("open");
	}

	if(!swipe){

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

		baseId = (target.attr('id')).replace('f','');
		idStr = baseId.replace('-a','').replace('-b','');
		splitId = idStr.split('c');
		targetColumn = splitId[1];
		targetLine = (splitId[0]).substr(1,splitId[0].length-1);
		currCol = targetColumn;
		currLine = targetLine;

		arrClickedCell = [currCol,currLine];

		for(var i=0;i<arrCells.length;i++){
			if(arrCells[i].indexOf($(this).attr('id'))>-1){
				arrCellPos[0] = i;
				arrCellPos[1] = arrCells[i].indexOf($(this).attr('id'));
			}

		}

		if(selectedBox == baseId){
			direction="vertical";
			selectedBox = '';

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
	}
	swipe = false;
}

function onPuzzleboxClick(event){


}

function adjustLayout(event){
	colWidth = $('#puzzleInputs').innerWidth()/columns;
	linHeight = colWidth;

	$('#puzzleHolder img.crosswords').css({'width':((columns*colWidth))+'px', 'height':((lines*linHeight))+'px'});
	$('#puzzleHolder').height(((lines*linHeight)+70)+'px');

	$('.puzzleField, .box').width(colWidth);
	$('.puzzleField, .box').height(linHeight);
}

function onZoomTap(event){
	$('body').toggleClass('zoomed');
	$(this).toggleClass('zoomout');

	if(inputOnFocus && $('body').hasClass('zoomed')){//
		var diffX = ( $('#puzzleHolder').width() - $('#puzzleContainer').width() )/2;
		var newPosX = $(inputOnFocus).offset().left - diffX;
		var diffY = ( $('#puzzleHolder').height() - ($(window).height() - $('#keyboard').height()) )/2;
		var newPosY = $(inputOnFocus).position().top - diffY + 100;

		$('#page').scrollLeft(newPosX);
		$('#page').scrollTop(newPosY);
	}else{
		$('#page').scrollLeft(0);
		$('#page').scrollTop(0);
	}
}

var initLocalStorage = function() {
	$('.puzzleField').each(function(){
		if(localStorage[dayStr + '_sudoku_'+$(this).attr('id')]){
			$(this).html(localStorage.getItem(dayStr + '_sudoku_'+$(this).attr('id')));
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

/**************************/
// Helpers

var normalizeNumberStr = function( value, length ){
	str = value.toString();
	while( str.length < length ){
		str = "0" + str;
	}
	return str;
}

// adds share actions to buttons
function share(){
	$('#share-facebook').on("click", function(e) {
		  $(this).sharePopup(e, 'facebook');
	});

	$('#share-twitter').on("click", function(e) {
		  $(this).sharePopup(e, 'twitter');
	});
}


/**
   * Prevents default a event and makes a share popup
   *
   * @param  {[object]} e           [Mouse event]
   * @param  {[integer]} intWidth   [Popup width defalut 500]
   * @param  {[integer]} intHeight  [Popup height defalut 400]
   * @param  {[boolean]} blnResize  [Is popup resizeabel default true]
*/
$.fn.sharePopup = function (e, network, intWidth, intHeight, blnResize) {
  e.preventDefault();
	var urlToShare = $('meta[property="og:url"]').attr("content");
	var titleToShare = $('meta[property="og:title"]').attr("content");

	if(network == 'facebook'){
		urlToShare = "https://www.facebook.com/sharer.php?u="+urlToShare;
	}else if(network == 'twitter'){
		urlToShare = "https://twitter.com/share?url="+urlToShare;
	}

  intWidth = intWidth || '500';
  intHeight = intHeight || '400';
  strResize = (blnResize ? 'yes' : 'no');

  var strTitle = titleToShare,
      strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,
      objWindow = window.open(urlToShare, strTitle, strParam).focus();
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
