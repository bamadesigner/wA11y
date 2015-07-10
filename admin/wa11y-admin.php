<?php

/**
 * Register our settings.
 *
 * @since    1.0
 */
add_action( 'admin_init', 'wa11y_register_settings' );
function wa11y_register_settings() {
	register_setting( 'wa11y_settings', 'wa11y_settings', 'wa11y_sanitize_settings' );
}

/**
 * Sanitizes the 'wa11y_settings' option.
 *
 * @since   1.0
 * @param 	array - $settings - the settings that are being sanitized
 * @return	array - sanitized $settings
 */
function wa11y_sanitize_settings( $settings ) {
	return $settings;
}

/**
 * Setup stylesheets for the admin.
 *
 * @since   1.0
 * @param   string - $hook_suffix - the hook/ID of the current page
 */
add_action( 'admin_enqueue_scripts', 'wa11y_enqueue_admin_styles' );
function wa11y_enqueue_admin_styles( $hook_suffix ) {
	global $wa11y_options_page;

	// Get our settings
	$wa11y_settings = wa11y_get_settings();

	switch( $hook_suffix ) {

		// Add styles to our options page
		case $wa11y_options_page:

			// Enqueue the styles for our options page
			wp_enqueue_style( 'wa11y-admin-options', plugin_dir_url( __FILE__ ) . 'css/wa11y-admin-options-page.min.css', array(), WA11Y_VERSION );

			// Enqueue the script for our options page
			//wp_enqueue_script( 'wa11y-admin-options', plugin_dir_url( __FILE__ ) . 'js/wa11y-admin-options-page.js', array( 'jquery' ), WA11Y_VERSION, false );

			break;

		// Add styles to the "Edit Post" screen
		case 'post.php':
		case 'post-new.php':

			// Register axe - goes in header
			//wp_register_script( 'axe', plugins_url( '/includes/axe/axe.min.js' , dirname(__FILE__ ) ) );

			// Initiate axe - goes in header
			//wp_enqueue_script( 'initiate-axe', plugin_dir_url( __FILE__ ) . 'js/wa11y-admin-post-axe.js', array( 'axe' ) );

			break;

	}

	// Load tools in the admin

	// Only need to worry about this stuff if we have enabled tools
	if ( $wa11y_enable_tools = isset( $wa11y_settings[ 'enable_tools' ] ) ? $wa11y_settings[ 'enable_tools' ] : array() ) {

		// If tota11y is enabled...
		if ( in_array( 'tota11y', $wa11y_enable_tools ) ) {

			// Get tota11y settings
			$wa11y_tota11y_settings = isset( $wa11y_settings[ 'tools' ] ) && isset( $wa11y_settings[ 'tools' ][ 'tota11y' ] ) ? $wa11y_settings[ 'tools' ][ 'tota11y' ] : array();

			// Load tota11y in the admin if set
			if ( isset( $wa11y_tota11y_settings[ 'load_in_admin' ] ) && $wa11y_tota11y_settings[ 'load_in_admin' ] > 0 ) {

				// This file belongs in the header
				wp_enqueue_script( 'tota11y', plugins_url( '/includes/tota11y/tota11y.min.js', dirname( __FILE__ ) ) );

			}

		}

	}

}

/**
 * Add the options page.
 *
 * @since   1.0
 */
add_action( 'admin_menu', 'wa11y_add_options_page' );
function wa11y_add_options_page() {
	global $wa11y_options_page;

	// Add the options page
	$wa11y_options_page = add_options_page( __( 'Wa11y', 'wa11y' ), __( 'Wa11y', 'wa11y' ), 'manage_options', 'wa11y', 'wa11y_print_options_page' );

}

/**
 * Print the options page.
 *
 * @since   1.0
 */
function wa11y_print_options_page() {

	// Include the options page
	require_once plugin_dir_path( __FILE__ ) . 'wa11y-admin-options-page.php';

}

/**
 * Add meta boxes to the options page.
 *
 * @since   1.0
 */
