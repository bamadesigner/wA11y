<?php

/**
 * The class that powers admin functionality.
 *
 * Class    wA11y_Admin
 * @since   1.0.0
 */
class wA11y_Admin {

	/**
	 * Holds the options page slug.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string
	 */
	public $options_page;

	/**
	 * Holds the class instance.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     wA11y_Admin
	 */
	private static $instance;

	/**
	 * Returns the instance of this class.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  wA11y_Admin
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	/**
	 * Method to keep our instance from being cloned.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @return  void
	 */
	private function __clone() {}

	/**
	 * Method to keep our instance from being unserialized.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @return  void
	 */
	private function __wakeup() {}

	/**
	 * Start your engines.
	 *
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function __construct() {

		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Enqueue admin scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

		// Add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_post_meta_boxes' ), 10, 2 );

		// Add options page
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );

		// Add meta boxes to the options page
		add_action( 'admin_head-settings_page_wa11y', array( $this, 'add_options_meta_boxes' ) );

		// Add plugin action links
		add_filter( 'plugin_action_links_wa11y/wa11y.php', array( $this, 'add_plugin_action_links' ), 10, 4 );

	}

	/**
	 * Register our settings.
	 *
	 * @since   1.0.0
	 */
	public function register_settings() {
		register_setting( 'wa11y_settings', 'wa11y_settings', array( $this, 'sanitize_settings' ) );
	}

	/**
	 * Sanitizes the 'wa11y_settings' option.
	 *
	 * @since   1.0.0
	 * @param 	array - $settings - the settings that are being sanitized
	 * @return	array - sanitized $settings
	 */
	public function sanitize_settings( $settings ) {
		return $settings;
	}

