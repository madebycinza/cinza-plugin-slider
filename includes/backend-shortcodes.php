<?php
	
add_action( 'init', 'cslider_shortcodes_init' );
function cslider_shortcodes_init() {
	add_shortcode( 'cinza_slider', 'cslider_shortcode' );
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Slider shortcode
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function cslider_shortcode( $atts = [], $content = null, $tag = 'cinza_slider' ) {

	// Enqueue scripts
    wp_enqueue_style('cslider-frontend-css');
	wp_enqueue_style('flickity-css');
	wp_enqueue_script('flickity-js');
	
    // Normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // Override default attributes with user attributes
    $cslider_atts = shortcode_atts(
        array(
            'id' => 'Empty',
        ), $atts, $tag
    );

	// Shortcode validation
	$slider_id = intval( $cslider_atts['id'] );
    if ( $slider_id == 'Empty' || !is_int($slider_id) ) return "Please enter a valid slider ID.";

    // Query: _cslider_options
	$cslider_options = get_post_meta($slider_id, '_cslider_options', true);
    $options = ' \'{ ';

        // Query validations
        if (intval($cslider_options['cslider_autoPlay']) > 0) $valid_autoPlay = '"autoPlay": '. $cslider_options['cslider_autoPlay'] .','; 
        else $valid_autoPlay = '"autoPlay": false,'; 

        if ($cslider_options['cslider_animation'] == "fade") {
            wp_enqueue_style('flickity-fade-css');
            wp_enqueue_script('flickity-fade-js');
            $valid_fade = '"fade": true,'; 
        } else {
            $valid_fade = '';
        }

        if (boolval($cslider_options['cslider_lazyLoad']) && ($cslider_options['cslider_animation'] != "fade")) {
            $valid_lazyLoad = '"lazyLoad": 2,'; // load images in selected slide, next 2 slides and previous 2 slides
            $img_load = 'data-flickity-lazyload';
        } else {
            $valid_lazyLoad = '"lazyLoad": false,'; 
            $img_load = 'src';
        }

        // Behavior
        $options .= '"draggable": ' . (boolval($cslider_options['cslider_draggable']) ? "true" : "false") . ',';
        $options .= '"freeScroll": ' . (boolval($cslider_options['cslider_freeScroll']) ? "true" : "false") . ',';
        $options .= '"wrapAround": ' . (boolval($cslider_options['cslider_wrapAround']) ? "true" : "false") . ',';
        $options .= '"groupCells": ' . $cslider_options['cslider_groupCells'] . ',';
        $options .= $valid_autoPlay;
        $options .= $valid_fade;
        $options .= '"pauseAutoPlayOnHover": ' . (boolval($cslider_options['cslider_pauseAutoPlayOnHover']) ? "true" : "false") . ',';
        $options .= '"adaptiveHeight": ' . (boolval($cslider_options['cslider_adaptiveHeight']) ? "true" : "false") . ',';
        $options .= '"watchCSS": ' . (boolval($cslider_options['cslider_watchCSS']) ? "true" : "false") . ',';
        $options .= '"dragThreshold": "' . $cslider_options['cslider_dragThreshold'] . '",';
        $options .= '"selectedAttraction": "' . $cslider_options['cslider_selectedAttraction'] . '",';
        $options .= '"friction": "' . $cslider_options['cslider_friction'] . '",';
        $options .= '"freeScrollFriction": "' . $cslider_options['cslider_freeScrollFriction'] . '",';
        
        // Images
        $options .= '"imagesLoaded": "true",';
        $options .= $valid_lazyLoad;

        // Setup
        $options .= '"cellSelector": ".carousel-cell",';
        $options .= '"initialIndex": 0,';
        $options .= '"accessibility": "true",';
        $options .= '"setGallerySize": ' . (boolval($cslider_options['cslider_setGallerySize']) ? "true" : "false") . ',';
        $options .= '"resize": ' . (boolval($cslider_options['cslider_resize']) ? "true" : "false") . ',';

        // Cell
        $options .= '"cellAlign": "' . $cslider_options['cslider_cellAlign'] . '",';
        $options .= '"contain": ' . (boolval($cslider_options['cslider_contain']) ? "true" : "false") . ',';
        $options .= '"percentPosition": ' . (boolval($cslider_options['cslider_percentPosition']) ? "true" : "false") . ',';

        // UI
        $options .= '"prevNextButtons": ' . (boolval($cslider_options['cslider_prevNextButtons']) ? "true" : "false") . ',';
        $options .= '"pageDots": ' . (boolval($cslider_options['cslider_pageDots']) ? "true" : "false");

    $options .= ' }\' ';

    // Query: _cslider_fields
    $cslider_fields = get_post_meta($slider_id, '_cslider_fields', true);
    $slides = '';
    foreach ( $cslider_fields as $field ) {
        $slides .= '<div class="carousel-cell">
                        <img class="carousel-cell-image" '. $img_load .'="'. $field['cslider_url'] .'" alt="'. $field['cslider_content'] .'" />
                        <div class="carousel-cell-content">'. $field['cslider_content'] .'</div>
                    </div>';
    }
 
    // Dynamic style 
    $cslider_style = "<style>";
    if (intval($cslider_options['cslider_minHeight']) > 0) $cslider_style .= ".carousel .flickity-viewport, .carousel .carousel-cell, .carousel .carousel-cell-image {min-height: ". $cslider_options['cslider_minHeight'] ."px;}";
    if (intval($cslider_options['cslider_maxHeight']) > 0) $cslider_style .= ".carousel .flickity-viewport, .carousel .carousel-cell, .carousel .carousel-cell-image {max-height: ". $cslider_options['cslider_maxHeight'] ."px;}";
    $cslider_style .= "</style>";

    // Output
    $o = '<div class="carousel" data-flickity='. $options .'>'. $slides .'</div>'. $cslider_style;

    return $o;
}