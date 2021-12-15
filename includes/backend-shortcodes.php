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
    wp_enqueue_script('flickity-js');
	wp_enqueue_style('flickity-css');
    wp_enqueue_style('animate-css');
    wp_enqueue_style('cslider-frontend-css');
	
    // Normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
 
    // Override default attributes with user attributes
    $cslider_atts = shortcode_atts(
        array(
            'id' => 'Empty',
        ), $atts, $tag
    );
	$slider_id = intval( $cslider_atts['id'] );
    $cslider_options = get_post_meta($slider_id, '_cslider_options', true);

	// Shortcode validation
    if ( $slider_id == 'Empty' || !is_int($slider_id) || empty($cslider_options) ) {
        return "<p class='cslider-error'>Please enter a valid slider ID.</p>";
    }

    // Query: _cslider_options
    $options = ' \'{ ';

        // Query validations
        if (intval($cslider_options['cslider_autoPlay']) > 0) {
            $valid_autoPlay = '"autoPlay": '. $cslider_options['cslider_autoPlay'] .','; 
        } else {
            $valid_autoPlay = '"autoPlay": false,'; 
        }

        if ($cslider_options['cslider_animation'] == "fade") {
            wp_enqueue_style('flickity-fade-css');
            wp_enqueue_script('flickity-fade-js');
            $valid_fade = '"fade": true,'; 
        } else {
            $valid_fade = '';
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
        $options .= '"lazyLoad": "false",';

        // Setup
        $options .= '"cellSelector": ".slider-cell",';
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

    // Query: _cslider_static
    $cslider_static = get_post_meta($slider_id, '_cslider_static', true);
    $static = "";
    if(!empty($cslider_static['cslider_static_content'])) {
        $static .=  '<div class="static-cell"><div class="slider-cell-content">'. $cslider_static['cslider_static_content'] .'</div></div>';
    }

    // Query: _cslider_fields
    $cslider_fields = get_post_meta($slider_id, '_cslider_fields', true);
    $slides = '';
    foreach ( $cslider_fields as $field ) {
        $layer_link_target = '';
        if($field['cslider_link_target'] == 'new') {
            $layer_link_target = 'target="_blank"';
        }

        $layer_img = '';
        if(!empty($field['cslider_img_id'])) {
            //$layer_img = '<img class="slider-cell-image" '. src="'. $field['cslider_img'] .'" />';
            $layer_img = wp_get_attachment_image( intval($field['cslider_img_id']), 'full', "", ["class" => "slider-cell-image"] );
        }

        $layer_link = '';
        if(!empty($field['cslider_link'])) {
            $layer_link .= '<a href="'. $field['cslider_link'] .'" '. $layer_link_target .' class="slider-cell-link"></a>';
        }

        $layer_content = '';
        if(!empty($field['cslider_content'])) {
            $layer_content = '<div class="slider-cell-content"><div class="slider-cell-content-inner">'. $field['cslider_content'] .'</div>'. $layer_link .'</div>';
        } else {
            $layer_content = '<div class="slider-cell-content">'. $layer_link .'</div>';
        }

        $slides .=  '<div class="slider-cell">' . $layer_img . $layer_content . '</div>';
    }

    // Dynamic style 
    $style = "<style>";

    $style .=  ".cinza-slider-".$slider_id." {
                    height: ". ( ($cslider_options['cslider_minHeight'] + $cslider_options['cslider_maxHeight']) / 2) ."px; /* Temporary while it loads, removed with jQuery */
                    opacity: 0;
                    overflow: hidden; /* Temporary while it loads, removed with jQuery */
                }
                
                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
                    object-fit: ". $cslider_options['cslider_imgFit'] .";
                }";

    $dynamic_minHeight = 'auto';
    $dynamic_maxHeight = 'auto';
    if (intval($cslider_options['cslider_minHeight']) > 0) {$dynamic_minHeight = $cslider_options['cslider_minHeight'] ."px";}
    if (intval($cslider_options['cslider_maxHeight']) > 0) {$dynamic_maxHeight = $cslider_options['cslider_maxHeight'] ."px";}
    $style .=  ".cinza-slider-".$slider_id.", 
                .cinza-slider-".$slider_id." .flickity-viewport, 
                .cinza-slider-".$slider_id." .slider-cell, 
                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
                    min-height: ". $dynamic_minHeight .";
                    max-height: ". $dynamic_maxHeight .";
                }";

    if (intval($cslider_options['cslider_fullWidth']) > 0) {
        $style .=  ".cinza-slider-".$slider_id." {
                        width: 100vw;
                        position: relative;
                        left: 50%;
                        right: 50%;
                        margin-left: -50vw;
                        margin-right: -50vw;
                    }";
    }

    if(!empty($cslider_static['cslider_static_overlay'])) {
        $style .=  ".cinza-slider-".$slider_id." .slider-cell:after {
                        content: '';
                        position: absolute;
                        display: block;
                        top: 0;
                        bottom: 0;
                        width: 100%;
                        height: 100%;
                        background: ". $cslider_static['cslider_static_overlay'] .";
                        z-index: 1;
                    }";
    }
    $style .= "</style>";

    // Output
    $o = '<div class="cinza-slider cinza-slider-'.$slider_id.' animate__animated animate__fadeIn" data-flickity='. $options .'>'. $static . $slides .'</div>'. $style;
    return $o;
}