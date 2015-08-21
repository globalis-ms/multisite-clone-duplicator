<?php

if ( ! class_exists( 'MUCD_Clone_Site' ) ) {

	class MUCD_Clone_Site {

		/**
		 * Main function of the plugin : duplicates a site
		 * @since 0.2.0
		 * @param  array $data parameters from form
		 * @return $form_message result messages of the process
		 */
		public static function clone_site( $data, $over_primary = false ) {

			// WPDB
			global $wpdb;
			$wpdb->hide_errors();

			// DATA
			extract( $data );

			// RESULTS
			$form_message = array();

			// SERVER
			MUCD_Functions::bypass_server_limit();

			// LOG
			self::start_duplication_log( $data, $over_primary );

			$mod_save_options = ! $over_primary;

			// CLONE OVER PRIMARY SITE SETTINGS
			if ( $over_primary ) {
				$email = get_blog_option( MUCD_PRIMARY_SITE_ID, 'admin_email' );
				$domain = '';
				add_filter( 'mucd_copy_dirs', array( 'MUCD_Clone_Files', 'copy_dirs_over_primary' ), 10, 1 );
				add_action( 'mucd_before_copy_files', array( 'MUCD_Clone_Files', 'empty_primary_dir' ), 10, 0 );
				add_action( 'mucd_before_copy_users', array( 'MUCD_Clone_Users', 'remove_users_from_primary_site' ), 10, 0 );
			}

			$user_id = MUCD_Clone_Users::create_admin( $email, $domain );

			if ( is_wp_error( $user_id ) ) {
				wp_cache_flush();
				$form_message['error'] = $user_id->get_error_message();
				return $form_message;
			}

			if ( $over_primary ) {
				$to_site_id = MUCD_PRIMARY_SITE_ID;
			}
			else {
				// Create new site
				$to_site_id = wpmu_create_blog( $newdomain, $path, $title, $user_id , array( 'public' => $public ), $network_id );

			}

			$wpdb->show_errors();

			if ( is_wp_error( $to_site_id ) ) {
				wp_cache_flush();
				$form_message['error'] = $to_site_id->get_error_message();
				return $form_message;
			}

			// User rights adjustments
			if ( ! is_super_admin( $user_id ) && ! get_user_option( 'primary_blog', $user_id ) ) {
				update_user_option( $user_id, 'primary_blog', $to_site_id, true );
			}

			// Copy Site - File
			if ( $copy_files ) {
				do_action( 'mucd_before_copy_files', $from_site_id, $to_site_id );
				$result = MUCD_Clone_Files::copy_files( $from_site_id, $to_site_id );
				do_action( 'mucd_after_copy_files', $from_site_id, $to_site_id );
			}

			// Copy Site - Data
			do_action( 'mucd_before_copy_data', $from_site_id, $to_site_id );
			$result = MUCD_Clone_DB::copy_data( $from_site_id, $to_site_id, $mod_save_options );
			do_action( 'mucd_after_copy_data', $from_site_id, $to_site_id );

			// Copy Site - Users
			if ( $keep_users ) {
				do_action( 'mucd_before_copy_users', $from_site_id, $to_site_id );
				$result = MUCD_Clone_Users::copy_users( $from_site_id, $to_site_id );
				do_action( 'mucd_after_copy_users', $from_site_id, $to_site_id );
			}

			$form_message['msg'] = __( 'New site was created', MUCD_DOMAIN );
			$form_message['site_id'] = $to_site_id;

			update_blog_option( $to_site_id, 'mucd_parent_site_id', $from_site_id );

			wp_cache_flush();

			self::end_duplication_log( $to_site_id );

			return $form_message;
		}

		public static function start_duplication_log( $data, $over_primary = false ) {
			if ( $over_primary ) {
				$data['domain'] = 'clone-over-primary';
				MUCD_Log::init( $data );
				MUCD_Log::write( 'Start cloning over the primary site : from site ' . $data['from_site_id'] );
			}
			else {
				MUCD_Log::init( $data );
				MUCD_Log::write( 'Start site duplication : from site ' . $data['from_site_id'] );
				MUCD_Log::write( 'Admin email : ' . $data['email'] );
				MUCD_Log::write( 'Domain : ' . $data['newdomain'] );
				MUCD_Log::write( 'Path : ' . $data['path'] );
				MUCD_Log::write( 'Site title : ' . $data['title'] );
			}

		}

		public static function end_duplication_log( $to_site_id ) {
			MUCD_Log::write( 'End site duplication : new site ID = ' . $to_site_id );
			MUCD_Log::close();
		}

	}

}
