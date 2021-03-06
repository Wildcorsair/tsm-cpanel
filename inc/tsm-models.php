<?php

/**
 * Adds menu pages of devices
 */

function tsm_device_menu() {
    add_menu_page(
        'Devices',
            'All devices',
            'manage_options',
            'tsm-all-models',
            'tsm_show_devices',
            'dashicons-desktop',
            110
    );

    add_submenu_page(
        'tsm-all-models',
        'Devices: Add device',
        'Add device',
        'manage_options',
        'tsm-cpanel-model-edit',
        'create_new_model'
    );
}

/**
 * Opens the form for new model
 */
function create_new_model() {
    require( PLUGINS_DIR . 'inc/tsm-model-manage.php' );
}

/**
 * Selects and displays all devices
 * 
 * @global object $wpdb
 */
function tsm_show_devices() {
    global $wpdb;
    $query = "SELECT `mdl`.`id`,
                     `model_name`,
                     `manufacturer_name`,
                     `full_price`,
                     `visibility`
              FROM `{$wpdb->prefix}models` `mdl`
              LEFT JOIN `{$wpdb->prefix}manufacturers` `mnf` ON `mdl`.`brand_id` = `mnf`.`id`
              ORDER BY `model_name`
              LIMIT 0, 100";
    $rows = $wpdb->get_results($query);
    require( PLUGINS_DIR . 'templates/cpanel-models-template.php' );
}

/**
 * Deletes model from database, returns the json data: status result and message
 * 
 * @global object $wpdb
 * @return json
 */
function tsm_delete_model_item() {
    global $wpdb;
    
    if ( !check_ajax_referer( 'tsm_security_nonce', 'security' ) ) {
        return wp_send_json_error( 'Invalid security key!' );
    }
    
    if ( !current_user_can( 'manage_options' ) ) {
        return wp_send_json_error( 'Sorry, access denied!' );
    }
    
    $rec_id = intval($_GET['rec_id']);
    $column = $wpdb->get_col( $wpdb->prepare( "SELECT COUNT(*) AS `rows_count` FROM `{$wpdb->prefix}orders` WHERE `model_id` = %d", $rec_id ), 0 );
    if ( $column[0] == 0 ) {
        $wpdb->delete( "{$wpdb->prefix}models", array( 'id' => $rec_id ), array( '%d' ) );
        return wp_send_json( array( 'delete_status' =>  'success', 'message' => 'Record was removed.' ) );
    }
    return wp_send_json( array( 'delete_status' =>  'error', 'message' => 'Can\'t to remove this model! The model has dependence.' ) );
}

/**
 * Appends or updates the model record
 */
if ( isset( $_POST['save_model'] ) ) {
    global $wpdb;

    if ( isset( $_POST['model_id'] ) || !empty( $_POST['model_id'] ) ) {
        $model_id = intval( $_POST['model_id'] );
    } else {
        $model_id = 0;
    }

    if ( !wp_verify_nonce($_POST['s34qw98dotnkd4963df'], 'd54z7fg97' ) ) {
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-model-edit&save=failure&code=4&model_id=' . $model_id );
        exit();
    }
    
    $model_name = sanitize_text_field( $_POST['model_name'] );
    $manufacturer_ID = intval( $_POST['manufacturer'] );
    $full_price = floatval( $_POST['full_price'] );
    $model_visibility = intval( $_POST['model_visibility'] );
   
    if ( empty( $model_name ) || empty( $full_price ) ) {
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-model-edit&save=failure&code=2&model_id=' . $model_id );
        exit();
    }

    $table = $wpdb->prefix . 'models';
    $wpdb->replace(
        $table,
        array(
            'id' => $model_id,
            'model_name' => $model_name,
            'brand_id' => $manufacturer_ID,
            'full_price' => $full_price,
            'visibility' => $model_visibility
        ), 
        array(
            '%d',
            '%s',
            '%d',
            '%f',
            '%d'
        )
    );
    header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-model-edit&save=complited&model_id=' . $wpdb->insert_id );
    exit();
}

/**
 *  Add actions
 */

add_action('admin_menu', 'tsm_device_menu');
add_action( 'wp_ajax_delete_model_item', 'tsm_delete_model_item' );