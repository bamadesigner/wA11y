<?php

/**
 * Plugin Name:       Wa11y
 * Plugin URI:        http://bamadesigner.com
 * Description:       @TODO This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0
 * Author:            Rachel Carden
 * Author URI:        http://bamadesigner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wa11y
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If you define them, will they be used?
define( 'WA11Y_VERSION', 1.0 );

// Add admin functionality in admin only
if( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/wa11y-admin.php';
}

// Get Wa11y settings
function wa11y_get_settings() {
	return get_option( 'wa11y_settings', array() );
}

// Load the script tools
add_action( 'wp_enqueue_scripts', 'wa11y_load_script_tools' );
function wa11y_load_script_tools() {

	// Get our settings
	$wa11y_settings = wa11y_get_settings();

	// Only need to worry about this stuff if we have enabled tools
	$wa11y_enable_tools = isset( $wa11y_settings[ 'enable_tools' ] ) ? $wa11y_settings[ 'enable_tools' ] : array();
	if ( empty( $wa11y_enable_tools ) )
		return;

	// Register axe - goes in header
	//wp_register_script( 'axe', plugins_url( '/includes/axe/axe.min.js' , __FILE__ ) );

	// Initiate axe - goes in header
	//wp_enqueue_script( 'initiate-axe', plugins_url( '/includes/axe/initiate-axe.js' , __FILE__ ), array( 'axe' ) );

	// Enqueue tota11y if enabled - goes in header
    if ( in_array( 'tota11y', $wa11y_enable_tools ) && current_user_can( 'view_tota11y' ) )
    	wp_enqueue_script( 'tota11y', plugins_url( '/includes/tota11y/tota11y.min.js' , __FILE__ ) );

}