<?php
	
add_action( 'init', 'cslider_shortcodes_init' );
function cslider_shortcodes_init() {
	add_shortcode( 'cinzaslider', 'cslider_shortcode' ); // Main
	add_shortcode( 'cinza_slider', 'cslider_shortcode' ); // Fallback
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Slider shortcode
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
function cslider_shortcode( $atts = [], $content = null, $tag = 'cinzaslider' ) {

	// Enqueue scripts
    wp_enqueue_script('flickity');
    wp_enqueue_script('cslider-frontend');
	wp_enqueue_style('flickity');
    wp_enqueue_style('animate');
    wp_enqueue_style('cslider-frontend');
	
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
    if ( $slider_id == 'Empty' || !is_int($slider_id) ||  empty($cslider_options) ) {
	    return "<p class='cslider-error'>ERROR: Please enter a valid Cinza Slider ID.</p>";
    } else if ( get_post_status_object( get_post_status($slider_id) )->label == 'Draft' ) {
	    return "<p class='cslider-error'>ERROR: This Cinza Slider is not published yet.</p>";
    }
    
    // Get setting values with validation
	$cslider_minHeight = isset($cslider_options['cslider_minHeight']) ? esc_attr($cslider_options['cslider_minHeight']) : '300';
	$cslider_maxHeight = isset($cslider_options['cslider_maxHeight']) ? esc_attr($cslider_options['cslider_maxHeight']) : '500';
	$cslider_fullWidth = isset($cslider_options['cslider_fullWidth']) ? esc_attr($cslider_options['cslider_fullWidth']) : '0';
	$cslider_setGallerySize = isset($cslider_options['cslider_setGallerySize']) ? esc_attr($cslider_options['cslider_setGallerySize']) : '1';
	$cslider_adaptiveHeight = isset($cslider_options['cslider_adaptiveHeight']) ? esc_attr($cslider_options['cslider_adaptiveHeight']) : '1';
	
	$cslider_prevNextButtons = isset($cslider_options['cslider_prevNextButtons']) ? esc_attr($cslider_options['cslider_prevNextButtons']) : '1';
	$cslider_pageDots = isset($cslider_options['cslider_pageDots']) ? esc_attr($cslider_options['cslider_pageDots']) : '1';
	$cslider_draggable = isset($cslider_options['cslider_draggable']) ? esc_attr($cslider_options['cslider_draggable']) : '1';
	$cslider_hash = isset($cslider_options['cslider_hash']) ? esc_attr($cslider_options['cslider_hash']) : '0';
	$cslider_mfAccessibility = isset($cslider_options['cslider_mfAccessibility']) ? esc_attr($cslider_options['cslider_mfAccessibility']) : '0';
	$cslider_rfAccessibility = isset($cslider_options['cslider_rfAccessibility']) ? esc_attr($cslider_options['cslider_rfAccessibility']) : '0';
	
	$cslider_animation = isset($cslider_options['cslider_animation']) ? esc_attr($cslider_options['cslider_animation']) : 'slide';
	$cslider_autoPlay = isset($cslider_options['cslider_autoPlay']) ? esc_attr($cslider_options['cslider_autoPlay']) : '0';
	$cslider_pauseAutoPlayOnHover = isset($cslider_options['cslider_pauseAutoPlayOnHover']) ? esc_attr($cslider_options['cslider_pauseAutoPlayOnHover']) : '1';
	$cslider_wrapAround = isset($cslider_options['cslider_wrapAround']) ? esc_attr($cslider_options['cslider_wrapAround']) : '1';
	$cslider_freeScroll = isset($cslider_options['cslider_freeScroll']) ? esc_attr($cslider_options['cslider_freeScroll']) : '0';
	
	$cslider_groupCells = isset($cslider_options['cslider_groupCells']) ? esc_attr($cslider_options['cslider_groupCells']) : '1';
	$cslider_cellAlign = isset($cslider_options['cslider_cellAlign']) ? esc_attr($cslider_options['cslider_cellAlign']) : 'left';
	$cslider_imgFit = isset($cslider_options['cslider_imgFit']) ? esc_attr($cslider_options['cslider_imgFit']) : 'cover';
	$cslider_resize = isset($cslider_options['cslider_resize']) ? esc_attr($cslider_options['cslider_resize']) : '1';
	$cslider_contain = isset($cslider_options['cslider_contain']) ? esc_attr($cslider_options['cslider_contain']) : '1';
	$cslider_percentPosition = isset($cslider_options['cslider_percentPosition']) ? esc_attr($cslider_options['cslider_percentPosition']) : '1';
	
	$cslider_lazyLoad = isset($cslider_options['cslider_lazyLoad']) ? esc_attr($cslider_options['cslider_lazyLoad']) : '0';
	$cslider_watchCSS = isset($cslider_options['cslider_watchCSS']) ? esc_attr($cslider_options['cslider_watchCSS']) : '0';
	$cslider_dragThreshold = isset($cslider_options['cslider_dragThreshold']) ? esc_attr($cslider_options['cslider_dragThreshold']) : '3';
	$cslider_selectedAttraction = isset($cslider_options['cslider_selectedAttraction']) ? esc_attr($cslider_options['cslider_selectedAttraction']) : '0.025';
	$cslider_friction = isset($cslider_options['cslider_friction']) ? esc_attr($cslider_options['cslider_friction']) : '0.28';
	$cslider_freeScrollFriction = isset($cslider_options['cslider_freeScrollFriction']) ? esc_attr($cslider_options['cslider_freeScrollFriction']) : '0.075';

    // Query: _cslider_options
    $options = ' \'{ ';

        // Query validations
        if (intval($cslider_autoPlay) > 0) {
            $valid_autoPlay = '"autoPlay": '. $cslider_autoPlay .','; 
        } else {
            $valid_autoPlay = '"autoPlay": false,'; 
        }

        if ($cslider_animation == "fade") {
            wp_enqueue_style('flickity-fade');
            wp_enqueue_script('flickity-fade');
            $valid_fade = '"fade": true,'; 
        } else {
            $valid_fade = '';
        }
        
        if (boolval($cslider_hash)) {
            wp_enqueue_script('flickity-hash');
        }

        // Behavior
        $options .= '"draggable": ' . (boolval($cslider_draggable) ? "true" : "false") . ',';
        $options .= '"hash": ' . (boolval($cslider_hash) ? "true" : "false") . ',';
        $options .= '"freeScroll": ' . (boolval($cslider_freeScroll) ? "true" : "false") . ',';
        $options .= '"wrapAround": ' . (boolval($cslider_wrapAround) ? "true" : "false") . ',';
        $options .= '"groupCells": ' . $cslider_groupCells . ',';
        $options .= $valid_autoPlay;
        $options .= $valid_fade;
        $options .= '"pauseAutoPlayOnHover": ' . (boolval($cslider_pauseAutoPlayOnHover) ? "true" : "false") . ',';
        $options .= '"adaptiveHeight": ' . (boolval($cslider_adaptiveHeight) ? "true" : "false") . ',';
        $options .= '"watchCSS": ' . (boolval($cslider_watchCSS) ? "true" : "false") . ',';
        $options .= '"dragThreshold": "' . $cslider_dragThreshold . '",';
        $options .= '"selectedAttraction": "' . $cslider_selectedAttraction . '",';
        $options .= '"friction": "' . $cslider_friction . '",';
        $options .= '"freeScrollFriction": "' . $cslider_freeScrollFriction . '",';
        
        // Images
        $options .= '"imagesLoaded": "true",';
		$options .= '"lazyLoad": ' . (boolval($cslider_lazyLoad) ? "true" : "false") . ',';

        // Setup
        $options .= '"cellSelector": ".slider-cell",';
        $options .= '"initialIndex": 0,';
        $options .= '"setGallerySize": ' . (boolval($cslider_setGallerySize) ? "true" : "false") . ',';
        $options .= '"resize": ' . (boolval($cslider_resize) ? "true" : "false") . ',';

        // Cell
        $options .= '"cellAlign": "' . $cslider_cellAlign . '",';
        $options .= '"contain": ' . (boolval($cslider_contain) ? "true" : "false") . ',';
        $options .= '"percentPosition": ' . (boolval($cslider_percentPosition) ? "true" : "false") . ',';

        // UI
        $options .= '"prevNextButtons": ' . (boolval($cslider_prevNextButtons) ? "true" : "false") . ',';
        $options .= '"pageDots": ' . (boolval($cslider_pageDots) ? "true" : "false") . ',';
        $options .= '"accessibility": ' . (boolval($cslider_mfAccessibility) ? "true" : "false");

    $options .= ' }\' ';

    // Query: _cslider_static
    $cslider_static = get_post_meta($slider_id, '_cslider_static', true);
    $static = "";
    if(!empty($cslider_static['cslider_static_content'])) {
        $static .=  '<div class="static-cell">
			        	<div class="slider-cell-content">
			        		<div class="slider-cell-content-inner">
			        			'. $cslider_static['cslider_static_content'] .'
			        		</div>
			        	</div>
			        </div>';
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
	        
			$img_id = intval($field['cslider_img_id']);
			$img_url = wp_get_attachment_image_url($img_id, 'full');
			$img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
	        
            if(boolval($cslider_lazyLoad)) {
	            $layer_img = "<img class='slider-cell-image cs-lazyLoad-on' data-flickity-lazyload='{$img_url}' alt='{$img_alt}' />";
            } else {
	            $layer_img = "<img class='slider-cell-image cs-lazyLoad-off' src='{$img_url}' alt='{$img_alt}' />";
            }
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

		$existing_cell_id = '';
		if(isset($field['cslider_cell_id'])) {
			$existing_cell_id = $field['cslider_cell_id'];	
		}
        $slides .=  '<div id="'. $existing_cell_id .'" class="slider-cell">' . $layer_img . $layer_content . '</div>';
    }

    // Dynamic style 
    $slider_classes = '';
    $ds_minHeight = intval($cslider_minHeight);
    $ds_maxHeight = intval($cslider_maxHeight);

    $style = "<style>";
    $style .=  ".cinza-slider-".$slider_id." {
                    height: ". ( ($ds_minHeight + $ds_maxHeight) / 2) ."px; /* Temporary while it loads, removed with jQuery */
                    opacity: 0;
                    overflow: hidden; /* Temporary while it loads, removed with jQuery */
                }
                
                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
                    object-fit: ". $cslider_imgFit .";
                }";

    $dynamic_minHeight = 'auto';
    $dynamic_maxHeight = 'auto';
    if ($ds_minHeight > 0) {$dynamic_minHeight = $ds_minHeight ."px";}
    if ($ds_maxHeight> 0) {$dynamic_maxHeight = $ds_maxHeight ."px";}
    
    if (boolval($cslider_setGallerySize)==false && boolval($cslider_adaptiveHeight)==false) {
	    // setGallerySize OFF + adaptiveHeight OFF
	    $style .=  ".cinza-slider-".$slider_id.", 
	                .cinza-slider-".$slider_id." .flickity-viewport, 
	                .cinza-slider-".$slider_id." .slider-cell, 
	                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
	                    min-height: ". $dynamic_minHeight .";
	                    max-height: ". $dynamic_minHeight .";
	                }";	    
    } else if (boolval($cslider_setGallerySize)==false && boolval($cslider_adaptiveHeight)==true) {
	    // setGallerySize OFF + adaptiveHeight ON
	    $style .=  ".cinza-slider-".$slider_id.", 
	                .cinza-slider-".$slider_id." .flickity-viewport, 
	                .cinza-slider-".$slider_id." .slider-cell, 
	                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
	                    min-height: ". $dynamic_maxHeight .";
	                    max-height: ". $dynamic_maxHeight .";
	                }";	
    } else {
	    // setGallerySize ON + adaptiveHeight OFF
	    // setGallerySize ON + adaptiveHeight ON
	    $style .=  ".cinza-slider-".$slider_id.", 
	                .cinza-slider-".$slider_id." .flickity-viewport, 
	                .cinza-slider-".$slider_id." .slider-cell, 
	                .cinza-slider-".$slider_id." .slider-cell .slider-cell-image {
	                    min-height: ". $dynamic_minHeight .";
	                    max-height: ". $dynamic_maxHeight .";
	                }";	
    }

    if (boolval($cslider_fullWidth)) {
        $style .=  ".cinza-slider-".$slider_id." {
                        width: 100vw !important;
                        max-width: 100vw !important;
                        position: relative;
                        left: 50%;
                        right: 50%;
                        margin-left: -50vw !important;
                        margin-right: -50vw !important;
                    }";
    }

    if(!empty($cslider_static['cslider_static_overlay'])) {

	    $cslider_static_gradient = isset($cslider_static['cslider_static_gradient']) ? esc_attr($cslider_static['cslider_static_gradient']) : '0';
	    if (boolval($cslider_static_gradient)) {
		    $cslider_static_overlay = "background: -moz-". $cslider_static['cslider_static_overlay'] .";";
		    $cslider_static_overlay .= "background: -webkit-". $cslider_static['cslider_static_overlay'] .";";
		    $cslider_static_overlay .= "background: ". $cslider_static['cslider_static_overlay'] .";";   
	    } else {
		    $cslider_static_overlay = "background: ". $cslider_static['cslider_static_overlay'] .";";   
	    }
	    
        $style .=  ".cinza-slider-".$slider_id." .slider-cell:after {
                        content: '';
                        position: absolute;
                        display: block;
                        top: 0;
                        bottom: 0;
                        width: 100%;
                        height: 100%;
                        z-index: 1;
                        ". $cslider_static_overlay ."
                    }";
    }
    
    if (boolval($cslider_rfAccessibility)) {
	    $slider_classes .= "rfAccessibility";
        $style .=  ".cinza-slider:focus,
					.cinza-slider:focus-visible,
					.cinza-slider .flickity-viewport:focus,
					.cinza-slider .flickity-viewport:focus-visible,
					.cinza-slider a.slider-cell-link:focus,
					.cinza-slider a.slider-cell-link:focus-visible {
					    outline: none !important;
					}
					
					.cinza-slider:focus .focusable,
					.cinza-slider:focus-visible .focusable {
						background: pink;
					}
					
					.cinza-slider a.slider-cell-link:focus,
					.cinza-slider a.slider-cell-link:focus-visible {
						background: rgba(255, 191, 202, 0.3);
					}
					
					.cinza-slider .flickity-button:focus {
					    outline: 0 !important;
					    box-shadow: none !important;
					    outline: none !important;
					}";
    }
    
    $style .= "</style>";

    // Output
    $o = '<div class="cinza-slider cinza-slider-'.$slider_id.' '.$slider_classes.' animate__animated animate__fadeIn" data-flickity='. $options .'>'. $static . $slides .'</div>'. $style;
    return $o;
}