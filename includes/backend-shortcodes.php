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
	wp_enqueue_script('react');
	wp_enqueue_script('react-dom');
	//wp_enqueue_script('flickity-css');
	//wp_enqueue_script('flickity-js');
	//wp_enqueue_script('cinza-slider');
	include_once( CSLIDER_PATH . 'components/CinzaSliderDEV.php' );
	
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
 
    // Output
    $o = '<div id="cslider-container" class="cslider-'. esc_html__($slider_id) .'"></div>';
    	
    return $o;
}