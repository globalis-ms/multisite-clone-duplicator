<?php

if ( ! class_exists( 'MUCD_Data_Validation' ) ) {

	class MUCD_Data_Validation {

		/**
		 * Duplication form validation
		 * @since 0.2.0
		 * @param  array $init_data default data
		 * @return array $data validated data, or errors
		 */
		public static function check_form( $init_data ) {

			$data = $init_data;
			$data['copy_files'] = 'no';
			$data['keep_users'] = 'no';
			$data['log'] = 'no';

			// Check referer and nonce
			if ( check_admin_referer( MUCD_DOMAIN ) ) {

				global $current_site;

				$error = array();

				// Merge $data / $_POST['site'] to get Posted data and fill form
				$data = array_merge( $data, $_POST['site'] );

				// format and check source
				$data['from_site_id'] = intval( $data['source'] );
				if ( $data['from_site_id'] < 1 || ! get_blog_details( $data['from_site_id'], false ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing fields', MUCD_DOMAIN ) );
				}

				$domain = '';
				if ( preg_match( '|^([a-zA-Z0-9-])+$|', $data['domain'] ) ) {
					$domain = strtolower( $data['domain'] );
				}

				// If not a subdomain install, make sure the domain isn't a reserved word
				if ( ! is_subdomain_install() ) {
					/** This filter is documented in wp-includes/ms-functions.php */
					$subdirectory_reserved_names = apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) );
					if ( in_array( $domain, $subdirectory_reserved_names ) ) {
						$error[] = new WP_Error( 'mucd_error', sprintf( __( 'The following words are reserved for use by WordPress functions and cannot be used as blog names : <code>%s</code>', MUCD_DOMAIN ) , implode( '</code>, <code>', $subdirectory_reserved_names ) ) );
					}
				}

				if ( empty( $domain ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid site address', MUCD_DOMAIN ) );
				}
				if ( is_subdomain_install() ) {
					$newdomain = $domain . '.' . preg_replace( '|^www\.|', '', $current_site->domain );
					$path      = $current_site->path;
				} else {
					$newdomain = $current_site->domain;
					$path      = $current_site->path . $domain . '/';
				}

				// format and check title
				if ( empty( $data['title'] ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid title', MUCD_DOMAIN ) );
				}

				// format and check email admin
				if ( empty( $data['email'] ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing admin email address', MUCD_DOMAIN ) );
				}
				$valid_mail = sanitize_email( $data['email'] );
				if ( is_email( $valid_mail ) ) {
					$data['email'] = $valid_mail;
				}
				else {
					$error[] = new WP_Error( 'mucd_error', __( 'Invalid admin email address', MUCD_DOMAIN ) );
				}

				$data['domain'] = $domain;
				$data['newdomain'] = $newdomain;
				$data['path'] = $path;

				$data['public'] = ! isset( $data['private'] );

				// Network
				$data['network_id'] = $current_site->id;

				if ( isset( $data['log'] ) && 'yes' == $data['log'] && ( ! isset( $data['log-path'] ) || $data['log-path'] == '' || ! MUCD_Functions::valid_path( $data['log-path'] ) ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid log directory path', MUCD_DOMAIN ) );
				}

				if ( isset( $error[0] ) ) {
					$data['error'] = $error[0];
				}
			}

			else {
				$data['error'] = __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN );
			}

			return $data;
		}

/**
		 * Duplication form validation
		 * @since 0.2.0
		 * @param  array $init_data default data
		 * @return array $data validated data, or errors
		 */
		public static function check_form_clone_over( $init_data ) {

			global $current_site;

			$data = $init_data;
			$data['copy_files'] = 'yes';
			$data['keep_users'] = 'yes';
			$data['log'] = 'no';
			$data['domain'] = 'primary';
			$data['newdomain'] = $current_site->domain;
			$data['path'] = $current_site->path;
			$data['network_id'] = $current_site->id;

			// Check referer and nonce
			if ( check_admin_referer( MUCD_DOMAIN ) ) {

				global $current_site;

				$error = array();

				// Merge $data / $_POST['site'] to get Posted data and fill form
				$data = array_merge( $data, $_POST['site'] );

				// format and check source
				$data['from_site_id'] = intval( $data['source'] );
				if ( $data['from_site_id'] < 2 || ! get_blog_details( $data['from_site_id'], false ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing fields', MUCD_DOMAIN ) );
				}			

				if ( isset( $data['log'] ) && 'yes' == $data['log'] && ( ! isset( $data['log-path'] ) || $data['log-path'] == '' || ! MUCD_Functions::valid_path( $data['log-path'] ) ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid log directory path', MUCD_DOMAIN ) );
				}

				if ( isset( $error[0] ) ) {
					$data['error'] = $error[0];
				}
			}

			else {
				$data['error'] = __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN );
			}

			return $data;
		}
	}
}