add_action( 'admin_head-settings_page_wa11y', 'wa11y_add_options_meta_boxes' );
function wa11y_add_options_meta_boxes() {
	global $wa11y_options_page;

	// Get our saved settings
	$wa11y_settings = wa11y_get_settings();

	// About this Plugin
	add_meta_box( 'wa11y-about', __( 'About Wa11y', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'side', 'core', $wa11y_settings );

	// Save Changes
	add_meta_box( 'wa11y-save-changes', __( 'Save Changes', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'side', 'core', $wa11y_settings );

	// Enable Tools
	add_meta_box( 'wa11y-enable-tools', __( 'Enable Tools', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'normal', 'core', $wa11y_settings );

	// tota11y Settings
	add_meta_box( 'wa11y-tota11y-settings', __( 'tota11y Settings', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'normal', 'core', $wa11y_settings );

}

/**
 * Print the meta boxes for the options page.
 *
 * @since   1.0
 * @param	array - $post - information about the current post, which is empty because there is no current post on a settings page
 * @param	array - $metabox - information about the metabox
 */
function wa11y_print_options_meta_boxes( $post, $metabox ) {

	// Get the saved settings passed to the meta boxes
	$wa11y_settings = isset( $metabox[ 'args' ] ) ? $metabox[ 'args' ] : array();

	switch( $metabox[ 'id' ] ) {

		// About Wa11y
		case 'wa11y-about':

			// Print the plugin name (with link to site)
			?><p>@TODO: ADD DESCRIPTION Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras risus urna, ullamcorper in ullamcorper in, dapibus vel leo. Nam diam odio, aliquam quis accumsan a, viverra non sem. Pellentesque non fringilla sapien.</p><?php

			// Print the plugin version and author (with link to site)
			?><p><strong><?php echo _e( 'Version', 'wa11y' ); ?>:</strong> <?php echo preg_match( '/^([0-9]+)$/i', WA11Y_VERSION ) ? number_format( WA11Y_VERSION, 1, '.', '' ) : WA11Y_VERSION; ?><br />
			<strong><?php echo _e( 'Author', 'wa11y' ); ?>:</strong> <a href="http://bamadesigner.com/" target="_blank">Rachel Carden</a></p><?php

			break;

		// Save Changes
		case 'wa11y-save-changes':
			echo submit_button( __( 'Save Your Changes', 'wa11y' ), 'primary', 'save_wa11y_settings', false, array( 'id' => 'save-wa11y-settings-mb' ) );
			break;

		// Enable Tools
		case 'wa11y-enable-tools':

			// Get enable tools settings
			$wa11y_enable_tools_settings = isset( $wa11y_settings[ 'enable_tools' ] ) ? $wa11y_settings[ 'enable_tools' ] : array();

			?><fieldset>
				<ul id="wa11y-enable-tools-list">
					<li>
						<input class="tool-checkbox" id="tota11y" type="checkbox" name="wa11y_settings[enable_tools][]" value="tota11y"<?php checked( is_array( $wa11y_enable_tools_settings ) && in_array( 'tota11y', $wa11y_enable_tools_settings) ); ?> />
						<label class="tool-label" for="tota11y">tota11y</label> <a class="view-tool-settings" href="#wa11y-tota11y-settings">(view settings)</a>
						<p class="tool-desc"><?php printf( __( '%1$s%2$s%3$s is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.', 'wa11y' ), '<a href="http://khan.github.io/tota11y/" target="_blank">', 'tota11y', '</a>' ); ?></p>
					</li>
				</ul>
			</fieldset><?php

			break;

		// tota11y Settings
		case 'wa11y-tota11y-settings':

			// Get tota11y settings
			$wa11y_tota11y_settings = isset( $wa11y_settings[ 'tools' ] ) && isset( $wa11y_settings[ 'tools' ][ 'tota11y' ] ) ? $wa11y_settings[ 'tools' ][ 'tota11y' ] : array();

			// Get the user roles
			$user_roles = get_editable_roles();

			?><div class="wa11y-tool-settings">
				<p class="tool-desc"><?php printf( __( '%1$s%2$s%3$s is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.', 'wa11y' ), '<a href="http://khan.github.io/tota11y/" target="_blank">', 'tota11y', '</a>' ); ?></p>
				<fieldset>
					<ul id="wa11y-tota11y-settings-list" class="tool-settings-list"><?php

						if ( ! empty( $user_roles ) ) {
							?><li><label class="tool-setting-header"><?php printf( __( 'Only load %1$s for specific user roles', 'wa11y' ), 'tota11y' ); ?>:</label> <?php

								foreach( $user_roles as $user_role_key => $user_role ) {
									?><input class="tool-checkbox" id="tota11y-user-role-<?php echo $user_role_key; ?>" type="checkbox" name="wa11y_settings[tools][tota11y][load_user_roles][]" value="<?php echo $user_role_key; ?>"<?php checked( isset( $wa11y_tota11y_settings[ 'load_user_roles' ] ) && in_array( $user_role_key, $wa11y_tota11y_settings[ 'load_user_roles' ] ) ); ?> />
									<label class="tool-label" for="tota11y-user-role-<?php echo $user_role_key; ?>"><?php echo $user_role[ 'name' ]; ?></label><?php
								}

							?></li><?php
						}

						?><li><label class="tool-setting-header" for="tota11y-user-capability"><?php printf( __( 'Only load %1$s for a specific user capability', 'wa11y' ), 'tota11y' ); ?>:</label> <input id="tota11y-user-capability" type="text" name="wa11y_settings[tools][tota11y][load_user_capability]" value="<?php echo isset( $wa11y_tota11y_settings[ 'load_user_capability' ] ) ? $wa11y_tota11y_settings[ 'load_user_capability' ] : null; ?>" /></li>

						<li><label class="tool-setting-header" for="tota11y-admin"><?php printf( __( 'Load %1$s in the admin', 'wa11y' ), 'tota11y' ); ?>:</label>
							<input class="tool-checkbox" id="tota11y-admin-yes" type="radio" name="wa11y_settings[tools][tota11y][load_in_admin]" value="1"<?php checked( isset( $wa11y_tota11y_settings[ 'load_in_admin' ] ) && $wa11y_tota11y_settings[ 'load_in_admin' ] > 0 ); ?> />
							<label class="tool-label" for="tota11y-admin-yes"><?php _e( 'Yes', 'wa11y' ); ?></label>
							<input class="tool-checkbox" id="tota11y-admin-no" type="radio" name="wa11y_settings[tools][tota11y][load_in_admin]" value="0"<?php checked( ! ( isset( $wa11y_tota11y_settings[ 'load_in_admin' ] ) && $wa11y_tota11y_settings[ 'load_in_admin' ] > 0 ) ); ?> />
							<label class="tool-label" for="tota11y-admin-no"><?php _e( 'No', 'wa11y' ); ?></label>

					</ul>
				</fieldset>
			</div> <!-- .wa11y-tool-settings --><?php

			break;

	}

}

/**
 * Adds a settings link to the plugins table.
 *
 * @since   1.0
 * @param	$actions - an array of plugin action links
 * @param 	$plugin_file - path to the plugin file
 * @param	$context - The plugin context. Defaults are 'All', 'Active', 'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use', 'Drop-ins', 'Search'
 * @return 	array - the links info after it has been filtered
 */
// Add plugin action links
add_filter( 'plugin_action_links_wa11y/wa11y.php', 'wa11y_add_plugin_action_links', 10, 4 );
function wa11y_add_plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {

	// Add link to our settings page
	$actions[ 'settings' ] = '<a href="' . add_query_arg( array( 'page' => 'wa11y' ), admin_url( 'options-general.php' ) ) . '" title="' . esc_attr__( 'Visit Wa11y\'s settings page', 'wa11y' ) . '">' . __( 'Settings' , 'wa11y' ) . '</a>';

	return $actions;

}