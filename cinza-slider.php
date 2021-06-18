<?php

/**
 * Plugin Name:       Cinza Slider
 * Plugin URI:        https://cinza.io/plugin/slider/
 * Description:       A minimal slider plugin.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Cinza Web Design
 * Author URI:        https://cinza.io/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) || exit;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Register scrips for frontend
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action( 'wp_enqueue_scripts', 'cslider_scripts_frontend_init' );
function cslider_scripts_frontend_init( $hook ) {
	
	// Register scripts only on frontend
	if ( is_admin() ) return;
	
	// Register React - https://reactjs.org/docs/cdn-links.html
	wp_register_script('react', 'https://unpkg.com/react@17/umd/react.production.min.js');
	wp_register_script('react-dom', 'https://unpkg.com/react-dom@17/umd/react-dom.production.min.js');
	
	// Register Cinza Slider component, with dependencies
	wp_register_script('cinza-slider', 'http://plugins.local/wp-content/plugins/cinza-slider/components/CinzaSlider.js', array( 'react', 'react-dom' ) );
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Include plugin files
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

define( 'CSLIDER_PATH', plugin_dir_path( __FILE__ ) );
include_once( CSLIDER_PATH . 'includes/backend-cpts.php' );
include_once( CSLIDER_PATH . 'includes/backend-shortcodes.php' );

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Activation hook
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

register_activation_hook( __FILE__, 'cslider_activate' );
function cslider_activate() { 
	
    // Register CPT
    cslider_register_post_type(); 
    
    // Reset permalinks
    flush_rewrite_rules(); 
}
    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Deactivation hook
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

register_deactivation_hook( __FILE__, 'cslider_deactivate' );
function cslider_deactivate() {
    
    // Unregister CPT
    unregister_post_type( 'cinza_slider' );
    
    // Reset permalinks
    flush_rewrite_rules();
}


?>
