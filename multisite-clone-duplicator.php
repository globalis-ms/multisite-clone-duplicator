<?php
/**
 * Plugin Name:         MultiSite Clone Duplicator
 * Plugin URI:          http://wordpress.org/plugins/multisite-clone-duplicator/
 * Description:         Clones an existing site into a new one in a multisite installation : copies all the posts, settings and files
 * Author:              Pierre DARGHAM, Julien OGER, GLOBALIS media systems
 * Author URI:          https://github.com/pierre-dargham/multisite-clone-duplicator
 *
 * Version:             2.0.0.a.1
 * Requires at least:   4.0.0
 * Tested up to:        4.2.4
 */

// Block direct requests
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'MUCD' ) ) {

	require_once realpath( dirname( __FILE__ ) ) . '/include/load.php';


	/**
	 * Main class of the plugin
	 */
	class MUCD {

		/**
		 * Plugin's version number
		 */
		const VERSION = MUCD_VERSION;

		/**
		 * Register hooks used by the plugin
		 */
		public static function hooks() {

			// Activation hook
			register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );

			// Deactivation hook
			register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );

			// Uninstall hook
			register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

			// Admin init hook (runs each time the dashboard is loaded)
			add_action( 'admin_init', array( 'MUCD_Functions', 'check_if_multisite' ) );
		}

		/**
		 * What to do on plugin activation
		 */
		public static function activate() {
			MUCD_Functions::check_if_multisite();
			MUCD_Option::init_options();
		}

		/**
		 * What to do on plugin deactivation
		 */
		public static function deactivate() {
			// Nothing for now.
		}

		/**
		 * What to do on plugin uninstallation
		 */
		public static function uninstall() {
			MUCD_Option::delete_options();
		}

	}

	MUCD::hooks();
}
