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
	global $wa11y_settings;

	// If we've already retrieved them then we're good to go
	if ( isset( $wa11y_settings ) && ! empty( $wa11y_settings ) ) {
		return $wa11y_settings;
	}

	return $wa11y_settings = get_option( 'wa11y_settings', array(
		'tools' => array(
			'tota11y' => array(
				'load_user_roles'       => array( 'administrator' ),
				'load_user_capability'  => 'view_tota11y',
				'load_in_admin'         => 0,
			)
		)
	) );
}

/**
 * Add items to the admin bar.
 *
 * @since   1.0
 * @param 	WP_Admin_Bar - $wp_admin_bar - WP_Admin_Bar instance, passed by reference
 */
add_action( 'admin_bar_menu', 'wa11y_add_to_admin_bar', 100 );
function wa11y_add_to_admin_bar( $wp_admin_bar ) {

	// Right now it's only on the front end
	if ( is_admin() ) {
		return;
	}

	// Get our settings
	$wa11y_settings = wa11y_get_settings();

	// Only need to worry about this stuff if we have enabled tools
	$wa11y_enable_tools = isset( $wa11y_settings[ 'enable_tools' ] ) ? $wa11y_settings[ 'enable_tools' ] : array();
	if ( empty( $wa11y_enable_tools ) )
		return;

	// Will hold all of the Wa11y nodes
	$wa11y_nodes = array();

	// If WAVE is enabled...
    if ( in_array( 'wave', $wa11y_enable_tools ) ) {

	    // Get wave settings
	    //$wa11y_wave_settings = isset( $wa11y_settings[ 'tools' ] ) && isset( $wa11y_settings[ 'tools' ][ 'wave' ] ) ? $wa11y_settings[ 'tools' ][ 'wave' ] : array();

		// Build the current URL
		$current_url = ( ! ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) ? 'http://' : 'https://' ) . $_SERVER[ 'SERVER_NAME' ] . ( isset( $_SERVER[ 'REQUEST_URI' ] ) ? $_SERVER[ 'REQUEST_URI' ] : NULL );

		// Add WAVE node
		$wa11y_nodes[] = array(
			'id'    	=> 'wa11y-wave',
			'title' 	=> 'WAVE Evaluation',
			'href'		=> 'http://wave.webaim.org/report#/' . urlencode( $current_url ),
			'meta'		=> array( 'target' => '_blank' ),
			);

	}

	if ( ! empty( $wa11y_nodes ) ) {

		// Add parent Wa11y node
		$wp_admin_bar->add_node( array(
			'id'    	=> 'wa11y',
			'title' 	=> 'Wa11y',
			'parent'	=> false,
		));

		// Add child nodes
		foreach( $wa11y_nodes as $node ) {
			$wp_admin_bar->add_node( array_merge( $node, array( 'parent' => 'wa11y' ) ) );
		}

	}

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

	// If tota11y is enabled...
    if ( in_array( 'tota11y', $wa11y_enable_tools ) ) {

	    // Get tota11y settings
	    $wa11y_tota11y_settings = isset( $wa11y_settings[ 'tools' ] ) && isset( $wa11y_settings[ 'tools' ][ 'tota11y' ] ) ? $wa11y_settings[ 'tools' ][ 'tota11y' ] : array();

	    // Will be true if we should load tota11y - by default, load if logged in
	    $load_tota11y = is_user_logged_in();

	    // If user roles are set, turn off it not a user role
	    if ( isset( $wa11y_tota11y_settings[ 'load_user_roles' ] ) && is_array( $wa11y_tota11y_settings[ 'load_user_roles' ] ) ) {

		    // Get current user
		    if ( ( $current_user = wp_get_current_user() )
		        && ( $current_user_roles = isset( $current_user->roles ) ? $current_user->roles : false )
		        && is_array( $current_user_roles ) ) {

			    // Find out if they share values
			    $user_roles_intersect = array_intersect( $wa11y_tota11y_settings[ 'load_user_roles' ], $current_user_roles );

			    // If they do not intersect, turn off
			    if ( empty( $user_roles_intersect ) ) {
				    $load_tota11y = false;
				}

		    }

	    }

	    // If user capability is set, turn off if not capable
	    if ( ! empty( $wa11y_tota11y_settings[ 'load_user_capability' ] ) ) {
		    $load_tota11y = current_user_can( $wa11y_tota11y_settings[ 'load_user_capability' ] );
	    }

	    // We need to load tota11y
	    if ( $load_tota11y ) {

		    // This file belongs in the header
		    wp_enqueue_script( 'tota11y', plugins_url( '/includes/tota11y/tota11y.min.js', __FILE__ ) );

	    }

    }

}