<?php

$log_dir = MUCD_Duplicate::log_dir();
echo '<div id="message" class="error">';
echo '    <p>';
if ( $log_dir == '' ) {
	_e( 'The log file cannot be written', MUCD_DOMAIN );
}
else {
	_e( 'The log file cannot be written to location', MUCD_DOMAIN ) . ' <strong>'. $log_dir .'</strong><br />';
	_e( 'To enable logging, change permissions on log directory', MUCD_DOMAIN ) . '<br /><code>chmod 777 '. $log_dir .'</code>';
}
echo '    </p>';
echo '</div>';
