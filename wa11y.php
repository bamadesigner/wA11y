<?php

/**
 * Plugin Name:       wA11y - The Web Accessibility Toolbox
 * Plugin URI:        https://wordpress.org/plugins/wa11y
 * Description:       A toolbox of resources to help you improve the accessibility of your WordPress website.
 * Version:           1.0.0
 * Author:            Rachel Carden
 * Author URI:        https://bamadesigner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wa11y
 * Domain Path:       /languages
 */

// @TODO provide a resources meta box for the options page

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If you define them, will they be used?
define( 'WA11Y_VERSION', '1.0.0' );
define( 'WA11Y_PLUGIN_URL', 'https://wordpress.org/plugins/wa11y' );
define( 'WA11Y_PLUGIN_FILE', 'wa11y/wa11y.php' );
define( 'WA11Y_TOTA11Y_VERSION', '0.1.3' );

// We only need you in the admin
if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';
}

/**
 * The class that powers general plugin functionality.
 *
 * Class    wA11y
 * @since   1.0.0
 */
class wA11y {

	/**
	 * Whether or not this plugin is network active.
	 *
	 * @since	1.0.0
	 * @access	public
	 * @var		boolean
	 */
	public $is_network_active;

	/**
	 * The plugin settings.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		array
	 */
	protected $settings;

	/**
	 * List of enabled tools.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		array
	 */
	protected $enabled_tools;

	/**
	 * Will hold status of if
	 * we can load tools.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		array
	 */
	protected $can_load_tools;

	/**
	 * Holds the class instance.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		wA11y
	 */
	private static $instance;

	/**
	 * Returns the instance of this class.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return	wA11y
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	/**
	 * Method to keep our instance from being cloned.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	void
	 */
	private function __clone() {}

	/**
	 * Method to keep our instance from being unserialized.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @return	void
	 */
	private function __wakeup() {}

	/**
	 * Start your engines.
	 *
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function __construct() {

		// Is this plugin network active?
		$this->is_network_active = is_multisite() && ( $plugins = get_site_option( 'active_sitewide_plugins' ) ) && isset( $plugins[ WA11Y_PLUGIN_FILE ] );

		// Load our text domain
		add_action( 'init', array( $this, 'textdomain' ) );

		// Runs on install
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		// Runs when the plugin is upgraded
		add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 1, 2 );

		// Add items to the toolbar
		add_action( 'admin_bar_menu', array( $this, 'add_to_toolbar' ), 100 );

		// Load front-end styles/scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

	}

	/**
	 * Runs when the plugin is installed.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function install() {}

	/**
	 * Runs when the plugin is upgraded.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function upgrader_process_complete() {}

	/**
	 * Internationalization FTW.
	 * Load our textdomain.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	public function textdomain() {
		load_plugin_textdomain( 'wa11y', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Get wA11y settings
	 *
	 * @since   1.0.0
	 * @filter  'wa11y_settings' - array with the settings
	 * @param   $tool_key - if set, will retrieve settings for a particular tool
	 * @return  array - the settings
	 */
	public function get_settings( $tool_key = '' ) {

		// If settings are already defined...
		if ( isset( $this->settings ) ) {

			// Return settings for a specific tool...
			if ( ! empty( $tool_key ) ) {

				if ( isset( $this->settings['tools'] ) && isset( $this->settings['tools'][ $tool_key ] ) ) {
					return $this->settings['tools'][ $tool_key ];
				}

				// We don't have settings for this tool
				return array();

			}

			// Otherwise return everything
			return $this->settings;

		}

		// Define the default settings
		$default_settings = array(
			'enable_tools' => array(),
			'tools' => array(
				'tota11y' => array(
					'load_user_roles'       => array( 'administrator' ),
					'load_user_capability'  => null,
					'load_in_admin'         => 0,
				),
				'wave' => array(
					'load_user_roles'       => array( 'administrator' ),
					'load_user_capability'  => null,
					'load_admin_edit'       => 0,
				)
			)
		);

		// Get the settings from the database and run through a filter
		$settings = apply_filters( 'wa11y_settings', get_option( 'wa11y_settings', $default_settings ) );

		// Store the settings
		$this->settings = wp_parse_args( $settings, $default_settings );

		// Return settings for a specific tool...
		if ( ! empty( $tool_key ) ) {

			if ( isset( $this->settings['tools'] ) && isset( $this->settings['tools'][ $tool_key ] ) ) {
				return $this->settings['tools'][ $tool_key ];
			}

			// We don't have settings for this tool
			return array();

		}

		// Return the settings
		return $this->settings;
	}

