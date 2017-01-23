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

        if ( l10n.use_select2 ) {
            app.select2_init();
        }
    };

    app.select2_init = function() {
        app.$input.select2({
            width : '75%',
            language : l10n.locale,
            placeholder : l10n.placeholder_text,
            minimumInputLength: 1,
            templateResult: app.format_sites,
            templateSelection: function( result ) { return result.text; },
            escapeMarkup: function( markup ) { return markup; },
            ajax: {
                url : ajaxurl,
                cache : false,
                dataType : 'json',
                delay : 250,
                data : app.select2_ajax_data,
                processResults: app.handle_results,
            }
        });
    };

    app.format_sites = function( site ) {
        // return early if we're still loading
        if ( site.loading ) {
            return site.text;
        }

        var markup = '<div class="site-wrapper clearfix">'
            + '<span>' + site.text + '</span>'
            + '<br><strong>'+ l10n.blogname +'</strong>: ' + site.details.blogname
            + ', <strong>'+ l10n.the_id +'</strong>: ' + site.id
            + ', <strong>'+ l10n.post_count +'</strong>: ' + site.details.post_count
            + ', <strong>'+ l10n.is_public +'</strong>: ' + ( 1 == site.details['public'] ? l10n.yes : l10n.no )
            + ', <strong>'+ l10n.is_archived +'</strong>: ' + ( 1 == site.details.archived  ? l10n.yes : l10n.no )
            + '</div>';

        return markup;
    };

    // Handle setting up our ajax data
    app.select2_ajax_data = function( params ) {
        return {
            q      : params.term,
            action : 'mucd_fetch_sites',
            nonce  : l10n.nonce,
        };
    };

    // Handle our ajax results
    app.handle_results = function( ajax_data, page, query ) {
        // return early on ajax failure, undefined data, or empty data
        if ( ! ajax_data.success || ! ajax_data.data || ! ajax_data.data.length ) {
            if ( l10n.debug ) {
                console.warn( 'app.handle_results ajax_data.data', ajax_data.data );
            }
            return { results: [] };
        }

        var items = [];

        $.each( ajax_data.data, function( i, item ) {
            var new_item = {
                'id'      : item.id,
                'text'    : item.text,
                'details' : item.details,
            };

            items.push( new_item );
        });

        return { results: items };
    };

    app.toggle_advanced_options = function( value ){
         app.$.advOptions.toggle();
         app.$.showHideAdvanced.toggle();
         $(this).hide();
         app.$.statusAdvOptions.val(value);
    };

    $( document ).ready( app.init );

    return app;

})( window, document, jQuery, MUCD_Admin );