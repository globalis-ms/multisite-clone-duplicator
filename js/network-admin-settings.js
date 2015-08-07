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

});