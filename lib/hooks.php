<?php

if ( ! function_exists( 'mucd_save_cloned_url' ) ) {

	/**
	 * Save $from_site_id as option of the new site
	 * @since 1.4.0
	 * @param  int $from_site_id duplicated site id
	 * @param  int $to_site_id   new site id
	 */
	function mucd_save_from_site_id( $from_site_id, $to_site_id ) {
		update_blog_option( $to_site_id, 'mucd_parent_site_id', $from_site_id );
	}

	add_action( 'mucd_after_copy_data', 'mucd_save_from_site_id', 10, 2 );
}