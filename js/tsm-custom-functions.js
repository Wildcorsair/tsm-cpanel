(function($) {
    /**
     * The event handler for change brand in the "Brands" control
     */
    $( '#order-brand-id' ).on( 'change', this, function() {
        var i,
            option_list = '<option value="0">Select the model</option>',
            brand_id = $( this ).val(),
            $device_full_price = $('#device-full-price'),
            $device_price = $('#device-price'),
            $model_list = $( '#model-id' );
        $.ajax( {
            url: GVARS.ajaxurl,
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'select_models_by_brand_id',
                security: GVARS.security,
                brand_id: brand_id 
            },
            success: function( response ) {
                $model_list.empty();
                if (response.length > 0) {
                    for ( i = 0; i < response.length; i++ ) {
                        option_list += '<option value="' + response[i].id + '|' 
                                     + response[i].full_price + '">' 
                                     + response[i].model_name + '</option>';
                    }
                    $model_list.append(option_list);
                } else {
                    $model_list.append('<option value="0">No models of this brand</option>');
                }
                $device_full_price.val(0);
                $device_price.text(0);
            }
        } );
        // Reset checked device condition
        uncheck_condition();
        // Check end change "Get a Quote" button state (enable/disable)
        change_button_state();
    } );
    
    /**
     * The event handler for change model in the "Models" control
     */
    $( '#model-id' ).on( 'change', this, function() {       
        var model_data = $(this).val(),
            model_id,
            model_price,
            model_data_parts,
            $device_full_price = $( '#device-full-price' ),
            $device_price = $( '#device-price' );
            
            model_data_parts = model_data.split( '|' );
            model_id = model_data_parts[0];
            if ( model_id > 0 ) {
                model_price = model_data_parts[1];
            } else {
                model_price = 0;
            }
            $device_full_price.val(model_price);
            $device_price.text(model_price);
            // Reset checked device condition
            uncheck_condition();
            // Check end change "Get a Quote" button state (enable/disable)
            change_button_state();
    } );
    
    /**
     * The event handler for clicking on 'Device condition' radio-buttons
     */
    $( '#device-condition-controls input[type="radio"]' ).on( 'click', this, function() {
        var percent = $(this).val(),
            $full_price_elem = $( '#device-full-price' ),
            $price_elem = $( '#device-price' ),
            full_price = $full_price_elem.val(),
            counted_price;
            
            counted_price = parseFloat(full_price * percent / 100);
            $price_elem.text( counted_price );
    } );
    
    
    $( '#user-email' ).on( 'keyup', this, function() {
        change_button_state();
    } );
    
    $( '#user-email' ).on( 'change', this, function() {
        change_button_state();
    } );
   
    /**
     * The event handler for clicking on 'Get a Quote' button in the public page
     */
    $( '#tsm-order-submit-btn' ).on( 'click', this, function() {
        var brand_id = $( '#order-brand-id' ).val(),
            model_id,
            model_data = $( '#model-id' ).val(),
            cond_percent = $( 'input[name="condition"]:checked' ).val(),
            full_price = $( '#device-full-price' ).val(),
            price = $( '#device-price' ).text(),
            email = $( '#user-email').val();
            
            model_id = model_data.split( '|' )[0];
            cond_percent = cond_percent !== undefined ? cond_percent : '100';

            $.ajax( {
                url: GVARS.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_new_order',
                    security: GVARS.security,
                    brand_id: brand_id,
                    model_id: model_id,
                    full_price: full_price,
                    cond_percent: cond_percent,
                    price: price,
                    email: email
                },
                success: function( response ) {
                    if ( response.status == 'success' ) {
                        
                        var message_frame = $( '#tsm-message-frame' );
                            message_frame.text( 'Your order was stored.' );
                            message_frame.show();
                    }
                }
            } );
    } );

    /**
     * Checking for correct user email
     * @param {String} email
     * @returns {Boolean}
     */
    function is_email(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }
    
    /**
     * Checking for zero device price
     * @param {Float} price
     * @returns {Boolean}
     */
    function is_price(price) {
        if (parseFloat( price ) > 0 ) {
            return true;
        }
        return false;
    }

    /**
     * Check entry data (selected brand id, user email and device price)
     * @returns {Boolean}
     */
    function check_order_entry_data() {
        var email = $( '#user-email' ).val(),
            brand_id = $( '#order-brand-id' ).val(),
            price = $( '#device-price' ).text();
       
        if ( !is_email(email) ) {
            return false;
        }
        if ( brand_id === '0' ) {
            return false;
        }
        if ( !is_price( price ) ) {
            return false;
        }
        return true;
    }

    /**
     * Check entry data and change "Get a Quote" button state
     * @returns {Boolean}
     */
    function change_button_state() {
        if ( check_order_entry_data() ) {
            $( '#tsm-order-submit-btn' ).attr( 'disabled', false );
        } else {
            $( '#tsm-order-submit-btn' ).attr( 'disabled', true );
        }
        return false;
    }
    
    /**
     * Uncheck "Device condition" radio-button
     * @returns {Boolean}
     */
    function uncheck_condition() {
        $( '#device-condition-controls input[type="radio"]:checked' ).prop( 'checked', false );
        return false;
    }
})(jQuery);