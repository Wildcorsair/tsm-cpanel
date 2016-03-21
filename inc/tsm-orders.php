<?php

/**
 * Adds menu pages of orders
 */
function tsm_order_menu() {
    add_menu_page(
        'Orders',
        'All orders',
        'manage_options',
        'tsm-all-orders',
        'tsm_show_orders',
        'dashicons-cart',
        100
    );
    add_submenu_page(
        'tsm-all-orders',
        'Orders: Add order',
        'Add order',
        'manage_options',
        'tsm-cpanel-order-edit',
        'tsm_create_new_order'
    );
}

/**
 * Shows all orders
 * 
 * @global object $wpdb
 */
function tsm_show_orders() {
    global $wpdb;
    $query = "SELECT `ord`.`id`,
                     `manufacturer_name`,
                     `model_name`,
                     `user_email`,
                     `device_price`,
                     `order_status`
              FROM `{$wpdb->prefix}orders` `ord`
              LEFT JOIN `{$wpdb->prefix}manufacturers` `mnf` ON `ord`.`brand_id` = `mnf`.`id`
              LEFT JOIN `{$wpdb->prefix}models` `mdl` ON `ord`.`model_id` = `mdl`.`id`
              LIMIT 0, 100";
    $rows = $wpdb->get_results($query);
    require( PLUGINS_DIR . 'templates/cpanel-orders-template.php' );
}

/**
 * Opens the form for new order
 */
function tsm_create_new_order() {
    require( PLUGINS_DIR . 'inc/tsm-order-manage.php' );
}

/**
 * Gets model list for brand identifier.
 * 
 * @global object $wpdb
 * @return json
 */
function tsm_get_models_by_brand_id() {
    global $wpdb;
    
    if ( !check_ajax_referer( 'tsm_security_nonce', 'security' ) ) {
        return wp_send_json_error( 'Invalid security key!' );
    }
    
    /*if ( !current_user_can( 'manage_options' ) ) {
        return wp_send_json_error( 'Sorry, access denied!' );
    }*/
    
    $brand_id = intval($_GET['brand_id']);
    $rows = $wpdb->get_results( $wpdb->prepare( "SELECT
                                                    `id`,
                                                    `model_name`,
                                                    `full_price` 
                                                 FROM `{$wpdb->prefix}models`
                                                 WHERE `brand_id` = %d AND `visibility` = 0",
                                                 $brand_id
    ) );
    return wp_send_json( $rows );
}

/**
 * Deletes order from database, returns the json data: status result and message
 * 
 * @global object $wpdb
 * @return json
 */
function tsm_delete_order_item() {
    global $wpdb;
    
    if ( !check_ajax_referer( 'tsm_security_nonce', 'security' ) ) {
        return wp_send_json_error( 'Invalid security key!' );
    }
    
    if ( !current_user_can( 'manage_options' ) ) {
        return wp_send_json_error( 'Sorry, access denied!' );
    }
    
    if ( isset( $_GET['rec_id'] )) {
        $rec_id = intval( $_GET['rec_id'] );
        $affected_rows = $wpdb->delete( "{$wpdb->prefix}orders", array( 'id' => $rec_id ), array( '%d' ) );
        if ( $affected_rows > 0 ) {
            return wp_send_json( array( 'delete_status' =>  'success', 'message' => 'Record was removed.' ) );
        } else {
            return wp_send_json( array( 'delete_status' =>  'error', 'message' => 'Can\'t to remove this order!' ) );
        }
    }
}

