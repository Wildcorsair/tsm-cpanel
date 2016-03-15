<?php

global $wpdb;

if ( isset( $_GET['save'] ) ) {
    $save_status = sanitize_text_field( $_GET['save'] );
    
    if ( $save_status == 'complited' ) {
        $block_visibility = 'block';
        $classes = 'notice notice-success';
        $message = 'Changes successfully saved.';
    }
    
    if ( $save_status == 'failure' ) {
        $block_visibility = 'block';
        $classes = 'error notice-error';
        $message = 'Fill empty fields.';
    }
} else {
    $block_visibility = 'none';
    $message = '';
}

/**
 * Prepares data and displays model create/edit form
 */
if ( isset( $_GET['model_id'] ) ) {
    $caption = "Изменить модель";
    $model_id = intval( $_GET['model_id'] );
    $query = "SELECT `id`, `model_name`, `brand_id`, `full_price`, `visibility` FROM `{$wpdb->prefix}models` WHERE `id` = {$model_id} LIMIT 0, 1";
    $rows = $wpdb->get_results($query);
    if ( !empty( $rows ) ) {
        $record = $rows[0];    
    }
} else {
    $caption = "Добавить модель";
}
$query = "SELECT `id`, `manufacturer_name` FROM `{$wpdb->prefix}manufacturers` LIMIT 0, 100";
$manufacturers = $wpdb->get_results($query);
include PLUGINS_DIR . 'templates/edit-model-form.php';