<?php

/**
 * Common functions module
 */

function tsm_get_CRUD_message() {
    $message_data = array();
    if ( isset( $_GET['save'] ) ) {
        $save_status = sanitize_text_field( $_GET['save'] );

        if ( $save_status == 'complited' ) {
            $message_data['block_visibility'] = 'block';
            $message_data['classes'] = 'notice notice-success';
            $message_data['message'] = 'Changes successfully saved.';
        }

        if ( $save_status == 'failure' ) {
            $message_data['block_visibility'] = 'block';
            $message_data['classes'] = 'error notice-error';
            $message_data['message'] = 'Fill empty fields.';
        }
    } else {
        $message_data['block_visibility'] = 'none';
        $message_data['classes'] = '';
        $message_data['message'] = '';
    }
    return $message_data;
}