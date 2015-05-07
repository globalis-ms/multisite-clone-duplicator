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

        if ( $().select2 ) {
            app.$input.select2({
                width : '100%',
                minimumInputLength: 1,
                templateResult: app.formatSites,
                templateSelection: app.formatSiteSelection,
                escapeMarkup: function( markup ) { 
                    return markup; 
                },
                ajax: {
                    url : ajaxurl,
                    cache : false,
                    dataType : 'json',
                    delay : 250,
                    data : function( params ) {
                        return app.select2_ajax_data( params.term );
                    },
                    processResults: app.handle_results,
                }
            });
        }
    };

    app.formatSites = function( site ) {
        // return early if we're still loading
        if ( site.loading ) {
            return site.text;
        }

        console.log(site);
        var markup = '<div class="site-wrapper clearfix">'
            + '<span>' + site.text + '</span>'
            + '<br><strong>Blogname</strong>: ' + site.details.blogname
            + ', <strong>Post Count</strong>: ' + site.details.post_count
            + ', <strong>Public</strong>: ' + ( 1 == site.details['public'] ? 'Yes' : 'No' )
            + ', <strong>Archived</strong>: ' + ( 1 == site.details.archived  ? 'Yes' : 'No' )
            + '</div>';

        return markup;
    };

    app.formatSiteSelection = function( result ) {
        return result.text;
    };

    // Function to controle toggle on Advanced Options fields
    app.toggle_advanced_options = function( value ){
         app.$.advOptions.toggle();
         app.$.showHideAdvanced.toggle();
         $(this).hide();
         app.$.statusAdvOptions.val(value);
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

        // return early on ajax failure, undefined data, and empty data
        if ( 
            ( false == ajax_data.success )
            || ( 'undefined' === typeof ajax_data.data )
            || ( ajax_data.data.length < 1 )
        ) {
            return false;
        }

        $.each( ajax_data.data, function( i, item ) {
            var new_item = {
                'id'   : item.id,
                'text' : item.text,
                'details' : item.details,
            };

            items.push( new_item );
        });

        return { results: items };
    };

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery, MUCD_Admin );
