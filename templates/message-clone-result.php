<?php

if ( isset ( $form_message ) ) {

	if ( isset ( $form_message['error'] ) ) {
		echo '<div id="message" class="error">';
		echo '    <p>' . $form_message['error'] . '</p>';
		echo '</div>';
	}
	else {
		echo '<div id="message" class="updated">';
		echo '  <p>';
		echo '      <strong>' . $form_message['msg'] . ' : ' . '</strong>';
		switch_to_blog( $form_message['site_id'] );
		$user = get_current_user_id();
		echo '      <a href="' . get_dashboard_url( $user ) . '">' . __( 'Dashboard', MUCD_DOMAIN ) . '</a> - ';
		echo '      <a href="' . get_site_url() . '">' .  __( 'Visit', MUCD_DOMAIN ) . '</a> - ';
		echo '      <a href="' . admin_url( 'customize.php' ) . '">' .__( 'Customize', MUCD_DOMAIN ) . '</a>';
		if ( $log_url = MUCD_Duplicate::log_url() ) {
			echo ' - <a href="' . $log_url . '">' . __( 'View log', MUCD_DOMAIN ) . '</a>';
		}
		restore_current_blog();
		echo '  </p>';
		echo '</div>';
	}
	
}
