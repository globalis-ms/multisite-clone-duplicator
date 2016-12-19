jQuery(document).ready(function() {

    function enable_multiselect() {

        jQuery('.duplicables-list').prop('disabled', false);
        jQuery('#site-select-box').css('overflow', 'auto');

        var checkboxes = jQuery('#site-select-box').find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = jQuery(this);

            // Highlight pre-selected checkboxes
            if (checkbox.prop("checked")) {
                checkbox.parent().addClass("multiselect-on");
            }
 
            // Highlight checkboxes that the user selects
            checkbox.click(function() {
                if (checkbox.prop("checked")) {
                    checkbox.parent().addClass("multiselect-on");
                }
                else {
                    checkbox.parent().removeClass("multiselect-on");
                }
            });
        });        
    }

    function disable_multiselect() {
        jQuery('.duplicables-list').prop('disabled', true);
        jQuery('#site-select-box').css('overflow', 'hidden');
        var checkboxes = jQuery('#site-select-box').find("input:checkbox");
        checkboxes.each(function() {
            var checkbox = jQuery(this);
            checkbox.parent().removeClass("multiselect-on");
        });        
    }

    if(jQuery('#radio-duplicables-all').prop("checked")) {
            disable_multiselect();
    }
    else {
            enable_multiselect();
    }

    jQuery('#radio-duplicables-all').change(function() {
        if(jQuery(this).prop("checked", true)) {
            disable_multiselect();
        }
    });

    jQuery('#radio-duplicables-selected').change(function() {
        if(jQuery(this).prop("checked", true)) {
            enable_multiselect();
        }
    });

    // Logbox displaying
    jQuery('#log-box').change(function() {
        if(jQuery(this).is(":checked")) {
            jQuery('#log-path').prop('disabled', false);
        }   
        else {
            jQuery('#log-path').prop('disabled', true);
        }    
    }).change();

});