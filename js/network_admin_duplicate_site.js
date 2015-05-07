window.MUCD_Admin = window.MUCD_Admin || {};

( function( window, document, $, app, undefined ) {
    'use strict';

    var l10n = window.mucd_config;

    app.cache = function() {
        app.$ = {};
        app.$.logBox           = $( document.getElementById( 'log-box' ) );
        app.$.logPath          = $( document.getElementById( 'log-path' ) );
        app.$.showHideAdvanced = $( '#show-advanced-options, #hide-advanced-options' );
        app.$.advOptions       = $( document.getElementById( 'advanced-options' ) );
        app.$.statusAdvOptions = $( document.getElementById( 'status-advanced-options' ) );
        app.$input             = $( document.getElementById( 'mucd-site-source' ) );
    };

    app.init = function() {
        // Store/cache our selectors
        app.cache();

        // Logbox displaying
        app.$.logBox.change(function() {
            var maybe_disabled = ! $(this).is( ":checked" );
            app.$.logPath.prop('disabled', maybe_disabled );
        }).change();

        // Advanced Options fields displaying
        app.$.showHideAdvanced.click(function() {
             app.toggle_advanced_options( $(this).attr('id') );
        });

        // If display when user Post, Form reload with Advanced Options field displayed
        if( app.$.statusAdvOptions.val() == 'show-advanced-options'){
            app.toggle_advanced_options ( app.$.statusAdvOptions.val());
        }

        // bind select2
        app.$input.select2({
            placeholder        : l10n.placeholder_text,
            minimumInputLength : 1,
            allowClear         : true,
            width              : '100%',
            initSelection      : app.initial_selection,
            ajax               : {
                cache    : false,
                url      : ajaxurl,
                dataType : 'json',
                results  : app.handle_results,
                data     : function( term, page ) {
                    return app.select2_ajax_data( term );
                }
            }
        });

    };

    // Function to controle toggle on Advanced Options fields
    app.toggle_advanced_options = function(value){
         app.$.advOptions.toggle();
         app.$.showHideAdvanced.toggle();
         $(this).hide();
         app.$.statusAdvOptions.val(value);
    };

    // Display the inital selection
    app.initial_selection = function( element, callback ) {
        var site_id_value = app.$input.val();

        if ( '0' === site_id_value ) {
            callback( {
                id   : 0,
                text : l10n.placeholder_text
            } );
            return;
        }

        var done_cb = function( data ) {
            callback( {
                id   : data.success ? data.data[0].id : 0,
                text : data.success ? data.data[0].text : l10n.placeholder_text
            } );
        };

        return $.ajax( {
            url      : ajaxurl,
            dataType : 'json',
            data     : app.select2_ajax_data( l10n.placeholder_value_text, site_id_value ),
        } ).done( done_cb );

    };

    // Handle setting up our ajax data
    app.select2_ajax_data = function( term, id ) {
        var data = {
            q      : term,
            action : 'mucd_fetch_sites',
            nonce  : l10n.nonce,
        };

        if ( id ) {
            data.id = id;
        }
        return data;
    };

    // Handle our ajax results
    app.handle_results = function( ajax_data, page, query ) {
        var items=[];

        $.each( ajax_data.data, function( i, item ) {
            var new_item = {
                'id'   : item.id,
                'text' : item.text
            };

            items.push( new_item );
        });

        return { results: items };
    };

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery, MUCD_Admin );
