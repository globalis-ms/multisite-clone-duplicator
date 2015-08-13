<?php

if ( ! class_exists( 'MUCD_Data_Validation' ) ) {

	class MUCD_Data_Validation {

		/**
		 * Duplication form validation
		 * @since 0.2.0
		 * @param  array $init_data default data
		 * @return array $data validated data, or errors
		 */
		public static function check_form_clone_site() {

			$validated_data = array();

			// Check referer and nonce
			if ( ! check_admin_referer( MUCD_DOMAIN ) || ! isset( $_POST['site'] ) ) {
				$validated_data['error'] = self::error( __( 'Sorry, you don\'t have permissions to use this page.' ) );
			}
			else {
				$validated_data = self::check_from_site_id( $_POST['site'], $validated_data );
				$validated_data = self::check_domains_and_paths( $_POST['site'], $validated_data );
				$validated_data = self::check_title( $_POST['site'], $validated_data );
				$validated_data = self::check_email( $_POST['site'], $validated_data );
				$validated_data = self::check_copy_files( $_POST['site'], $validated_data );
				$validated_data = self::check_keep_users( $_POST['site'], $validated_data );
				$validated_data = self::check_log( $_POST['site'], $validated_data );
				$validated_data = self::check_public( $_POST['site'], $validated_data );
				$validated_data = self::check_network_id( $_POST['site'], $validated_data );
			}

			return $validated_data;
		}

		public static function check_form_clone_site_over_primary() {

			$validated_data = array();

			// Check referer and nonce
			if ( ! check_admin_referer( MUCD_DOMAIN ) || ! isset( $_POST['site'] ) ) {
				$validated_data['error'] = self::error( __( 'Sorry, you don\'t have permissions to use this page.' ) );
			}
			else {
				$validated_data = self::check_from_site_id( $_POST['site'], $validated_data );
				$validated_data = self::check_copy_files( $_POST['site'], $validated_data );
				$validated_data = self::check_keep_users( $_POST['site'], $validated_data );
				$validated_data = self::check_log( $_POST['site'], $validated_data );
				$validated_data = self::check_network_id( $_POST['site'], $validated_data );
			}

			return $validated_data;
		}

		public static function error( $message ) {
			return new WP_Error( 'mucd_error', $message );
		}

		public static function missing_field( $message ) {
			return self::error( __( 'Missing or invalid field', MUCD_DOMAIN ) . ' : ' . $message );
		}

		public static function check_from_site_id( $input_data, $validated_data ) {

			$return = $validated_data;
			$valid  = false;

			if ( isset( $input_data['source']  ) ) {
				$from_site_id = intval( $input_data['source'] );
				if ( $from_site_id >= 1 || site_exists( $from_site_id ) ) {
					$return['from_site_id'] = $from_site_id;
					$valid = true;
				}
			}

			if( ! $valid && ! isset( $return['error'] ) ) {
				$return['error'] = self::missing_field( __( 'Original site to copy', MUCD_DOMAIN ) );
			}

			return $return;
		}

		public static function check_domains_and_paths( $input_data, $validated_data ) {

			$return = $validated_data;
			$valid  = false;

			if ( isset( $input_data['domain']  ) ) {

				global $current_site;

				$domain = '';
				if ( preg_match( '|^([a-zA-Z0-9-])+$|', $input_data['domain'] ) ) {
					$domain = strtolower( $input_data['domain'] );
				}

				// If not a subdomain install, make sure the domain isn't a reserved word
				if ( ! is_subdomain_install() ) {
					/** This filter is documented in wp-includes/ms-functions.php */
					$subdirectory_reserved_names = apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) );
					if ( in_array( $domain, $subdirectory_reserved_names ) ) {
						$return['error'] = self::error( sprintf( __('The following words are reserved for use by WordPress functions and cannot be used as blog names: <code>%s</code>' ) , implode( '</code>, <code>', $subdirectory_reserved_names ) ) );
						return $return;
					}
				}

				if ( ! empty( $domain ) ) {
					if ( is_subdomain_install() ) {
						$return['newdomain'] = $domain . '.' . preg_replace( '|^www\.|', '', $current_site->domain );
						$return['path']      = $current_site->path;
					} else {
						$return['newdomain'] = $current_site->domain;
						$return['path']      = $current_site->path . $domain . '/';
					}
					$return['domain'] = $domain;
					$valid = true;
				}


			}

			if( ! $valid && ! isset( $return['error'] ) ) {
				$return['error'] = self::missing_field( __( 'New Site - Address', MUCD_DOMAIN ) );
			}

			return $return;
		}

		public static function check_title( $input_data, $validated_data ) {

			$return = $validated_data;
			$valid  = false;

			if ( isset( $input_data['title'] ) && ! empty( $input_data['title'] ) ) {
				$return['title'] = $input_data['title'];
				$valid = true;
			}

			if( ! $valid && ! isset( $return['error'] ) ) {
				$return['error'] = self::missing_field( __( 'New Site - Title', MUCD_DOMAIN ) );
			}

			return $return;
		}

		public static function check_email( $input_data, $validated_data ) {

			$return = $validated_data;
			$valid  = false;

			if ( isset( $input_data['email'] ) && ! empty( $input_data['email'] ) ) {
				$valid_mail = sanitize_email( $input_data['email'] );
				if ( is_email( $valid_mail ) ) {
					$return['email'] = $valid_mail;
					$valid = true;
				}
			}

			if( ! $valid && ! isset( $return['error'] ) ) {
				$return['error'] = self::missing_field( __( 'New Site - Admin Email', MUCD_DOMAIN ) );
			}

			return $return;
		}

		public static function check_copy_files( $input_data, $validated_data ) {

			$return = $validated_data;

			if ( isset( $input_data['copy_files'] ) && 'yes' == $input_data['copy_files'] ) {
				$return['copy_files'] = true;
			}
			else {
				$return['copy_files'] = false;
			}

			return $return;
		}

		public static function check_keep_users( $input_data, $validated_data ) {

			$return = $validated_data;

			if ( isset( $input_data['keep_users'] ) && 'yes' == $input_data['keep_users'] ) {
				$return['keep_users'] = true;
			}
			else {
				$return['keep_users'] = false;
			}

			return $return;
		}

		public static function check_log( $input_data, $validated_data ) {

			$return = $validated_data;
			$valid  = true;

			if ( isset( $input_data['log'] ) && 'yes' == $input_data['log'] ) {

				if( ! isset( $input_data['log-path'] ) || empty( $input_data['log-path'] ) || ! MUCD_Functions::valid_path( $input_data['log-path'] ) ) {
					$valid  = false;
				}
				else {
					$return['log'] = true;
					$return['log-path'] = $input_data['log-path'];
				}

			}
			else {
					$return['log'] = false;
			}

			if( ! $valid && ! isset( $return['error'] ) ) {
				$return['error'] = self::missing_field( __( 'Log directory', MUCD_DOMAIN ) );
			}

			return $return;
		}

		public static function check_public( $input_data, $validated_data ) {

			$return           = $validated_data;
			$return['public'] = true;

			if ( isset( $input_data['private'] ) && true === $input_data['private'] ) {
				$return['public'] = false;
			}

			return $return;
		}

		public static function check_network_id( $input_data, $validated_data ) {

			global $current_site;

			$return               = $validated_data;
			$return['network_id'] = $current_site->id;

			return $return;
		}

	}
}
