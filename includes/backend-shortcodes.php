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
    wp_enqueue_style('flickity-fade-css');
	wp_enqueue_script('flickity-js');
    wp_enqueue_script('flickity-fade-js');
	
    // Normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // Override default attributes with user attributes
    $cslider_atts = shortcode_atts(
        array(
            'id' => 'Empty',
        ), $atts, $tag
    );
    
	// Validation
	$slider_id = intval( $cslider_atts['id'] );
    if ( $slider_id == 'Empty' || !is_int($slider_id) ) return "Please enter a valid slider ID.";

    // Query: _cslider_options
	$cslider_options = get_post_meta($slider_id, '_cslider_options', true);
    $options = ' \'{ ';
        $options .= '"prevNextButtons": '. (boolval($cslider_options['cslider_prevNextButtons']) ? 'true' : 'false') . ',';
        $options .= '"pageDots": '. (boolval($cslider_options['cslider_pageDots']) ? 'true' : 'false') . ',';
        $options .= '"fade": true';
    $options .= ' }\' ';

    // For ttoubleshooting
    echo 'cslider_prevNextButtons: '. (boolval($cslider_options['cslider_prevNextButtons']) ? 'true' : 'false');
    echo '<br />cslider_pageDots: '. (boolval($cslider_options['cslider_pageDots']) ? 'true' : 'false');

    // Query: _cslider_fields
    $cslider_fields = get_post_meta($slider_id, '_cslider_fields', true);
    $slides = '';
    foreach ( $cslider_fields as $field ) {
        $slides .= '<div class="carousel-cell" style="background-image:url('. $field['cslider_url'] .')">'. $field['name'] .'</div>';
    }
 
    // Output
    $o = '<div class="carousel" data-flickity='. $options .'>'. $slides .'</div>';
    	
    return $o;
}