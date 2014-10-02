jQuery(document).ready(function() {

    jQuery('#log-box').change(function() {
        if(jQuery(this).is(":checked")) {
            jQuery('#log-path').prop('disabled', false);
        }   
        else {
            jQuery('#log-path').prop('disabled', true);
        }    
    });

    jQuery('#show-advanced-options').click(function() {
		 jQuery('#advanced-options').show();
		 jQuery('#hide-advanced-options').show();
		 jQuery(this).hide();
    });

    jQuery('#hide-advanced-options').click(function() {
		 jQuery('#advanced-options').hide();
		 jQuery('#show-advanced-options').show();
		 jQuery(this).hide();
    });
      
});