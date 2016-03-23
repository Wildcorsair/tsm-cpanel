<?php
global $wpdb;

$message_data = tsm_get_CRUD_message();

// Select brands
$query = "SELECT `id`, `manufacturer_name` FROM `{$wpdb->prefix}manufacturers` LIMIT 0, 100";
$manufacturers = $wpdb->get_results($query);

/**
 * Prepares data and displays order create/edit form
 */ 
if ( isset( $_GET['order_id'] ) && !empty( $_GET['order_id'] ) ) {

    $order_id = intval( $_GET['order_id'] );
    $caption = "Edit order";

    if ( isset( $_SESSION['brand_id'] ) ) {
        $order['brand_id'] = $_SESSION['brand_id'];
        $order['model_id'] = $_SESSION['model_id'];
        $order['device_full_price'] = $_SESSION['device_full_price'];
        $order['cond_percent'] = $_SESSION['cond_percent'];
        $order['device_price'] = $_SESSION['device_price'];
        $order['user_email'] = $_SESSION['user_email'];
        $order['order_status'] = $_SESSION['status_id'];
        session_destroy();
    } else {
        // Select order data
        $query = "SELECT 
                    `id`,
                    `brand_id`,
                    `model_id`,
                    `device_full_price`,
                    `cond_percent`,
                    `device_price`,
                    `user_email`,
                    `order_status`
                  FROM `{$wpdb->prefix}orders`
                  WHERE `id` = %d
                  LIMIT 0, 1";
        $rows = $wpdb->get_results( $wpdb->prepare( $query, $order_id ), ARRAY_A );

        if ( !empty( $rows ) ) {
            $order = $rows[0];
        }  
    }

    // Select models of selected brand
    $query = "SELECT
                    `id`,
                    `model_name`,
                    `full_price`
              FROM `{$wpdb->prefix}models`
              WHERE `brand_id` = {$order['brand_id']} AND `visibility` = 0
              LIMIT 0, 100";
    $devices = $wpdb->get_results($query);
} else {
    
    $caption = "Add order";
    $order['model_id'] = $manufacturers[0]->id;
    
    if ( isset( $_SESSION['brand_id'] ) ) {
        $order['brand_id'] = $_SESSION['brand_id'];
        $order['model_id'] = $_SESSION['model_id'];
        $order['device_full_price'] = $_SESSION['device_full_price'];
        $order['cond_percent'] = $_SESSION['cond_percent'];
        $order['device_price'] = $_SESSION['device_price'];
        $order['user_email'] = $_SESSION['user_email'];
        $order['order_status'] = $_SESSION['status_id'];
        session_destroy();
    }
    
    $query = "SELECT
                    `id`,
                    `model_name`,
                    `full_price`
              FROM `{$wpdb->prefix}models`
              WHERE `brand_id` = {$order['model_id']} AND `visibility` = 0
              LIMIT 0, 100";
    $devices = $wpdb->get_results($query);
}

include PLUGINS_DIR . 'templates/edit-order-form.php';