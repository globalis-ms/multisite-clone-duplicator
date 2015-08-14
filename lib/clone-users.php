<?php

if ( ! class_exists( 'MUCD_Clone_Users' ) ) {

	class MUCD_Clone_Users {

		/**
		 * Creates an admin user if no user exists with this email
		 * @since 0.2.0
		 * @param  string $email the email
		 * @param  string $domain the domain
		 * @return int id of the user
		 */
		public static function create_admin( $email, $username = '' ) {
			// Create New site Admin if not exists
			$password = 'N/A';
			$user_id = email_exists( $email );
			if ( ! $user_id ) { // Create a new user with a random password
				$password = wp_generate_password( 12, false );

				if ( empty( $username ) ) {
					global $current_site;
					$i = 001;
					$username = $current_site->domain . $i;
					while ( null != username_exists( $username ) ) {
						++$i;
						$username = $current_site->domain . $i;
						if ( $i > 999 ) {
							return new WP_Error( 'create_user', __( 'There was an error creating the user.', MUCD_DOMAIN ) );
						}
					}
				}

				$user_id = wpmu_create_user( $username, $password, $email );
				if ( false == $user_id ) {
					return new WP_Error( 'file_copy', __( 'There was an error creating the user.', MUCD_DOMAIN ) );
				}
				else {
					wp_new_user_notification( $user_id, $password );
				}
			}

			return $user_id;
		}

		/**
		 * Copy users and roles from one site to another
		 * @since 0.2.0
		 * @param  int $from_site_id duplicated site id
		 * @param  int $to_site_id   new site id
		 */
		public static function copy_users( $from_site_id, $to_site_id ) {

			global $wpdb;

			// Source Site information
			$from_site_prefix = $wpdb->get_blog_prefix( $from_site_id );		// prefix
			$from_site_prefix_length = strlen( $from_site_prefix );				// prefix length

			// Destination Site information
			$to_site_prefix = $wpdb->get_blog_prefix( $to_site_id );			// prefix
			$to_site_prefix_length = strlen( $to_site_prefix );

			$users = get_users( 'blog_id='.$from_site_id );

			$admin_email = get_blog_option( $to_site_id, 'admin_email' , 'false' );

			switch_to_blog( $to_site_id );

			foreach ( $users as $user ) {
				if ( $user->user_email != $admin_email ) {

					add_user_to_blog( $to_site_id, $user->ID, 'subscriber' );

					$all_meta = array_map( array( 'MUCD_Functions', 'user_array_map' ), get_user_meta( $user->ID ) );

					foreach ( $all_meta as $metakey => $metavalue ) {
						$prefix = substr( $metakey, 0, $from_site_prefix_length );
						if ( $prefix == $from_site_prefix ) {
							$raw_meta_name = substr( $metakey,$from_site_prefix_length );
							update_user_meta( $user->ID, $to_site_prefix . $raw_meta_name, maybe_unserialize( $metavalue ) );
						}
					}
				}
			}

			restore_current_blog();
		}

		public static function remove_users( $site_id ) {

			global $wpdb;

			$users = get_users( 'blog_id='.$site_id );

			$admin_email = get_blog_option( $site_id, 'admin_email' , 'false' );

			switch_to_blog( $site_id );

			foreach ( $users as $user ) {
				if ( $user->user_email != $admin_email ) {
					remove_user_from_blog( $user->ID, $site_id );
				}
			}

			restore_current_blog();
		}

		public static function remove_users_from_primary_site() {
			self::remove_users( MUCD_PRIMARY_SITE_ID );
		}

	}
}
