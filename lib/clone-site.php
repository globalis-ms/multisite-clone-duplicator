<?php

if ( ! class_exists( 'MUCD_Duplicate' ) ) {

	class MUCD_Duplicate {

		public static $log;

		/**
		 * Init static variables
		 * @since 0.2.0
		 */
		public static function init() {
			self::$log = false;
		}

		/**
		 * Main function of the plugin : duplicates a site
		 * @since 0.2.0
		 * @param  array $data parameters from form
		 * @return $form_message result messages of the process
		 */
		public static function duplicate_site( $data ) {

			global $wpdb;
			$form_message = array();
			//$wpdb->hide_errors();

			self::init_log( $data );

			$email = $data['email'];
			$domain = $data['domain'];
			$newdomain = $data['newdomain'];
			$path = $data['path'];
			$title = $data['title'];
			$from_site_id = $data['from_site_id'];
			$keep_users = $data['keep_users'];
			$copy_file = $data['copy_files'];
			$public = $data['public'];
			$network_id = $data['network_id'];

			MUCD_Duplicate::write_log( 'Start site duplication : from site ' . $from_site_id );
			MUCD_Duplicate::write_log( 'Admin email : ' . $email );
			MUCD_Duplicate::write_log( 'Domain : ' . $newdomain );
			MUCD_Duplicate::write_log( 'Path : ' . $path );
			MUCD_Duplicate::write_log( 'Site title : ' . $title );

			$user_id = MUCD_Duplicate::create_admin( $email, $domain );

			if ( is_wp_error( $user_id ) ) {
				wp_cache_flush();
				$form_message['error'] = $user_id->get_error_message();
				return $form_message;
			}

			// Create new site
			$to_site_id = wpmu_create_blog( $newdomain, $path, $title, $user_id , array( 'public' => $public ), $network_id );
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

			MUCD_Duplicate::bypass_server_limit();

			// Copy Site - File
			if ( 'yes' == $copy_file ) {
				do_action( 'mucd_before_copy_files', $from_site_id, $to_site_id );
				$result = MUCD_Files::copy_files( $from_site_id, $to_site_id );
				do_action( 'mucd_after_copy_files', $from_site_id, $to_site_id );
			}

			// Copy Site - Data
			do_action( 'mucd_before_copy_data', $from_site_id, $to_site_id );
			$result = MUCD_Data::copy_data( $from_site_id, $to_site_id );
			do_action( 'mucd_after_copy_data', $from_site_id, $to_site_id );

			// Copy Site - Users
			if ( 'yes' == $keep_users ) {
				do_action( 'mucd_before_copy_users', $from_site_id, $to_site_id );
				$result = MUCD_Duplicate::copy_users( $from_site_id, $to_site_id );
				do_action( 'mucd_after_copy_users', $from_site_id, $to_site_id );
			}

			$form_message['msg'] = __( 'New site was created', MUCD_DOMAIN );
			$form_message['site_id'] = $to_site_id;

			MUCD_Duplicate::write_log( 'End site duplication : new site ID = ' . $to_site_id );

			update_blog_option( $to_site_id, 'mucd_duplicated_site_id', $from_site_id );

			wp_cache_flush();

			self::close_log();

			return $form_message;
		}

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

				if( empty( $username ) ) {
					global $current_site;
					$i = 001;
					$username = $current_site->domain . $i;
					while ( null != username_exists( $username ) ) {
						++$i;
						$username = $current_site->domain . $i;
						if( $i > 999 ) {
							return new WP_Error( 'file_copy', __( 'There was an error creating the user.', MUCD_DOMAIN ) );
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

					// PHP >= 5.3
					//$all_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user->ID ) );
					// PHP < 5.3
					$all_meta = array_map( array( 'MUCD_Functions', 'user_array_map'), get_user_meta( $user->ID ) );

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

		/**
		 * Init log object
		 * @since 0.2.0
		 * @param  array $data data from FORM
		 */
		public static function init_log( $data ) {
			// INIT LOG AND SAVE OPTION
			if ( isset( $data['log'] ) && 'yes' == $data['log'] ) {
				if ( isset( $data['log-path'] ) && ! empty( $data['log-path'] ) ) {
					$log_name = @date( 'Y_m_d_His' ) . '-' . $data['domain'] . '.log';
					if ( substr( $data['log-path'], -1 ) != '/' ) {
						$data['log-path'] = $data['log-path'] . '/';
					}
					MUCD_Duplicate::$log = new MUCD_Log( true, $data['log-path'], $log_name );
				}
			}
			else {
				 MUCD_Duplicate::$log = new MUCD_Log( false );
			}
		}

		/**
		 * Check if log is active
		 * @since 0.2.0
		 * @return boolean
		 */
		public static function log() {
			return ( false !== self::$log && self::$log->can_write() && self::$log->mod() !== false );
		}

		/**
		 * Check if log has error
		 * @since 0.2.0
		 * @return boolean
		 */
		public static function log_error() {
			return ( false !== self::$log && ! (self::$log->can_write()) && self::$log->mod() !== false );
		}

		/**
		 * Writes a message in log file
		 * @since 0.2.0
		 * @param  string $msg the message
		 */
		public static function write_log( $msg ) {
			if ( false !== self::log() ) {
				self::$log->write_log( $msg );
			}
		}

		/**
		 * Close the log file
		 * @since 0.2.0
		 */
		public static function close_log() {
			if ( false !== self::log() ) {
				self::$log->close_log();
			}
		}

		/**
		 * Get the url of the created log file
		 * @since 0.2.0
		 * @return  string the url of false if no log file was created
		 */
		public static function log_url() {
			if ( false !== self::log() ) {
				return self::$log->file_url();
			}
			return false;
		}

		/**
		 * Get log directory
		 * @since 0.2.0
		 * @return string the path
		 */
		public static function log_dir() {
			return self::$log->dir_path();
		}

		/**
		 * Bypass limit server if possible
		 * @since 0.2.0
		 */
		public static function bypass_server_limit() {
			@ini_set( 'memory_limit','1024M' );
			@ini_set( 'max_execution_time','0' );
		}
	}

	MUCD_Duplicate::init();
}