	/**
	 * Returns array of enabled tools.
	 *
	 * @since   1.0.0
	 * @return  array of enabled tools
	 */
	public function get_enabled_tools() {

		// If already defined, get out of here
		if ( isset( $this->enabled_tools ) ) {
			return $this->enabled_tools;
		}

		// Get our settings
		$settings = $this->get_settings();

		// Get enabled tools
		if ( isset( $settings[ 'enable_tools' ] ) && ! empty( $settings[ 'enable_tools' ] ) ) {

			// Make sure its an array
			if ( ! is_array( $settings[ 'enable_tools' ] ) ) {
				$settings[ 'enable_tools' ] = explode( ', ', $settings[ 'enable_tools' ] );
			}

			// Return the enabled list
			return $this->enabled_tools = $settings[ 'enable_tools' ];

		}

		// We have no enabled tools
		return $this->enabled_tools = false;
	}

	/**
	 * Add items to the toolbar.
	 *
	 * @since   1.0.0
	 * @filter  'wa11y_wave_url' - string containing the WAVE evaluation URL. Passes the $post object if it exists.
	 * @param   WP_Admin_Bar - $wp_admin_bar - WP_Admin_Bar instance, passed by reference
	 */
	public function add_to_toolbar( $wp_admin_bar ) {

		// Only need to worry about this stuff if we have enabled tools
		$enabled_tools = $this->get_enabled_tools();
		if ( empty( $enabled_tools ) ) {
			return;
		}

		// Will hold all of the wA11y nodes
		$wa11y_nodes = array();

		// Process each enabled tool
		foreach( $enabled_tools as $tool ) {

			switch( $tool ) {

				case 'wave':

					// Can we load WAVE?
					if ( $this->can_load_wave() ) {

						// Get WAVE settings
						$settings = wa11y()->get_settings( 'wave' );

						// Make sure its OK to add to the toolbar
						if ( ! empty( $settings['load_in_toolbar'] ) && true == $settings['load_in_toolbar'] ) {

							// Build the WAVE url
							$wave_url = get_permalink();

							// Filter the WAVE url - includes $post object if it exists
							$wave_url = apply_filters( 'wa11y_wave_url', $wave_url, get_post() );

							// Add WAVE node if we have a URL
							if ( ! empty( $wave_url ) ) {
								$wa11y_nodes[] = array(
									'id'    => 'wa11y-wave',
									'title' => sprintf( __( 'View %s evaluation', 'wa11y' ), 'WAVE' ),
									'href'  => 'http://wave.webaim.org/report#/' . urlencode( $wave_url ),
									'meta'  => array( 'target' => '_blank' ),
								);
							}

						}

					}

					break;

			}

		}

		// If we have nodes to add...
		if ( ! empty( $wa11y_nodes ) ) {

			// Add parent wA11y node
			$wp_admin_bar->add_node( array(
				'id'    	=> 'wa11y',
				'title' 	=> '<span aria-hidden="true">wA11y</span><span class="screen-reader-text">Wally</span>',
				'parent'	=> false,
			));

			// Add child nodes
			foreach( $wa11y_nodes as $node ) {
				$wp_admin_bar->add_node( array_merge( $node, array( 'parent' => 'wa11y' ) ) );
			}

		}

	}

	/**
	 * Enqueue any styles/scripts needed for the front-end.
	 *
	 * Pretty much loading the scripts needed for our tools.
	 *
	 * @since   1.0.0
	 * @filter  'wa11y_load_tota11y' - boolean on whether or not to load the tota11y tool. Passes the tota11y settings.
	 */
	public function enqueue_styles_scripts() {

		// If tota11y is enabled...
		if ( wa11y()->can_load_tota11y() ) {

			// This file belongs in the header
			wp_enqueue_script( 'tota11y', plugins_url( '/tools/tota11y/tota11y.min.js', __FILE__ ), array(), WA11Y_VERSION );

		}

	}

