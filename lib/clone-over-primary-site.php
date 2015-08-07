<?php

if ( ! class_exists( 'MUCD_Clone_Over_Primary' ) ) {

	class MUCD_Clone_Over_Primary {

		public static function hooks() {
			add_filter( 'mucd_copy_dirs', array( __CLASS__, 'copy_dirs' ), 10, 2 );
		}

		public static function duplicate_site_over_primary( $data ) {

			self::hooks();

			global $wpdb;
			$form_message = array();
			//$wpdb->hide_errors();


			$newdomain = $data['newdomain'];
			$path = $data['path'];
			$from_site_id = $data['from_site_id'];
			$keep_users = $data['keep_users'];
			$copy_file = $data['copy_files'];
			$network_id = $data['network_id'];

			$email = get_blog_option( MUCD_PRIMARY_SITE_ID, 'admin_email' );
			$user_id = MUCD_Duplicate::create_admin( $email, '' );

			if ( is_wp_error( $user_id ) ) {
				wp_cache_flush();
				$form_message['error'] = $user_id->get_error_message();
				return $form_message;
			}

			//$wpdb->show_errors();

			// User rights adjustments
			if ( ! is_super_admin( $user_id ) && ! get_user_option( 'primary_blog', $user_id ) ) {
				update_user_option( $user_id, 'primary_blog', MUCD_PRIMARY_SITE_ID, true );
			}

			MUCD_Duplicate::bypass_server_limit();

			// Copy Site - File
			if ( 'yes' == $copy_file ) {
				do_action( 'mucd_before_copy_files', $from_site_id, MUCD_PRIMARY_SITE_ID );
				self::empty_primary_dir();
				$result = MUCD_Files::copy_files( $from_site_id, MUCD_PRIMARY_SITE_ID );
				do_action( 'mucd_after_copy_files', $from_site_id, MUCD_PRIMARY_SITE_ID );
			}

			// Copy Site - Data
			do_action( 'mucd_before_copy_data', $from_site_id, MUCD_PRIMARY_SITE_ID );
			$result = MUCD_Data::clone_over_primary( $from_site_id );
			do_action( 'mucd_after_copy_data', $from_site_id, MUCD_PRIMARY_SITE_ID );

			// Copy Site - Users
			if ( 'yes' == $keep_users ) {
				do_action( 'mucd_before_copy_users', $from_site_id, MUCD_PRIMARY_SITE_ID );
				self::remove_users( MUCD_PRIMARY_SITE_ID );
				$result = MUCD_Duplicate::copy_users( $from_site_id, MUCD_PRIMARY_SITE_ID );
				do_action( 'mucd_after_copy_users', $from_site_id, MUCD_PRIMARY_SITE_ID );
			}

			$form_message['msg'] = MUCD_NETWORK_PAGE_DUPLICATE_NOTICE_CREATED;
			$form_message['site_id'] = MUCD_PRIMARY_SITE_ID;

			update_blog_option( MUCD_PRIMARY_SITE_ID, 'mucd_duplicated_site_id', $from_site_id );

			wp_cache_flush();
			return $form_message;
		}

		public static function empty_primary_dir() {

			switch_to_blog( MUCD_PRIMARY_SITE_ID );
			$wp_upload_info = wp_upload_dir();
			$dir = str_replace( ' ', '\\ ', trailingslashit( $wp_upload_info['basedir'] ) );
			restore_current_blog();

			self::rrmdir_inside_and_exclude( $dir, array( 'sites' ) );
		}

		public static function copy_dirs( $dirs, $from_site_id ) {

			switch_to_blog( MUCD_PRIMARY_SITE_ID );
			$wp_upload_info = wp_upload_dir();
			$dir = str_replace( ' ', '\\ ', trailingslashit( $wp_upload_info['basedir'] ) );
			restore_current_blog();

			$dirs[0]['to_dir_path'] = $dir;

			return $dirs;
		}

		public static function rrmdir_inside_and_exclude( $dir, $exclude ) {
			if ( is_dir( $dir ) ) {
				$objects = scandir( $dir );
				foreach ( $objects as $object ) {
					if ( $object != '.' && $object != '..' && ! in_array( $object, $exclude ) ) {
						if ( 'dir' == filetype( $dir . '/' . $object ) ) {
							MUCD_Files::rrmdir( $dir . '/' . $object );
						}
						else {
							unlink( $dir . '/' . $object );
						}
				   	}
				}
				reset( $objects );
		   	}
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

	}

}