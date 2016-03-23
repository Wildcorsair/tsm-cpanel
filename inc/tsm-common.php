<?php

/**
 * Common functions module
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
//            $message_data['classes'] = 'error notice-error';
            $message_data['classes'] = 'error settings-error notice is-dismissible';
        }
        $message_data['block_visibility'] = 'block';
    }
    return $message_data;
}