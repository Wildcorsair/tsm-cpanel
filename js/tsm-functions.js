(function($) {

    $( '#message>button' ).after('<span class="sreen-reader-text">Hide this message</span>');
    
    // Message block doesn't remove if it was added by js, that's why this function is necessary
    $ ( 'body' ).on( 'click', '#message>button', function() {
        $(this).parent().remove();
    } );
    
    // The event handler for clicking on 'Delete' button in the table of models
    $( '.btn-delete' ).on( 'click', this, function( e ) {
        e.preventDefault();
        var rec_id = $(this).data( 'value' ),
            $btn = $(this);
        $( '#dialog-confirm' ).dialog({
          resizable: false,
          height:140,
          modal: true,
          buttons: {
            "Yes": function() {
              remove_model( $btn, rec_id );
              $( this ).dialog( "close" );
            },
            "No": function() {
              $( this ).dialog( "close" );
            }
          }
        });
    });
    
    function remove_model( elem, rec_id ) {
        $.ajax({
            url: ajaxurl,
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'delete_model_item',
                security: TSM_MODEL_LOC.security,
                rec_id: rec_id
            },
            success: function( response ) {
                $( '#message' ).remove();
                var message = '<div id="message" class="updated notice notice-success is-dismissible">' 
                            + '<p>' + response.message + '</p><button type="button" class="notice-dismiss"></button></div>';
                if ( response.delete_status == 'ok' ) {
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
            optionList = '';

        $.ajax({
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
                        optionList += '<option value="' + response[ i ].id + '|' 
                                    + response[ i ].full_price + '">'
                                    + response[ i ].model_name + '</option>';
                    }
                    $( '#device-full-price' ).val(response[ 0 ].full_price);
                    $( '#device-price' ).val(response[ 0 ].full_price);
                } else {
                    optionList += '<option value="0">No models of this brand</option>';
                    $( '#device-full-price' ).val( 0 );
                    $( '#device-price' ).val( 0 );
                }
                $model_list.append( optionList );
                uncheck_condition();
            }
        });
    } );
    
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
//            console.log(model_price);
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
//            console.log();
    } );
    
    function uncheck_condition() {
        $( '#device-condition-controls input[type="radio"]:checked' ).prop( 'checked', false );
    }
    
    /*function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }*/
    
})(jQuery);