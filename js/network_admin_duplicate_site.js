var MUCD_Admin = (function( window, document, $, undefined ) {
    'use strict';
    
    var l10n = window.mucd_config;
    var app = { ajaxurl: window.ajaxurl };

    app.init = function() {
        $.extend( app, l10n );

        console.log(app);

        // bind select2
        app.select2Init();

        // Logbox displaying
        $('#log-box').change(function() {
            if($(this).is(":checked")) {
                $('#log-path').prop('disabled', false);
            }   
            else {
                $('#log-path').prop('disabled', true);
            }    
        }).change();

        // Advanced Options fields displaying
        $('#show-advanced-options, #hide-advanced-options').click(function() {
             app.mucd_toggle_advanced_options ($(this).attr('id'));
        });
        
        // If display when user Post, Form reload with Advanced Options field displayed
        if($('#status-advanced-options').val() == 'show-advanced-options'){
            app.mucd_toggle_advanced_options ( $('#status-advanced-options').val());
        }
    };

    app.select2Init = function() {
        app.$select = $( '#mucd-site-source');
        
        app.$select.select2({
            placeholder : app.placeholder_text,
            minimumInputLength : 1,
            allowClear: true,
            width: '50%',
            ajax : {
                cache : false,
                url : app.ajaxurl,
                dataType : 'json',
                data : function( term, page ) {
                    return {
                        q : term,
                        action : 'mucd_fetch_sites',
                        nonce : app.nonce,
                    };
                },
                results : app.select2Data
            },
            initSelection : function( element, callback ) {
                var id = $(element).val();
                
                if ( '0' !== id ) {
                    return $.ajax({
                        url : app.ajaxurl,
                        dataType : 'json',
                        data : {
                            q : 'test',
                            action : 'mucd_fetch_sites',
                            nonce : app.nonce,
                            id : id
                        },
                    }).done(function( data ) {
                        var results = {
                            id : data.data[0].id,
                            text : data.data[0].text
                        };
                        callback( results );
                    });
                } else {
                    var results = {
                        id : 0,
                        text : app.placeholder_text
                    };
                    callback( results );
                }
            }
        });
    };
    
    app.select2Data = function( ajax_data, page, query ) {
        var items=[];
        
        $.each( ajax_data.data, function( i, item ) {
            var new_item = {
                'id' : item.id,
                'text' : item.text
            };
            
            items.push(new_item);
        });

        return { results: items };
    };

    // Function to controle toggle on Advanced Options fields
    app.mucd_toggle_advanced_options = function(value){
         $('#advanced-options').toggle();
         $('#hide-advanced-options, #show-advanced-options').toggle();
         $(this).hide();
         $('#status-advanced-options').val(value);
    };
    
    $( document ).ready( app.init );
    
    return app;
    
})( window, document, jQuery );