<?php

/**
 * Module provides shortcode features
 */

function tsm_get_manufacturers_list() {
    global $wpdb;
    
    $rows = $wpdb->get_results("SELECT `id`, `manufacturer_name` FROM `{$wpdb->prefix}manufacturers` LIMIT 0, 100");
    if ( !empty( $rows ) ) {
        return $rows;
    } else {
        return false;
    }
}

function tsm_include_public_interface($atts, $content=null) {  
    $atts = shortcode_atts(
            array(
                'title' => '',
                'currency' => '$',
            ),
            $atts
    );
    
    $models = tsm_get_manufacturers_list();
    if ( !is_array( $models ) ) {
        return false;
    }

    $str = '<option selected value="0">Select the brand</option>';
    foreach ($models as $item) {
        $str .= "<option value='{$item->id}'>{$item->manufacturer_name}</option>";
    }
    return "
        <div class='wrap tsm-public-form'>
            <h2>{$atts['title']}</h2>
            <div id='tsm-message-frame'></div>
            <div class='meta-row'>
                <div class='meta-th'>
                    <label for='brand-id'>Brand</label>
                </div>
                <div class='meta-td'>
                    <select id='order-brand-id' name='brand_id'>
                        {$str}
                    </select>
                </div>
            </div>
            <div class='meta-row'>
                <div class='meta-th'>
                    <label for='model-id'>Model</label>
                </div>
                <div class='meta-td'>
                    <select id='model-id' name='model_id'>
                       <option selected value='0'>Select the model</option>
                    </select>
                </div>
            </div>
            <div class='meta-row'>
                <div class='meta-th'>
                    <label>Condition</label>
                </div>
                <div class='meta-td'>
                    <div id='device-condition-controls'>
                        <input id='perfect-device-condition' type='radio' name='condition' value='100'>
                        <label for='perfect-device-condition'>Perfect</label>
                        <input id='good-device-condition' type='radio' name='condition' value='75'>
                        <label for='good-device-condition'>Good</label>
                        <input id='fair-device-condition' type='radio' name='condition' value='50'>
                        <label for='fair-device-condition'>Fair</label>
                        <input id='dead-device-condition' type='radio' name='condition' value='25'>
                        <label for='dead-device-condition'>Dead</label>
                    </div>
                </div>
            </div>
            <div class='meta-row'>
                <div class='meta-th'>
                    <label>Price</label>
                </div>
                <div class='meta-td'>
                    <input id='device-full-price' type='hidden' name='device_full_price'>
                    <!--<input id='device-price' type='text' name='device_full_price' readonly='readonly'>-->
                    <div id='price-container'><span id='currency'>{$atts['currency']}</span><span id='device-price'>0</span></div>
                </div>
            </div>
            <div class='meta-row'>
                <div class='meta-th'>
                    <label>Your e-mail</label>
                </div>
                <div class='meta-td'>
                    <input id='user-email' type='text' name='user_email' value='' placeholder='yourname@example.com'>
                </div>
            </div>
            <div class='meta-row'>
                <button id='tsm-order-submit-btn' disabled>Get a Quote</button>
            </div>
        </div>
    ";
}

add_shortcode( 'tsm_public_order_form', 'tsm_include_public_interface' );