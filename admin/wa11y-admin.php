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

	switch( $hook_suffix ) {

		// Add styles to our options page
		case $wa11y_options_page:

			// Enqueue the styles for our options page
			wp_enqueue_style( 'wa11y-admin-options', plugin_dir_url( __FILE__ ) . 'wa11y-admin-options-page.css', array(), WA11Y_VERSION );

			// Enqueue the script for our options page
			//wp_enqueue_script( 'wa11y-admin-options', plugin_dir_url( __FILE__ ) . 'wa11y-admin-options-page.js', array( 'jquery' ), WA11Y_VERSION, false );

			break;

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
	$wa11y_saved_settings = wa11y_get_settings();

	// About this Plugin
	add_meta_box( 'wa11y-about', __( 'About Wa11y', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'side', 'core', $wa11y_saved_settings );

	// Save Changes
	add_meta_box( 'wa11y-save-changes', __( 'Save Changes', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'side', 'core', $wa11y_saved_settings );

	// Enable Tools
	add_meta_box( 'wa11y-enable-tools', __( 'Enable Tools', 'wa11y' ), 'wa11y_print_options_meta_boxes', $wa11y_options_page, 'normal', 'core', $wa11y_saved_settings );

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
			?><p><strong>Version:</strong> <?php echo preg_match( '/^([0-9]+)$/i', WA11Y_VERSION ) ? number_format( WA11Y_VERSION, 1, '.', '' ) : WA11Y_VERSION; ?><br />
			<strong>Author:</strong> <a href="http://bamadesigner.com/" target="_blank">Rachel Carden</a></p><?php

			break;

		// Save Changes
		case 'wa11y-save-changes':
			echo submit_button( 'Save Your Changes', 'primary', 'save_wa11y_settings', false, array( 'id' => 'save-wa11y-settings-mb' ) );
			break;

		// Enable Tools
		case 'wa11y-enable-tools':

			// Get enable tools settings
			$wa11y_enable_tools = isset( $wa11y_settings[ 'enable_tools' ] ) ? $wa11y_settings[ 'enable_tools' ] : array();

			?><fieldset>
				<ul id="wa11y-enable-tools-list">
					<li>
						<input class="tool-checkbox" id="tota11y" type="checkbox" name="wa11y_settings[enable_tools][]" value="tota11y"<?php checked( is_array( $wa11y_enable_tools ) && in_array( 'tota11y', $wa11y_enable_tools) ); ?> />
						<label class="tool-label" for="tota11y">tota11y</label>
						<p class="tool-desc">tota11y is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.</p>
					</li>
				</ul>
			</fieldset><?php

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