	/**
	 * Checks whether or not tota11y is set to load.
	 *
	 * @since   1.0.0
	 * @filter  'wa11y_load_tota11y' - boolean on whether or not to load the tota11y tool. Passes the tota11y settings.
	 * @return	boolean - true if we're set to load tota11y, otherwise false
	 */
	public function can_load_tota11y() {

		// If already defined, get out of here
		if ( isset( $this->can_load_tools['tota11y'] ) ) {
			return $this->can_load_tools['tota11y'];
		}

		// Get enabled tools
		$enabled_tools = $this->get_enabled_tools();

		// If tota11y isn't enabled...
		if ( empty( $enabled_tools ) || ! in_array( 'tota11y', $enabled_tools ) ) {
			return $this->can_load_tools['tota11y'] = false;
		}

		// Get tota11y settings
		$settings = $this->get_settings( 'tota11y' );

		// By default, only load tota11y if the user is logged in
		$load_tota11y = is_user_logged_in();

		// If we're still passing tests, are we supposed to load tota11y in the admin?
		if ( $load_tota11y && is_admin() ) {
			$load_tota11y = isset( $settings[ 'load_in_admin' ] ) && $settings[ 'load_in_admin' ] > 0;
		}

		// If we're still passing tests, keep checking
		if ( $load_tota11y ) {

			// If a set user role, then load tota11y
			if ( ! empty( $settings[ 'load_user_roles' ] ) ) {
				$load_tota11y = $this->is_user_in_user_roles( $settings['load_user_roles'] );
			}

			// If user capability is set, turn off if not capable
			if ( ! empty( $settings[ 'load_user_capability' ] ) ) {
				$load_tota11y = current_user_can( $settings[ 'load_user_capability' ] );
			}

		}

		// Filter whether or not to load tota11y - passes the tota11y settings
		return $this->can_load_tools['tota11y'] = apply_filters( 'wa11y_load_tota11y', $load_tota11y, $settings );
	}

	/**
	 * Checks whether or not WAVE is set to load.
	 *
	 * @since   1.0.0
	 * @filter  'wa11y_load_wave' - boolean on whether or not to load the WAVE tool. Passes the WAVE settings.
	 * @return	boolean - true if we're set to load WAVE, otherwise false
	 */
	public function can_load_wave() {

		// If already defined, get out of here
		if ( isset( $this->can_load_tools['wave'] ) ) {
			return $this->can_load_tools['wave'];
		}

		// Get enabled tools
		$enabled_tools = $this->get_enabled_tools();

		// If WAVE isn't enabled...
		if ( ! in_array( 'wave', $enabled_tools ) ) {
			return $this->can_load_tools['wave'] = false;
		}

		// Get WAVE settings
		$settings = $this->get_settings( 'wave' );

		// By default, only load WAVE if the user is logged in
		$load_wave = is_user_logged_in();

		// Only for published posts
		if ( 'publish' != get_post_status() ) {
			$load_wave = false;
		}

		// If we're still passing tests, keep checking
		if ( $load_wave ) {

			// If a set user role, then load WAVE
			if ( ! empty( $settings[ 'load_user_roles' ] ) ) {
				$load_wave = $this->is_user_in_user_roles( $settings['load_user_roles'] );
			}

			// If user capability is set, turn off if not capable
			if ( ! empty( $settings[ 'load_user_capability' ] ) ) {
				$load_wave = current_user_can( $settings[ 'load_user_capability' ] );
			}

		}

		// Filter whether or not to load WAVE - passes the WAVE settings
		return $this->can_load_tools['wave'] = apply_filters( 'wa11y_load_wave', $load_wave, $settings );
	}

	/**
	 * Tests if current user is one of the
	 * user roles passed to the function.
	 *
	 * @since   1.0.0
	 * @param	array - an array of user roles to test against
	 * @return	boolean - true if user is one of passed user roles, false otherwise
	 */
	public function is_user_in_user_roles( $user_roles ) {

		// Make sure we have user roles
		if ( empty( $user_roles ) ) {
			return false;
		}

		// Get current user
		if ( ( $current_user = wp_get_current_user() )
		     && ( $current_user_roles = isset( $current_user->roles ) ? $current_user->roles : false )
		     && is_array( $current_user_roles ) ) {

			// Find out if they share values
			$user_roles_intersect = array_intersect( $user_roles, $current_user_roles );

			// If they intersect, user is in user roles so return true
			if ( ! empty( $user_roles_intersect ) ) {
				return true;
			}

		}

		return false;

	}

}

/**
 * Returns the instance of our main wA11y class.
 *
 * Will come in handy when we need to access the
 * class to retrieve data throughout the plugin.
 *
 * @since	1.0.0
 * @access	public
 * @return	wA11y
 */
function wa11y() {
	return wA11y::instance();
}

// Let's get this show on the road
wa11y();