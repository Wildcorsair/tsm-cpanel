<?php

/**
 * Common functions module
 */

/**
 * Returns message string, message type and class for message block in the order
 * or model form
 * @return array
 */
function tsm_get_CRUD_message() {
    $tsm_error_text_messages = require_once( PLUGINS_DIR . 'inc/tsm-text-messages.php' );

    $message_data['block_visibility'] = 'none';
    $message_data['classes'] = '';
    $message_data['message'] = '';
    
    if ( isset( $_GET['save'] ) ) {
        $save_status = sanitize_text_field( $_GET['save'] );

        if ( $save_status == 'complited' ) {
            $message_data['message'] = 'Changes successfully saved.';
            $message_data['classes'] = 'notice notice-success';
        }

        if ( $save_status == 'failure' ) {
            if ( isset( $_GET['code'] ) ) {
                $code = intval( $_GET['code'] ) - 1;
                    $message_data['message'] = isset( $tsm_error_text_messages[$code] ) 
                                                    ? $tsm_error_text_messages[$code] 
                                                    : $tsm_error_text_messages[0];
            }
            $message_data['classes'] = 'error settings-error notice is-dismissible';
        }
        $message_data['block_visibility'] = 'block';
    }
    return $message_data;
}

/**
 * Returns temporary data from session
 * @return array
 */
function get_temp_data() {
    $order = array();
    if ( isset( $_SESSION['brand_id'] ) ) {
        $order['id']                = $_SESSION['id'];
        $order['brand_id']          = $_SESSION['brand_id'];
        $order['model_id']          = $_SESSION['model_id'];
        $order['device_full_price'] = $_SESSION['device_full_price'];
        $order['cond_percent']      = $_SESSION['cond_percent'];
        $order['device_price']      = $_SESSION['device_price'];
        $order['user_email']        = $_SESSION['user_email'];
        $order['order_status']      = $_SESSION['status_id'];
        session_destroy();
    }
    return $order;
}