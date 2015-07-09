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
	return get_option( 'wa11y_settings', array(
		'tools' => array(
			'tota11y' => array(
				'load_user_roles'       => array( 'administrator' ),
				'load_user_capability'  => 'view_tota11y',
				'load_in_admin'         => 0,
			)
		)
	) );
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

	// If tota11y is enabled...
    if ( in_array( 'tota11y', $wa11y_enable_tools ) ) {

	    // Get tota11y settings
	    $wa11y_tota11y_settings = isset( $wa11y_settings[ 'tools' ] ) && isset( $wa11y_settings[ 'tools' ][ 'tota11y' ] ) ? $wa11y_settings[ 'tools' ][ 'tota11y' ] : array();

	    // Will be true if we should load tota11y
	    $load_tota11y = false;

	    // Load by user role
	    if ( isset( $wa11y_tota11y_settings[ 'load_user_roles' ] ) && is_array( $wa11y_tota11y_settings[ 'load_user_roles' ] ) ) {

		    // Get current user
		    if ( ( $current_user = wp_get_current_user() )
		        && ( $current_user_roles = isset( $current_user->roles ) ? $current_user->roles : false )
		        && is_array( $current_user_roles ) ) {

			    // Find out if they share values
			    $user_roles_intersect = array_intersect( $wa11y_tota11y_settings[ 'load_user_roles' ], $current_user_roles );

			    // If they intersect at all...
			    if ( ! empty( $user_roles_intersect ) ) {
				    $load_tota11y = true;
				}

		    }

	    }

	    // Load by user capability
	    if ( ! $load_tota11y && isset( $wa11y_tota11y_settings[ 'load_user_capability' ] ) && current_user_can( $wa11y_tota11y_settings[ 'load_user_capability' ] ) ) {
		    $load_tota11y = true;
	    }

	    // We need to load tota11y
	    if ( $load_tota11y ) {

		    // This file belongs in the header
		    wp_enqueue_script( 'tota11y', plugins_url( '/includes/tota11y/tota11y.min.js', __FILE__ ) );

	    }

    }

}