<?php

/**
 * Provides the view for the options page.
 */

global $wa11y_options_page;
 
?><div id="wa11y-options" class="wrap options">

	<h2><span aria-hidden="true">Wa11y</span><span class="screen-reader-text">Wally</span></h2>
	
	<form method="post" action="options.php"><?php
		
		// Handle the settings
		settings_fields( 'wa11y_settings' );
		
		?><div id="poststuff">
		
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				<div id="post-body-content">
					
					<div id="postbox-container-1" class="postbox-container"><?php
						
						// Print side meta boxes
						do_meta_boxes( $wa11y_options_page, 'side', array() );
						
					?></div> <!-- #postbox-container-1 -->

					<div id="postbox-container-2" class="postbox-container"><?php
						
						// Print normal meta boxes
						do_meta_boxes( $wa11y_options_page, 'normal', array() );
						
						// Print advanced meta boxes
						do_meta_boxes( $wa11y_options_page, 'advanced', array() );
						
						// Print the submit button
						submit_button( 'Save Your Changes', 'primary', 'save_wa11y_settings', false, array( 'id' => 'save-wa11y-settings' ) );
									
					?></div> <!-- #postbox-container-2 -->
					
				</div> <!-- #post-body-content -->
			</div> <!-- #post-body -->	
			
		</div> <!-- #poststuff -->
		
	</form>
	
</div> <!-- .wrap -->