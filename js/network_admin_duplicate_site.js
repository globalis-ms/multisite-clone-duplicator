jQuery(document).ready(function() {

    // Logbox displaying
    jQuery('#log-box').change(function() {
        if(jQuery(this).is(":checked")) {
            jQuery('#log-path').prop('disabled', false);
        }   
        else {
            jQuery('#log-path').prop('disabled', true);
        }    
    }).change();

    // Advanced Options fields displaying
    jQuery('#show-advanced-options, #hide-advanced-options').click(function() {
		 mucd_toggle_advanced_options (jQuery(this).attr('id'));
    });
    // If display when user Post, Form reload with Advanced Options field displayed
    if(jQuery('#status-advanced-options').val() == 'show-advanced-options'){
        mucd_toggle_advanced_options ( jQuery('#status-advanced-options').val());
    }

    // Function to controle toggle on Advanced Options fields
    function mucd_toggle_advanced_options (value){
         jQuery('#advanced-options').toggle();
         jQuery('#hide-advanced-options, #show-advanced-options').toggle();
         jQuery(this).hide();
         jQuery('#status-advanced-options').val(value);
    }

});