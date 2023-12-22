jQuery(document).ready(function($) {

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Set height on load
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $(".cinza-slider").css({ 'height' : 'auto', 'overflow' : 'visible', 'opacity' : '1' });
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Accessibility improvements
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	$('.cinza-slider.rfAccessibility').each(function() {
		var $this = $(this);

	    if ( $(this).attr('data-flickity').includes('"prevNextButtons": true') ) {
		    // First, we check of the arrows are enabled
		    // If they are, we will focus on them and ignore the dots
	        $this.find(".flickity-prev-next-button").attr('tabindex', 0);
	        $this.find(".flickity-prev-next-button").addClass('focusable');
	        $this.find(".flickity-page-dots").attr('tabindex', -1);    
	    }
	    else if ( $(this).attr('data-flickity').includes('"pageDots": true') ) {
		    // Second, since the arrows are disabled, we check if the dots are enabled
		    // If they are, we will focus on them
	        $this.find(".flickity-page-dots").attr('tabindex', 0);
	        $this.find(".flickity-page-dots").addClass('focusable');
	    }
	});
	
    // Either way, we want to ignore the link from cells that are not active
	$(".slider-cell:not(.is-selected) .slider-cell-content-inner a").attr('tabindex', -1);
	$(".slider-cell:not(.is-selected) a.slider-cell-link").attr('tabindex', -1);
	$(".slider-cell.is-selected .slider-cell-content-inner a").attr('tabindex', 0);
	$(".slider-cell.is-selected a.slider-cell-link").attr('tabindex', 0);
					
	$(".cinza-slider *").click(function() {
		$(".slider-cell:not(.is-selected) .slider-cell-content-inner a").attr('tabindex', -1);
		$(".slider-cell:not(.is-selected) a.slider-cell-link").attr('tabindex', -1);
		$(".slider-cell.is-selected .slider-cell-content-inner a").attr('tabindex', 0);
		$(".slider-cell.is-selected a.slider-cell-link").attr('tabindex', 0);
	});
	
	$(".cinza-slider").keydown(function() {
		$(".slider-cell:not(.is-selected) .slider-cell-content-inner a").attr('tabindex', -1);
		$(".slider-cell:not(.is-selected) a.slider-cell-link").attr('tabindex', -1);
		$(".slider-cell.is-selected .slider-cell-content-inner a").attr('tabindex', 0);
		$(".slider-cell.is-selected a.slider-cell-link").attr('tabindex', 0);
	});	


});