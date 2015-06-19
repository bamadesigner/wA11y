<?php

/**
 * Plugin Name:       Wally
 * Plugin URI:        http://bamadesigner.com
 * Description:       @TODO This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0
 * Author:            Rachel Carden
 * Author URI:        http://bamadesigner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wally
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load the scripts
add_action( 'wp_enqueue_scripts', 'wally_enqueue_scripts' );
function wally_enqueue_scripts() {

	// Register axe - goes in header
	wp_register_script( 'axe', plugins_url( '/includes/axe/axe.min.js' , __FILE__ ) );

	// Initiate axe - goes in header
	wp_enqueue_script( 'initiate-axe', plugins_url( '/includes/axe/initiate-axe.js' , __FILE__ ), array( 'axe' ) );

	// Enqueue tota11y - goes in header
	wp_enqueue_script( 'tota11y', plugins_url( '/includes/tota11y/tota11y.min.js' , __FILE__ ) );

}