function tsm_save_new_order() {
    global $wpdb;
    
    if ( !check_ajax_referer( 'tsm_security_nonce', 'security' ) ) {
        return wp_send_json_error( 'Invalid security key!' );
    }
    
    if ( isset( $_POST['brand_id'] ) ) {
        $brand_id       = intval( $_POST['brand_id'] );
        $model_id       = intval( $_POST['model_id'] );
        $full_price     = floatval( $_POST['full_price'] );
        $cond_percent   = intval( $_POST['cond_percent'] );
        $price          = floatval( $_POST['price'] );
        $email          = sanitize_text_field( $_POST['email'] );
    }

    $result = $wpdb->insert( "{$wpdb->prefix}orders",
            array(
                'brand_id'          => $brand_id,
                'model_id'          => $model_id,
                'device_full_price' => $full_price,
                'cond_percent'      => $cond_percent,
                'device_price'      => $price,
                'user_email'        => $email,
                'order_status'      => 0
            ),
            array(
                '%d',
                '%d',
                '%f',
                '%d',
                '%f',
                '%s',
                '%d'
            )
    );
    if ( $result ) {
        return wp_send_json( array( 'status' => 'success' ) );
    } else {
        return wp_send_json_error( array( 'status' => 'error' ) );
    }
}

/*function tsm_select_models_by_brand_id() {
    global $wpdb;
    
    if ( !check_ajax_referer( 'tsm_security_nonce', 'security' ) ) {
        return wp_send_json_error( 'Invalid security key!' );
    }
    
    $brand_id = intval( $_GET['brand_id'] );
    return wp_send_json( array( 'status' => 'ok' ) );
}*/

/**
 * Appends or updates the order record
 */
if ( isset( $_POST['save_order'] ) ) {
    global $wpdb;

    if ( isset( $_POST['order_id'] ) || !empty( $_POST['order_id'] ) ) {
        $order_id = intval( $_POST['order_id'] );
    } else {
        $order_id = 0;
    }
    
    if ( !wp_verify_nonce($_POST['hjfv548sd9g1hf9w36f9v'], 'er7z247d3' ) ) {
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-order-edit&save=failure&code=4&order_id=' . $order_id );
        exit();
    }
    
    $brand_id           = intval( $_POST['brand_id'] );
    $model_id           = intval( $_POST['model_id'] );
    $device_full_price  = floatval( $_POST['device_full_price'] );
    $cond_percent       = ( intval( $_POST['condition'] ) > 0) ? intval( $_POST['condition'] ) : 100;
    $device_price       = floatval( $_POST['device_price'] );
    $user_email         = sanitize_text_field( $_POST['user_email'] );
    $status_id          = intval( $_POST['status_id'] );

    if ( empty( $user_email ) ) {
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-order-edit&save=failure&code=2&order_id=' . $order_id );
        exit();
    }
    
    if ( !filter_var( $user_email, FILTER_VALIDATE_EMAIL) ) {       
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-order-edit&save=failure&code=3&order_id=' . $order_id );
        exit();
    }
    
    if ( $model_id == 0 ) {
        header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-order-edit&save=failure&code=5&order_id=' . $order_id );
        exit();
    }
    
    $table = $wpdb->prefix . 'orders';
    $wpdb->replace(
        $table,
        array(
            'id'            => $order_id,
            'brand_id'      => $brand_id,
            'model_id'      => $model_id,
            'device_full_price' => $device_full_price,
            'cond_percent'  => $cond_percent,
            'device_price'  => $device_price,
            'user_email'    => $user_email,
            'order_status'  => $status_id,
        ), 
        array(
            '%d',
            '%d',
            '%d',
            '%f',
            '%d',
            '%f',
            '%s',
            '%d'
        )
    );
//    $wpdb->print_error();
//    die();
    header( 'Location: ' . get_permalink() . '?page=tsm-cpanel-order-edit&save=complited&order_id=' . $wpdb->insert_id );
    exit();
}

/**
 *  Add actions
 */
add_action( 'wp_ajax_delete_order_item', 'tsm_delete_order_item' );
add_action( 'wp_ajax_check_brand_item', 'tsm_get_models_by_brand_id' );
add_action('admin_menu', 'tsm_order_menu');

add_action('wp_ajax_select_models_by_brand_id', 'tsm_get_models_by_brand_id');
add_action('wp_ajax_nopriv_select_models_by_brand_id', 'tsm_get_models_by_brand_id');

add_action('wp_ajax_save_new_order', 'tsm_save_new_order');
add_action('wp_ajax_nopriv_save_new_order', 'tsm_save_new_order');