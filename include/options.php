<?php
/**
 * Option management for the plugin
 */
if ( ! class_exists( 'MUCD_Option' ) ) {

	class MUCD_Option {


		/**
		 * Do some actions at the beginning of an admin script
		 */
		public static function hooks() {
			// Network setting page
			add_action( 'wpmu_options', array( __CLASS__, 'admin_network_option_page' ) );
			// Save Network setting page
			add_action( 'wpmuadminedit', array( __CLASS__, 'save_admin_network_option_page' ) );
		}

		/**
		 * Add plugin default options
		 * @since 1.3.0
		 */
		public static function init_options() {
			add_site_option( 'mucd_copy_files', MUCD_DEFAULT_OPTION_COPY_FILES );
			add_site_option( 'mucd_keep_users', MUCD_DEFAULT_OPTION_KEEP_USERS );
			add_site_option( 'mucd_log', MUCD_DEFAULT_OPTION_LOG );
			add_site_option( 'mucd_log_dir', MUCD_Functions::get_primary_upload_dir() . MUCD_DEFAULT_OPTION_LOG_DIRNAME );
		}

		/**
		 * Removes plugin options
		 * @since 1.3.0
		 */
		public static function delete_options() {
			delete_site_option( 'mucd_copy_files' );
			delete_site_option( 'mucd_keep_users' );
			delete_site_option( 'mucd_log' );
			delete_site_option( 'mucd_log_dir' );
		}

		public static function mod_copy_files() {
			return get_site_option( 'mucd_copy_files', MUCD_DEFAULT_OPTION_COPY_FILES );
		}

		public static function set_mod_copy_files( $value ) {
			update_site_option( 'mucd_copy_files', $value );
		}

		public static function mod_keep_users() {
			return get_site_option( 'mucd_keep_users', MUCD_DEFAULT_OPTION_KEEP_USERS );
		}

		public static function set_mod_keep_users( $value ) {
			update_site_option( 'mucd_keep_users', $value );
		}

		public static function mod_log() {
			return get_site_option( 'mucd_log', MUCD_DEFAULT_OPTION_LOG );
		}

		public static function set_mod_log( $value ) {
			update_site_option( 'mucd_log', $value );
		}

		/**
		 * Get log directory option
		 * @since 0.2.0
		 * @return string the path
		 */
		public static function log_directory() {
			return get_site_option( 'mucd_log_dir', MUCD_Functions::get_primary_upload_dir() . MUCD_DEFAULT_OPTION_LOG_DIRNAME );
		}

		public static function set_log_directory( $value ) {
			update_site_option( 'mucd_log_dir', $value );
		}

		/**
		 * Get default options that should be preserved in the new blog.
		 * @since 0.2.0
		 * @return  array of string
		 */
		public static function get_default_saved_option() {
			return array(
				'siteurl' 			=> '',
				'home' 				=> '',
				'upload_path' 		=> '',
				'fileupload_url' 	=> '',
				'upload_url_path' 	=> '',
				'admin_email' 		=> '',
				'blogname' 			=> '',
			);
		}

		/**
		 * Get filtered options that should be preserved in the new blog.
		 * @since 0.2.0
		 * @return  array of string (filtered)
		 */
		public static function get_saved_option() {
			return apply_filters( 'mucd_copy_blog_data_saved_options', MUCD_Option::get_default_saved_option() );
		}

		/**
		 * Get default fields to scan for an update after data copy
		 * @since 0.2.0
		 * @return array '%table_name' => array('%field_name_1','%field_name_2','%field_name_3', ...)
		 */
		public static function get_default_fields_to_update() {
			return array(
				'commentmeta' 			=> array(),
				'comments' 				=> array(),
				'links' 				=> array( 'link_url', 'link_image' ),
				'options' 				=> array( 'option_name', 'option_value' ),
				'postmeta' 				=> array( 'meta_value' ),
				'posts' 				=> array( 'post_content', 'guid' ),
				'terms' 				=> array(),
				'term_relationships'	=> array(),
				'term_taxonomy' 		=> array(),
			);
		}

		/**
		 * Get filtered fields to scan for an update after data copy
		 * @since 0.2.0
		 * @return  array of string (filtered)
		 */
		public static function get_fields_to_update() {
			return apply_filters( 'mucd_default_fields_to_update', MUCD_Option::get_default_fields_to_update() );
		}

		/**
		 * Get default tables to duplicate when duplicated site is primary site
		 * @since 0.2.0
		 * @return  array of string
		 */
		public static function get_default_primary_tables_to_copy() {
			return array(
				'commentmeta',
				'comments',
				'links',
				'options',
				'postmeta',
				'posts',
				'terms',
				'term_relationships',
				'term_taxonomy',
			);
		}

		/**
		 * Get filtered tables to duplicate when duplicated site is primary site
		 * @since 0.2.0
		 * @return  array of string (filtered)
		 */
		public static function get_primary_tables_to_copy() {
			return apply_filters( 'mucd_default_primary_tables_to_copy', MUCD_Option::get_default_primary_tables_to_copy() );
		}

		/**
		 * Save duplication options on network settings page
		 * @since 0.2.0
		 */
		public static function save_admin_network_option_page() {

			if ( ! empty( $_POST ) && isset( $_POST[ MUCD_SLUG_ACTION_SETTINGS ] ) ) {

				if ( check_admin_referer( 'siteoptions' ) ) {

					if ( isset( $_POST['mucd_copy_files']) && 'yes' == $_POST['mucd_copy_files'] ) {
						self::set_mod_copy_files( true );
					}
					else {
						self::set_mod_copy_files( false );
					}

					if ( isset( $_POST['mucd_keep_users']) && 'yes' == $_POST['mucd_keep_users'] ) {
						self::set_mod_keep_users( true );
					}
					else {
						self::set_mod_keep_users( false );
					}

					if ( isset( $_POST['mucd_log']) && 'yes' == $_POST['mucd_log'] ) {

						self::set_mod_log( true );

						if ( isset( $_POST['mucd_log_dir'] ) ) {
							self::set_log_directory( $_POST['mucd_log_dir'] );
						}
					}
					else {
						self::set_mod_log( false );
					}

				}
			}

		}

		/**
		 * Print duplication options on network settings page
		 * @since 0.2.0
		 */
		public static function admin_network_option_page() {
			require_once MUCD_PATH_TEMPLATES . '/network-admin-settings.php';
		}

	}

	MUCD_Option::hooks();

}
