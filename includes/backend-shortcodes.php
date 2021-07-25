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
    
	// Validation
	$slider_id = intval( $cslider_atts['id'] );
    if ( $slider_id == 'Empty' || !is_int($slider_id) ) return "Please enter a valid slider ID.";

    // Query
	$cslider_fields = get_post_meta($slider_id, '_cslider_fields', true);
	$i = 0;
 
    // Output
    $o = '<div class="carousel" data-flickity=\'{ 
            "cellAlign": "left", 
            "autoPlay": 5000,
            "contain": true,
            "wrapAround": true
        }\'>';
	foreach ( $cslider_fields as $field ) {
        $o .= '<div class="carousel-cell" style="background-image:url('. $field['url'] .')">'. $field['name'] .'</div>';
		$i++;
	}
    $o .= '</div>';
    	
    return $o;
}