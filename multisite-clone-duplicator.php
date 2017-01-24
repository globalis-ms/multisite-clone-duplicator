<?php
/**
 * Plugin Name:         MultiSite Clone Duplicator
 * Plugin URI:          http://wordpress.org/plugins/multisite-clone-duplicator/
 * Description:         Clones an existing site into a new one in a multisite installation : copies all the posts, settings and files
 * Author:              Julien OGER, Pierre DARGHAM, David DAUGREILH, GLOBALIS media systems
 * Author URI:          https://github.com/pierre-dargham/multisite-clone-duplicator
 *
 * Version:             1.4.1
 * Requires at least:   4.0.0
 * Tested up to:        4.7.1
 *
 * Network:             true
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

    if ( defined('WP_CLI') && WP_CLI ) {
        require_once MUCD_COMPLETE_PATH . '/lib/duplicate.php';
        MUCD_Functions::set_locale_to_en_US();
        require_once MUCD_COMPLETE_PATH . '/wp-cli/wp-cli-site-duplicate-subcommand.php';
    }    

    /**
     * Main class of the plugin
     */
    class MUCD {

        /**
        * Plugin's version number
        */
        const VERSION = '1.4.1';
        
        /**
         * Register hooks used by the plugin
         */
        public static function hooks() {
            register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
            add_action( 'admin_init', array( 'MUCD_Functions', 'check_if_multisite' ) );
        }

        /**
         * What to do on plugin activation
         */
        public static function activate() {
            MUCD_Option::init_options();
        }

    }

    MUCD::hooks();
}
