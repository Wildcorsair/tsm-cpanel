(function($) {

    $( '#message>button' ).after('<span class="sreen-reader-text">Hide this message</span>');
    
    // Message block doesn't remove if it was added by js, that's why this function is necessary
    $ ( 'body' ).on( 'click', '#message>button', function() {
        $(this).parent().remove();
    } );
    
    // The event handler for clicking on 'Delete' button in the table of models
    $( '.btn-model-delete' ).on( 'click', this, function( e ) {
        var rec_id = $(this).data( 'value' ),
            $btn = $(this);
            dialog_window( $btn, rec_id, 'delete_model_item' );
    });
    
    // The event handler for clicking on 'Delete' button in the table of orders
    $( '.btn-order-delete' ).on( 'click', this, function() {
            var rec_id = $(this).data( 'value' ),
            $btn = $(this);
            dialog_window( $btn, rec_id, 'delete_order_item' );
    } );
    
    // Displays dialog window
    function dialog_window(elem, rec_id, action) {
        $( '#dialog-confirm' ).dialog({
          resizable: false,
          height:140,
          modal: true,
          buttons: {
            "Yes": function() {
              remove_record( elem, rec_id, action );
              $( this ).dialog( "close" );
            },
            "No": function() {
              $( this ).dialog( "close" );
            }
          }
        });
    }
    
    /**
     * Delete record from DB and remove line from table on the page
     * @param {object} elem
     * @param {integer} rec_id
     * @param {string} action
     * @returns {undefined}
     */
    function remove_record( elem, rec_id, action ) {
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            dataType: 'json',
            data: {
                action: action,
                security: TSM_MODEL_LOC.security,
                rec_id: rec_id
            },
            success: function( response ) {
                $( '#message' ).remove();
                var message = '<div id="message" class="updated notice notice-success is-dismissible">'
                            + '<p>' + response.message
                            + '</p><button type="button" class="notice-dismiss"></button></div>';
                if ( response.delete_status == 'success' ) {
                    $( 'h1' ).after( message );
                    elem.parent().parent().remove();
                } else if ( response.delete_status == 'error' ) {
                    $( 'h1' ).after( message );
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    
    // The event handler for clicking on 'Brand' select into the create/edit order form
    $( '#order-brand-id' ).on( 'change', this, function() {
        var i,
            $brand_list = $(this),
            $model_list = $( '#model-id' ),
            brand_id = $brand_list.val(),
            option_list = '';
        
        $.ajax( {
            url: ajaxurl,
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'check_brand_item',
                security: TSM_MODEL_LOC.security,
                brand_id: brand_id  
            },
            success: function( response ) {
                $model_list.empty();
                if ( response.length > 0 ) {
                    for (i = 0; i < response.length; i++ ) {
                        option_list += '<option value="' + response[ i ].id + '|' 
                                    + response[ i ].full_price + '">'
                                    + response[ i ].model_name + '</option>';
                    }
                    $( '#device-full-price' ).val(response[ 0 ].full_price);
                    $( '#device-price' ).val(response[ 0 ].full_price);
                } else {
                    option_list += '<option value="0">No models of this brand</option>';
                    $( '#device-full-price' ).val( 0 );
                    $( '#device-price' ).val( 0 );
                }
                $model_list.append( option_list );
                uncheck_condition();
            }
        } );
    } );
    
    // The event handler for change on the "Models" select
    $( '#model-id' ).on( 'change', this, function() {
        var model_data = $(this).val(),
            model_price,
            model_data_parts,
            $device_full_price = $( '#device-full-price' ),
            $device_price = $( '#device-price' );
            
            model_data_parts = model_data.split( '|' );
            model_price = model_data_parts[ '1' ];
            $device_full_price.val(model_price);
            $device_price.val(model_price);
            uncheck_condition();
    } );
    
    // The event handler for clicking on 'Condition' radio buttons into the create/edit order form
    $( '#device-condition-controls input[type="radio"]' ).on( 'click', this, function() {
        var percent = $(this).val(),
            $full_price_elem = $( '#device-full-price' ),
            $price_elem = $( '#device-price' ),
            full_price = $full_price_elem.val(),
            counted_price;

            counted_price = parseFloat(full_price * percent / 100);
            $price_elem.val( counted_price );
    } );
    
    // Uncheck "Condition" radio button, when was changed brand or model in the selects
    function uncheck_condition() {
        $( '#device-condition-controls input[type="radio"]:checked' ).prop( 'checked', false );
    }

})(jQuery);