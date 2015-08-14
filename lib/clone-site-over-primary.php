<?php

if ( ! class_exists( 'MUCD_Clone_Site_Over_Primary' ) ) {

	class MUCD_Clone_Site_Over_Primary {

		public static function clone_site_over_primary( $data ) {

			// WPDB
			global $wpdb;

			// DATA
			extract($data);

			// RESULTS
			$form_message = array();	

			// LOG		
			self::start_duplication_log( $data );

			// SERVER
			MUCD_Functions::bypass_server_limit();

			// HOOKS
			add_filter( 'mucd_copy_dirs', array( 'MUCD_Clone_Files', 'copy_dirs_over_primary' ), 10, 1 );
			add_action( 'mucd_before_copy_files', array( 'MUCD_Clone_Files', 'empty_primary_dir' ), 10, 0 );
			add_action( 'mucd_before_copy_users', array( 'MUCD_Clone_Users', 'remove_users_from_primary_site' ), 10, 0 );

			$email = get_blog_option( MUCD_PRIMARY_SITE_ID, 'admin_email' );
			$user_id = MUCD_Clone_Users::create_admin( $email, '' );

			if ( is_wp_error( $user_id ) ) {
				wp_cache_flush();
				$form_message['error'] = $user_id->get_error_message();
				return $form_message;
			}

			// User rights adjustments
			if ( ! is_super_admin( $user_id ) && ! get_user_option( 'primary_blog', $user_id ) ) {
				update_user_option( $user_id, 'primary_blog', MUCD_PRIMARY_SITE_ID, true );
			}

			// Copy Site - File
			if ( $copy_files ) {
				do_action( 'mucd_before_copy_files', $from_site_id, MUCD_PRIMARY_SITE_ID );
				$result = MUCD_Clone_Files::copy_files( $from_site_id, MUCD_PRIMARY_SITE_ID );
				do_action( 'mucd_after_copy_files', $from_site_id, MUCD_PRIMARY_SITE_ID );
			}

			// Copy Site - Data
			do_action( 'mucd_before_copy_data', $from_site_id, MUCD_PRIMARY_SITE_ID );
			$result = MUCD_Clone_DB::clone_over_primary( $from_site_id );
			do_action( 'mucd_after_copy_data', $from_site_id, MUCD_PRIMARY_SITE_ID );

			// Copy Site - Users
			if ( $keep_users ) {
				do_action( 'mucd_before_copy_users', $from_site_id, MUCD_PRIMARY_SITE_ID );
				$result = MUCD_Clone_Users::copy_users( $from_site_id, MUCD_PRIMARY_SITE_ID );
				do_action( 'mucd_after_copy_users', $from_site_id, MUCD_PRIMARY_SITE_ID );
			}

			$form_message['msg'] = __( 'New site was created', MUCD_DOMAIN );
			$form_message['site_id'] = MUCD_PRIMARY_SITE_ID;

			update_blog_option( MUCD_PRIMARY_SITE_ID, 'mucd_parent_site_id', $from_site_id );

			wp_cache_flush();

			self::end_duplication_log( MUCD_PRIMARY_SITE_ID );

			return $form_message;
		}

		public static function start_duplication_log( $data ) {
			$data['domain'] = 'clone-over-primary';
			MUCD_Log::init( $data );
			MUCD_Log::write( 'Start cloning over the primary site : from site ' . $data['from_site_id'] );
		}

		public static function end_duplication_log( $to_site_id ) {
			MUCD_Log::write( 'End site duplication' );
			MUCD_Log::close();
		}

	}

}
