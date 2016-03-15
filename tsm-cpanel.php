<?php
/**
 * @package TSM-Cpanel
 * @version 1.0
 */

/*
Plugin Name: TSM Control Panel
Description: This plugin displays the form for sale devices
Author: Vladimir Zakharchenko
Version: 1.0
*/

define( 'PLUGINS_DIR', plugin_dir_path(__FILE__) );

/**
 * Register custom styles for control panel page
 */
function custom_styles_registration() {
    wp_register_style( 'jquery-ui-theme', plugins_url('/tsm-cpanel/css/jqueryUI/themes/smoothness/theme.css'), array(), 'v1.11.4', 'all' );
    wp_enqueue_style( 'jquery-ui-theme' );
    wp_register_style( 'jquery-ui-styles', plugins_url('/tsm-cpanel/css/jqueryUI/jquery-ui.min.css'), array(), 'v1.11.4', 'all' );
    wp_enqueue_style( 'jquery-ui-styles' );
    wp_register_style( 'tsm-styles', plugins_url('/tsm-cpanel/css/tsm-styles.css'), array(), '20160311', 'all' );
    wp_enqueue_style( 'tsm-styles' );
}

/**
 * Register custom js functions for control panel page
 */
function custom_script_registration() {
    wp_register_script( 'jquery-ui-js', plugins_url('/tsm-cpanel/js/jqueryUI/jquery-ui.min.js'), array(), 'v1.11.4', true);
    wp_enqueue_script( 'jquery-ui-js' );
    wp_register_script( 'tsm-script', plugins_url('/tsm-cpanel/js/tsm-functions.js'), array(), '20160311', true);
    wp_enqueue_script( 'tsm-script' );
    wp_localize_script('tsm-script', 'TSM_MODEL_LOC', array(
        'security' => wp_create_nonce('tsm_security_nonce')
    ));
}

//add_action( 'wp_enqueue_scripts', 'custom_styles_registration' );
add_action( 'admin_enqueue_scripts', 'custom_styles_registration' );
add_action( 'admin_enqueue_scripts', 'custom_script_registration' );

// Includes parts of plugin
require( PLUGINS_DIR . 'inc/tsm-orders.php' );
require( PLUGINS_DIR . 'inc/tsm-models.php' );