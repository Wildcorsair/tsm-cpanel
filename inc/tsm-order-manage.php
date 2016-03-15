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
    $rows = $wpdb->get_results( $wpdb->prepare( $query, $order_id ) );

    if ( !empty( $rows ) ) {
        $order = $rows[0];
    }

    // Select models of selected brand
    $query = "SELECT
                    `id`,
                    `model_name`,
                    `full_price`
              FROM `{$wpdb->prefix}models`
              WHERE `brand_id` = {$order->brand_id} AND `visibility` = 0
              LIMIT 0, 100";
    $devices = $wpdb->get_results($query);
} else {
    
    $caption = "Add order";

    $query = "SELECT
                    `id`,
                    `model_name`,
                    `full_price`
              FROM `{$wpdb->prefix}models`
              WHERE `brand_id` = {$manufacturers[0]->id} AND `visibility` = 0
              LIMIT 0, 100";
    $devices = $wpdb->get_results($query);
}

include PLUGINS_DIR . 'templates/edit-order-form.php';