<?php

// if uninstall not called from the WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

// Delete plugin options
delete_option( 'wa11y_settings' );