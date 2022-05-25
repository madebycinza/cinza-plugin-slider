jQuery(document).ready(function($) {

    // Set height on load
    $(".cinza-slider").css({ 'height' : 'auto', 'overflow' : 'visible', 'opacity' : '1' });

	var draggableDiv = $('#draggable').draggable(); 
	$('.slider-cell-content-inner', draggableDiv).mousedown(function(ev) {draggableDiv.draggable('disable'); }).mouseup(function(ev) {draggableDiv.draggable('enable'); }); 


});