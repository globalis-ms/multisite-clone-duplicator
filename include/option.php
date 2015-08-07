<?php
/**
 * Option management for the plugin
 */
if ( ! class_exists( 'MUCD_Option' ) ) {

	class MUCD_Option {

		/**
		 * Add plugin default options
		 * @since 1.3.0
		 */
		public static function init_options() {
			add_site_option( 'mucd_copy_files', 'yes' );
			add_site_option( 'mucd_keep_users', 'yes' );
			add_site_option( 'mucd_log', 'no' );
			add_site_option( 'mucd_log_dir', MUCD_COMPLETE_PATH . '/logs/' );
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

		/**
		 * Get log directory option
		 * @since 0.2.0
		 * @return string the path
		 */
		public static function get_option_log_directory() {
			return get_site_option( 'mucd_log_dir', MUCD_COMPLETE_PATH . '/logs/' );
		}

		/**
		 * Get directories to exclude from file copy when duplicated site is primary site
		 * @since 0.2.0
		 * @return  array of string
		 */
		public static function get_primary_dir_exclude() {
			return array(
				'sites',
			);
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

	}
}