	/**
	 * Enqueue styles and scripts for the admin.
	 *
	 * @since   1.0.0
	 * @param   string - $hook_suffix - the hook/ID of the current page
	 * @filter  'wa11y_load_tota11y' - boolean on whether or not to load the tota11y tool. Passes the tota11y settings.
	 */
	public function enqueue_styles_scripts( $hook_suffix ) {

		switch( $hook_suffix ) {

			// Add styles to our options page
			case $this->options_page:

				// Enqueue the styles for our options page
				wp_enqueue_style( 'wa11y-admin-options', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin-options-page.min.css', array(), WA11Y_VERSION );

				break;

			// Add styles to the "Edit Post" screen
			case 'post.php':

				// Right now its only Wave styles
				if ( wa11y()->can_load_wave() ) {

					// Enqueue our styles for the edit post screen
					wp_enqueue_style( 'wa11y-admin-edit-post', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/admin-edit-post.min.css', array(), WA11Y_VERSION );

				}

				break;

		}

		/**
		 * Load tools in the admin
		 */

		// If tota11y is enabled in the admin...
		if ( wa11y()->can_load_tota11y() ) {

			// This file belongs in the header
			wp_enqueue_script( 'tota11y', plugins_url( '/tools/tota11y/tota11y.min.js', dirname( __FILE__ ) ), array(), WA11Y_VERSION );

		}

	}

	/**
	 * Adds meta boxes to the "Edit Post" screen.
	 *
	 * @since   1.0.0
	 * @param	string - the post type that's being edited
	 * @param	object - information about the post that's being edited
	 */
	public function add_post_meta_boxes( $post_type, $post ) {

		// Only add these boxes for public post types
		if ( ! in_array( $post_type, get_post_types( array( 'public' => true ) ) ) ) {
			return;
		}

		// If we can load WAVE...
		if ( wa11y()->can_load_wave() ) {

			// Get WAVE settings
			$settings = wa11y()->get_settings( 'wave' );

			// Make sure its OK to load the evaluation
			if ( ! empty( $settings['load_admin_edit'] ) && true == $settings['load_admin_edit'] ) {

				// Add WAVE Evaluation meta box
				add_meta_box( 'wa11y-wave-evaluation-mb', 'wA11y - WAVE ' . __( 'Accessibility Evaluation', 'wa11y' ), array( $this, 'print_post_meta_boxes' ), $post_type, 'normal', 'high' );

			}

		}

	}

	/**
	 * Print the meta boxes for the "Edit Post" screen.
	 *
	 * @since   1.0.0
	 * @param	array - $post - information about the current post
	 * @param	array - $metabox - information about the metabox
	 */
	public function print_post_meta_boxes( $post, $metabox ) {

		switch( $metabox[ 'id' ] ) {

			case 'wa11y-wave-evaluation-mb':

				// If SSL, we can't load the iframe because WAVE isn't SSL so add a message
				if ( is_ssl() ) {

					// Build anchor element to settings page
					$settings_page_anchor = '<a href="' . add_query_arg( array( 'page' => 'wa11y' ), admin_url( 'options-general.php' ) ) . '" title="' . sprintf( esc_attr__( 'Visit the %s settings page', 'wa11y' ), 'wA11y' ) . '" target="_blank">';

					?>
					<p id="wa11y-wave-eval-no-SSL"><strong><?php printf( __( 'At this time, the %1$s evaluation iframe cannot be embedded on a site using SSL because the %2$s site does not use SSL.', 'wa11y' ), 'WAVE', 'WAVE' ); ?></strong> <?php printf( __( 'If you would like to remove this message, please uncheck the "Display %1$s evaluation when editing content" setting on %2$sthe %3$s settings page%4$s.', 'wa11y' ), 'WAVE', $settings_page_anchor, 'wA11y', '</a>' ); ?></p>
					<?php

				} else {

					// Build WAVE evaluation URL
					$wave_url = 'http://wave.webaim.org/report#/' . urlencode( get_permalink( $post->ID ) );

					// Filter the WAVE url - includes $post object
					$wave_url = apply_filters( 'wa11y_wave_url', $wave_url, $post );

					// Print the WAVE evaluation iframe ?>
					<a class="wa11y-wave-open-evaluation" href="<?php echo $wave_url; ?>" target="_blank"><?php printf( __( 'Open %s evaluation in new window', 'wa11y' ), 'WAVE' ); ?></a>
					<iframe id="wa11y-wave-evaluation-mb-iframe" src="<?php echo $wave_url; ?>"></iframe>
					<?php

				}

				break;

		}

	}

	/**
	 * Add the options page.
	 *
	 * @since   1.0.0
	 */
	public function add_options_page() {

		// Add the options page
		$this->options_page = add_options_page( 'wA11y', 'wA11y', 'manage_options', 'wa11y', array( $this, 'print_options_page' ) );

	}

	/**
	 * Print the options page.
	 *
	 * @since   1.0.0
	 */
	public function print_options_page() {

		?>
		<div id="wa11y-options" class="wrap options" role="form">

			<h1><span aria-hidden="true">wA11y</span><span class="screen-reader-text">Wally</span> - <?php _e( 'The Web Accessibility Toolbox', 'wa11y' ); ?></h1>

			<form method="post" action="options.php">
				<?php

				// Handle the settings
				settings_fields( 'wa11y_settings' );

				?>
				<div id="poststuff">

					<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
						<div id="post-body-content">

							<div id="postbox-container-1" class="postbox-container">
								<?php

								// Print side meta boxes
								do_meta_boxes( $this->options_page, 'side', array() );

								submit_button( __( 'Save Your Changes', 'wa11y' ), 'primary', 'save_wa11y_settings', false, array( 'id' => 'save-wa11y-settings-side' ) );

								?>
							</div> <!-- #postbox-container-1 -->

							<div id="postbox-container-2" class="postbox-container">
								<?php

								// Print normal meta boxes
								do_meta_boxes( $this->options_page, 'normal', array() );

								// Print advanced meta boxes
								do_meta_boxes( $this->options_page, 'advanced', array() );

								// Print the submit button
								submit_button( __( 'Save Your Changes', 'wa11y' ), 'primary', 'save_wa11y_settings', false, array( 'id' => 'save-wa11y-settings-bottom' ) );

								?>
							</div> <!-- #postbox-container-2 -->

						</div> <!-- #post-body-content -->
					</div> <!-- #post-body -->

				</div> <!-- #poststuff -->

			</form>

		</div> <!-- .wrap -->
		<?php

	}

	/**
	 * Add meta boxes to the options page.
	 *
	 * @since   1.0.0
	 */
	public function add_options_meta_boxes() {

		// Get our saved settings
		$settings = wa11y()->get_settings();

		// About this Plugin
		add_meta_box( 'wa11y-about-mb', sprintf( __( 'About %s', 'wa11y' ), 'wA11y' ), array( $this, 'print_options_meta_boxes' ), $this->options_page, 'side', 'core', $settings );

		// About this wA11y.org
		add_meta_box( 'wa11y-about-org-mb', sprintf( __( 'About %s', 'wa11y' ), 'wA11y.org' ), array( $this, 'print_options_meta_boxes' ), $this->options_page, 'side', 'core', $settings );

		// Spread the Love
		add_meta_box( 'wa11y-promote-mb', __( 'Spread the Love', 'wa11y' ), array( $this, 'print_options_meta_boxes' ), $this->options_page, 'side', 'core', $settings );

		// tota11y Settings
		add_meta_box( 'wa11y-tota11y-settings-mb', 'tota11y', array( $this, 'print_options_meta_boxes' ), $this->options_page, 'normal', 'core', $settings );

		// WAVE Settings
		add_meta_box( 'wa11y-wave-settings-mb', 'WAVE (' . __( 'Web Accessibility Evaluation Tool', 'wa11y' ) . ')', array( $this, 'print_options_meta_boxes' ), $this->options_page, 'normal', 'core', $settings );

	}

	/**
	 * Print the meta boxes for the options page.
	 *
	 * @since   1.0.0
	 * @param	array - $post - information about the current post, which is empty because there is no current post on a settings page
	 * @param	array - $metabox - information about the metabox
	 */
	public function print_options_meta_boxes( $post, $metabox ) {

		// Get the saved settings passed to the meta boxes
		$settings = isset( $metabox[ 'args' ] ) ? $metabox[ 'args' ] : array();

		// Get enable tools settings
		$enabled_tools_settings = isset( $settings[ 'enable_tools' ] ) ? $settings[ 'enable_tools' ] : array();

		// Get the user roles
		$user_roles = get_editable_roles();

		switch( $metabox[ 'id' ] ) {

			// About wA11y
			case 'wa11y-about-mb':

				// Print the plugin name (with link to site)
				?><p><?php printf( __( '%s is a toolbox of resources to help you improve the accessibility of your WordPress website.', 'wa11y' ), 'wA11y' ); ?></p><?php

				// Print the plugin version and author (with link to site)
				?><p>
					<strong><?php _e( 'Version', 'wa11y' ); ?>:</strong> <?php echo WA11Y_VERSION; ?><br />
					<strong><?php _e( 'Author', 'wa11y' ); ?>:</strong> <a href="https://bamadesigner.com/" target="_blank">Rachel Carden</a>
				</p><?php

				break;

			// About wA11y.org
			case 'wa11y-about-org-mb':

				// Let users know about the website
				?><p><?php printf( __( '%s is a new community initiative to contribute to web accessibility by providing information, education, resources, and tools.', 'wa11y' ), '<a href="https://wa11y.org">wA11y.org</a>' ); ?></p>
				<p><?php printf( __( 'If you\'re interested in joining the %1$s community, and would like to contribute to its growth, please subscribe at %2$s.', 'wa11y' ), 'wA11y.org', '<a href="https://wa11y.org">https://wa11y.org</a>' ); ?></p><?php
				break;

			// Promote
			case 'wa11y-promote-mb':
				?>
				<p class="star">
					<a href="<?php echo WA11Y_PLUGIN_URL; ?>" title="<?php esc_attr_e( 'Give the plugin a good rating', 'wa11y' ); ?>" target="_blank"><span class="dashicons dashicons-star-filled"></span> <span class="promote-text"><?php _e( 'Give the plugin a good rating', 'wa11y' ); ?></span></a>
				</p>
				<p class="twitter">
					<a href="https://twitter.com/bamadesigner" title="<?php _e( 'Follow bamadesigner on Twitter', 'wa11y' ); ?>" target="_blank"><span class="dashicons dashicons-twitter"></span> <span class="promote-text"><?php _e( 'Follow me on Twitter', 'wa11y' ); ?></span></a>
				</p>
				<p class="donate">
					<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZCAN2UX7QHZPL&lc=US&item_name=Rachel%20Carden%20%28wA11y%29&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted" title="<?php esc_attr_e( 'Donate a few bucks to the plugin', 'wa11y' ); ?>" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" alt="<?php esc_attr_e( 'Donate', 'wa11y' ); ?>" /> <span class="promote-text"><?php _e( 'and buy me a coffee', 'wa11y' ); ?></span></a>
				</p>
				<?php
				break;

			// tota11y Settings
			case 'wa11y-tota11y-settings-mb':

				// Get tota11y settings
				$tota11y_settings = wa11y()->get_settings( 'tota11y' );

				?><div class="wa11y-tool-settings tota11y-tool-settings">

					<div class="tool-header">
						<input class="tool-checkbox" id="tota11y" type="checkbox" name="wa11y_settings[enable_tools][]" value="tota11y"<?php checked( is_array( $enabled_tools_settings ) && in_array( 'tota11y', $enabled_tools_settings) ); ?> />
						<label class="tool-label" for="tota11y"><?php printf( __( 'Enable %s', 'wa11y' ), 'tota11y' ); ?> <span class="lighter thinner">[v<?php echo WA11Y_TOTA11Y_VERSION; ?>]</span></label>
						<p class="tool-desc"><?php printf( __( '%1$s%2$s%3$s is an accessibility visualization toolkit provided by your friends at %4$s%5$s%6$s. It is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.', 'wa11y' ), '<a href="http://khan.github.io/tota11y/" target="_blank">', 'tota11y', '</a>', '<a href="http://khanacademy.org/" target="_blank">', 'Khan Academy', '</a>' ); ?></p>
					</div> <!-- .tool-header -->

					<div class="tool-body">

						<h3 class="tool-subheader"><?php printf( __( 'Why %s Is Awesome', 'wa11y' ), 'tota11y' ); ?></h3>
						<p><?php printf( __( '%1$s consists of several plugins, each with their own functionality, that works to help you visualize accessibility violations (and successes) while also educating you on best practices. Beyond simply pointing out errors, many %2$s plugins also suggest ways to fix these violations - specifically tailored to your document.', 'wa11y' ), 'tota11y', 'tota11y' ); ?></p>

						<h3 class="tool-subheader"><?php printf( __( 'Best Uses For %s', 'wa11y' ), 'tota11y' ); ?></h3>
						<p><?php printf( __( '%1$s is built to scan, and provide feedback on, an entire document so this tool is best used to evaluate pages on the front-end of your site. <strong>%2$s can scan any page that you can load</strong> so the page does not have to be published.', 'wa11y' ), 'tota11y', 'tota11y' ); ?></p>

						<h3 class="tool-subheader"><?php _e( 'Other Resources', 'wa11y' ); ?></h3>
						<p><?php

							// Translate the link anchor title
							$extension_anchor_title = sprintf( __( 'View the available %1$s %2$s extensions', 'wa11y' ), 'tota11y', 'Chrome' );

							// Print the message
							printf( __( 'There are several %1$s%2$s extensions%3$s available.', 'wa11y' ), '<a href="https://chrome.google.com/webstore/search/tota11y?hl=en" target="_blank" title="' . $extension_anchor_title . '">', 'Chrome', '</a>' );

						?></p>

					</div> <!-- .tool-body -->

					<p class="tool-settings-warning"><?php printf( __( 'If no user roles are selected or user capability is provided, %s will load for all logged-in users.', 'wa11y' ), 'tota11y' ); ?></p>

					<fieldset>
						<ul id="wa11y-tota11y-settings-list" class="tool-settings-list"><?php

							if ( ! empty( $user_roles ) ) :

								// Get the defined 'load_user_roles'
								$load_user_roles = array();
								if ( ! empty( $tota11y_settings[ 'load_user_roles' ] ) && is_array( $tota11y_settings[ 'load_user_roles' ] ) ) {
									$load_user_roles = $tota11y_settings[ 'load_user_roles' ];
								}

								?>
								<li>
									<label class="tool-option-header" for="tota11y-user-roles"><?php printf( __( 'Only load %s for specific user roles', 'wa11y' ), 'tota11y' ); ?>:</label>
									<select class="tool-option-select" id="tota11y-user-roles" name="wa11y_settings[tools][tota11y][load_user_roles][]" multiple="multiple">
										<?php foreach( $user_roles as $user_role_key => $user_role ) : ?>
											<option value="<?php echo $user_role_key; ?>"<?php selected( is_array( $load_user_roles ) && in_array( $user_role_key, $load_user_roles ) ); ?>><?php echo $user_role[ 'name' ]; ?></option>
										<?php endforeach; ?>
									</select>
								</li>
							<?php endif; ?>

							<li>
								<label class="tool-option-header" for="tota11y-user-capability"><?php printf( __( 'Only load %s for a specific user capability', 'wa11y' ), 'tota11y' ); ?>:</label>
								<input class="tool-setting-text" id="tota11y-user-capability" type="text" name="wa11y_settings[tools][tota11y][load_user_capability]" value="<?php echo isset( $tota11y_settings[ 'load_user_capability' ] ) ? $tota11y_settings[ 'load_user_capability' ] : null; ?>" />
								<span class="tool-option-side-note">e.g. view_tota11y</span>
							</li>

							<li>
								<label class="tool-option-header" for="tota11y-admin"><?php printf( __( 'Load %s in the admin', 'wa11y' ), 'tota11y' ); ?>:</label>
								<input class="tool-option-checkbox" id="tota11y-admin" type="checkbox" name="wa11y_settings[tools][tota11y][load_in_admin]" value="1"<?php checked( isset( $tota11y_settings[ 'load_in_admin' ] ) && $tota11y_settings[ 'load_in_admin' ] > 0 ); ?> />
								<span class="tool-option-side-note"><?php printf( __( 'This will load the %s button on all pages in the admin to give you a glimpse of admin accessibility.', 'wa11y' ), 'tota11y' ); ?></span>
							</li>

						</ul>
					</fieldset>
				</div> <!-- .wa11y-tool-settings -->
				<?php

				break;

			// WAVE Settings
			case 'wa11y-wave-settings-mb':

				// Get WAVE settings
				$wave_settings = wa11y()->get_settings( 'wave' );

				// Have to disable the admin WAVE evaluation if SSL because WAVE isnt SSL
				$disable_admin_wave = is_ssl();

				?><div class="wa11y-tool-settings wave-tool-settings">

					<div class="tool-header">
						<input class="tool-checkbox" id="wave" type="checkbox" name="wa11y_settings[enable_tools][]" value="wave"<?php checked( is_array( $enabled_tools_settings ) && in_array( 'wave', $enabled_tools_settings) ); ?> />
						<label class="tool-label" for="wave"><?php printf( __( 'Enable %1$s %2$s(Web Accessibility Evaluation Tool)%3$s', 'wa11y' ), '<span class="wave-red">WAVE</span>', '<span class="thinner wave-gray">', '</span>' ); ?></label>
						<p class="tool-desc"><?php printf( __( '%1$s%2$s%3$s is a free evaluation tool provided by %4$s%5$s (Web Accessibility In Mind)%6$s. It can be used to evaluate a live website for a wide range of accessibility issues. When this tool is enabled, a \'View %7$s evaluation\' button will be placed in your WordPress admin bar to help you quickly evaluate the page you\'re viewing.', 'wa11y' ), '<a href="http://wave.webaim.org/" target="_blank">', 'WAVE', '</a>', '<a href="http://webaim.org/" target="_blank">', 'WebAIM', '</a>', 'WAVE' ); ?></p>
					</div> <!-- .tool-header -->

					<div class="tool-body">

						<h3 class="tool-subheader"><?php printf( __( 'Why %s Is Awesome', 'wa11y' ), 'WAVE' ); ?></h3>
						<p><?php printf( __( '%1$s provides a simple, straight forward evaluation of any public webpage and allows you to filter the evaluation by standard: Full, Section 508, and WCAG 2.0 A and AA. If your page does contain errors, the report provides documentation to explain the issue and how to fix it. %2$s also provides a color contrast checker.', 'wa11y' ), 'WAVE', 'WAVE' ); ?></p>

						<h3 class="tool-subheader"><?php printf( __( 'Best Uses For %s', 'wa11y' ), 'WAVE' ); ?></h3>
						<p><?php printf( __( '%1$s is built to scan, and provide feedback on, an entire document so this tool is best used to evaluate pages on the front-end of your site. A big difference between %2$s and %3$s is that <strong>%4$s can only evaluate publicly-accessible pages</strong> so it\'s not ideal for local/staging environments or content that is password-protected.', 'wa11y' ), 'WAVE', 'WAVE', 'tota11y', 'WAVE' ); ?></p>

						<h3 class="tool-subheader"><?php _e( 'Other Resources', 'wa11y' ); ?></h3>
						<p><?php

							// Translate the link anchor title
							$extension_anchor_title = sprintf( __( 'Learn more about the %1$s %2$s extension', 'wa11y' ), 'WAVE', 'Chrome' );
							$api_anchor_title = sprintf( __( 'Learn more about the %s API', 'wa11y' ), 'WAVE' );

							// Print the message
							printf( __( '%1$s also offers %2$sa %3$s extension%4$s and %5$san API%6$s for those who need more in-depth usage.', 'wa11y' ), 'WAVE', '<a href="http://wave.webaim.org/extension/" target="_blank" title="' . $extension_anchor_title . '">', 'Chrome', '</a>', '<a href="http://wave.webaim.org/api/" target="_blank" title="' . $api_anchor_title . '">', '</a>' );

						?></p>

					</div> <!-- .tool-body -->

					<p class="tool-settings-warning"><?php printf( __( 'If no user roles are selected or user capability is provided, %s will display for all logged-in users.', 'wa11y' ), 'WAVE' ); ?></p>

					<fieldset>
						<ul id="wa11y-wave-settings-list" class="tool-settings-list"><?php

							// If we have user roles
							if ( ! empty( $user_roles ) ) :

								// Get the defined 'load_user_roles'
								$load_user_roles = array();
								if ( ! empty( $wave_settings[ 'load_user_roles' ] ) && is_array( $wave_settings[ 'load_user_roles' ] ) ) {
									$load_user_roles = $wave_settings[ 'load_user_roles' ];
								}

								?>
								<li>
									<label class="tool-option-header"><?php printf( __( 'Only show %s for specific user roles', 'wa11y' ), 'WAVE' ); ?>:</label>
									<select class="tool-option-select" name="wa11y_settings[tools][wave][load_user_roles][]" multiple="multiple">
										<?php foreach( $user_roles as $user_role_key => $user_role ) : ?>
											<option value="<?php echo $user_role_key; ?>"<?php selected( is_array( $load_user_roles ) && in_array( $user_role_key, $load_user_roles ) ); ?>><?php echo $user_role[ 'name' ]; ?></option><?php
										endforeach; ?>
									</select>
								</li>
							<?php endif; ?>

							<li><label class="tool-option-header" for="wave-user-capability"><?php printf( __( 'Only show %s for a specific user capability', 'wa11y' ), 'WAVE' ); ?>:</label> <input class="tool-setting-text" id="wave-user-capability" type="text" name="wa11y_settings[tools][wave][load_user_capability]" value="<?php echo isset( $wave_settings[ 'load_user_capability' ] ) ? $wave_settings[ 'load_user_capability' ] : null; ?>" /> <span class="tool-option-side-note">e.g. view_wave</span></span></li>

							<li><label class="tool-option-header" for="wave-toolbar"><?php printf( __( 'Add link to %s evalution to the WordPress toolbar', 'wa11y' ), 'WAVE' ); ?>:</label>
								<input class="tool-option-checkbox" id="wave-toolbar" type="checkbox" name="wa11y_settings[tools][wave][load_in_toolbar]" value="1"<?php checked( isset( $wave_settings[ 'load_in_toolbar' ] ) && $wave_settings[ 'load_in_toolbar' ] > 0 ); ?> />
								<span class="tool-option-side-note"><?php _e( "In the front-end, this will allow you to quickly evaluate any page that you're viewing. In the admin, the link will only display on screens where you are editing a post or a page.", 'wa11y' ); ?> <span class="highlight-red"><strong><?php printf( __( '%s can only evaluate publicly-accessible pages.', 'wa11y' ), 'WAVE' ); ?></strong></span></span>
							</li>

							<li<?php echo $disable_admin_wave ? ' class="disabled"' : null; ?>><label class="tool-option-header" for="wave-admin"><?php printf( __( 'Display %s evaluation when editing content', 'wa11y' ), 'WAVE' ); ?>:</label>
								<input class="tool-option-checkbox" id="wave-admin" type="checkbox" name="wa11y_settings[tools][wave][load_admin_edit]" value="1"<?php checked( ! $disable_admin_wave && isset( $wave_settings[ 'load_admin_edit' ] ) && $wave_settings[ 'load_admin_edit' ] > 0 ); disabled( $disable_admin_wave ); ?> />
								<?php

								if ( $disable_admin_wave ) { ?>
									<span class="tool-option-disabled-message"><?php printf( __( 'At this time, the %1$s evaluation iframe cannot be embedded on a site using SSL because the %2$s site does not use SSL.', 'wa11y' ), 'WAVE', 'WAVE' ); ?></span>
								<?php } else { ?>
									<span class="tool-option-side-note"><?php printf( __( 'The %1$s evaluation will only display on screens where you are editing a post or a page. <strong>%1$s can only evaluate publicly-accessible pages.</strong>', 'wa11y' ), 'WAVE', 'WAVE' ); ?> <span class="highlight-red"><strong><?php printf( __( '%s can only evaluate publicly-accessible pages.', 'wa11y' ), 'WAVE' ); ?></strong></span></span>
								<?php }

								?>
							</li>
						</ul>
					</fieldset>
				</div><?php

				break;

		}

	}

	/**
	 * Adds a settings link to the plugins table.
	 *
	 * @since   1.0.0
	 * @param	$actions - an array of plugin action links
	 * @param 	$plugin_file - path to the plugin file
	 * @param	$context - The plugin context. Defaults are 'All', 'Active', 'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use', 'Drop-ins', 'Search'
	 * @return 	array - the links info after it has been filtered
	 */
	public function add_plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {

		// Add link to our settings page
		$actions[ 'settings' ] = '<a href="' . add_query_arg( array( 'page' => 'wa11y' ), admin_url( 'options-general.php' ) ) . '" title="' . sprintf( esc_attr__( 'Visit the %s settings page', 'wa11y' ), 'wA11y' ) . '">' . __( 'Settings' , 'wa11y' ) . '</a>';

		return $actions;
	}

}

/**
 * Returns the instance of our wA11y_Admin class.
 *
 * Will come in handy when we need to access the
 * class to retrieve data throughout the plugin.
 *
 * @since	1.0.0
 * @access	public
 * @return	wA11y_Admin
 */
function wa11y_admin() {
	return wA11y_Admin::instance();
}

// Admin all the things
wa11y_admin();