<?php
/**
 * Remember plugin path & URL
 */
define( 'MUCD_PATH', plugin_basename( realpath( dirname( __FILE__ ).'/..') ) );
define( 'MUCD_COMPLETE_PATH', untrailingslashit(plugin_dir_path(dirname( __FILE__ ))) );
define( 'MUCD_URL', plugins_url().'/'.MUCD_PATH );

/**
 * Domaine
 */
define( 'MUCD_DOMAIN', 'multisite-clone-duplicator' );

/**
 * Slugs
 */
define( 'MUCD_SLUG_NETWORK_ACTION', 'multisite-clone-duplicator' );
define( 'MUCD_SLUG_ACTION_DUPLICATE', 'duplicate-site' );
define( 'MUCD_SLUG_ACTION_SETTINGS', '_mucdsettings' );

/**
 * Site to excude
 */
define( 'MUCD_SITE_DUPLICATION_EXCLUDE', '' );

/**
 * Environment constants
 */
define( 'MUCD_PRIMARY_SITE_ID', 1 );
define( 'MUCD_MAX_NUMBER_OF_SITE', 5000 );