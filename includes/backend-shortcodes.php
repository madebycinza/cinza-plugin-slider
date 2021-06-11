<?php
	
add_action( 'init', 'cslider_shortcodes_init' );
function cslider_shortcodes_init() {
	add_shortcode( 'cinza_slider', 'cslider_shortcode' );
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Slider shortcode
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function cslider_shortcode( $atts = [], $content = null, $tag = 'cinza_slider' ) {
	
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
    if ( $slider_id == 'Empty' || !is_int($slider_id) ) return "Please enter a valid slider ID." . gettype($slider_id);
 
    // Output
	$slider_post = get_post( $slider_id ); 
	$slider_title = $slider_post->post_title;
    
    $o = '';
    $o .= '<h3>'. $slider_title .'</h3>';
    $o .= '<div class="cslider-container cslider-'. esc_html__($slider_id) .'"></div>';
 
    return $o;
}