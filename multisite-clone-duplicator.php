<?php
/**
 * Plugin Name:         MultiSite Clone Duplicator
 * Plugin URI:          http://wordpress.org/plugins/multisite-clone-duplicator/
 * Description:         Clones an existing site into a new one in a multisite installation : copies all the posts, settings and files
 * Author:              Julien OGER, Pierre DARGHAM, GLOBALIS media systems
 * Author URI:          http://www.globalis-ms.com
 *
 * Version:             1.2.0
 * Requires at least:   3.5.0
 * Tested up to:        4.1.0
 */

// Block direct requests
if ( !defined('ABSPATH') )
    die('-1');

if( !class_exists( 'MUCD' ) ) {
    // Load configuration
    require_once realpath( dirname( __FILE__ ) ) . '/include/config.php';

    // Plugin options
    require_once MUCD_COMPLETE_PATH . '/include/option.php';

    // Load textdomain
    load_plugin_textdomain( MUCD_DOMAIN, NULL, MUCD_PATH . '/language/' );

    // Load language
    require_once MUCD_COMPLETE_PATH . '/include/lang.php';

    // Load Functions
    require_once MUCD_COMPLETE_PATH . '/lib/functions.php';

    if( is_admin() ) {
        require_once MUCD_COMPLETE_PATH . '/include/admin.php';
        MUCD_Admin::hooks();
    }

    /**
     * Main class of the plugin
     */
    class MUCD {
        /**
         * Register hooks used by the plugin
         */
        public static function hooks() {
            // Register (de)activation hook
            register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
            register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

            add_action( 'init', array( __CLASS__, 'init' ) );
            add_action( 'admin_init', array( __CLASS__, 'check_if_multisite' ) );
        }


        /**
         * Deactivate the plugin if we are not on a multisite installation
         * @since 0.2.0
         */
        public static function check_if_multisite() {
            if (!function_exists('is_multisite') || !is_multisite()) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                wp_die('MultiSite Clone Duplicator works only for multisite installation');
            }
        }

        /**
         * What to do on plugin activation
         */
        public static function activate() {
            MUCD::check_if_multisite();
            MUCD_Option::init_duplicable_option();
            MUCD_Option::init_log_dir_option();               
        }

        /**
         * What to do on plugin deactivation
         */
        public static function deactivate() {
            MUCD_Option::delete_duplicable_option();
            MUCD_Option::delete_log_dir_option();
        }

        /**
         * What to do on plugin uninstallation
         */
        public static function uninstall() {
            // Nothing for now.
        }

        /**
         * Plugin init
         */
        public static function init() {
             // Nothing for now.
        }   
    }
    MUCD::hooks();
}