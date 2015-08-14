<?php

/* ------------------- ENVIRONMENT --------------------- */

define( 'MUCD_VERSION', '2.0.0.b.1' );

define( 'MUCD_DOMAIN', 'multisite-clone-duplicator' );

define( 'MUCD_GITHUB', 'https://github.com/pierre-dargham/multisite-clone-duplicator/tree/2.0.0.b.1' );



/* ------------------- PATHS AND URLS ------------------ */

define( 'MUCD_PATH', plugin_basename( realpath( dirname( __FILE__ ) . '/..' ) ) );

define( 'MUCD_PATH_PLUGIN', WP_PLUGIN_DIR . '/' . MUCD_PATH );
define( 'MUCD_URL_PLUGIN', WP_PLUGIN_URL . '/' . MUCD_PATH );

define( 'MUCD_PATH_INCLUDE', MUCD_PATH_PLUGIN . '/include' );
define( 'MUCD_URL_PLUGIN_INCLUDE', MUCD_URL_PLUGIN . '/include' );

define( 'MUCD_PATH_LIB', MUCD_PATH_PLUGIN . '/lib' );
define( 'MUCD_URL_PLUGIN_LIB', MUCD_URL_PLUGIN . '/lib' );

define( 'MUCD_PATH_SCRIPTS', MUCD_PATH_PLUGIN . '/js' );
define( 'MUCD_URL_PLUGIN_SCRIPTS', MUCD_URL_PLUGIN . '/js' );

define( 'MUCD_PATH_TEMPLATES', MUCD_PATH_PLUGIN . '/templates' );
define( 'MUCD_URL_PLUGIN_TEMPLATES', MUCD_URL_PLUGIN . '/templates' );

define( 'MUCD_PATH_CLI', MUCD_PATH_PLUGIN . '/wp-cli' );



/* ------------------- SLUGS --------------------------- */

define( 'MUCD_SLUG_NETWORK_ACTION', 'multisite-clone-duplicator-clone-site' );

define( 'MUCD_SLUG_NETWORK_ACTION_CLONE_OVER', 'multisite-clone-duplicator-clone-site-over-primary' );

define( 'MUCD_SLUG_ACTION_DUPLICATE', 'clone-site' );

define( 'MUCD_SLUG_ACTION_DUPLICATE_OVER_PRIMARY', 'clone-site-over-primary' );

define( 'MUCD_SLUG_ACTION_SETTINGS', '_mucdsettings' );



/* ------------------- DEFAULT OPTIONS --------------------------- */

define( 'MUCD_PRIMARY_SITE_ID', 1 );

define( 'MUCD_DEFAULT_OPTION_COPY_FILES', true );

define( 'MUCD_DEFAULT_OPTION_KEEP_USERS', true );

define( 'MUCD_DEFAULT_OPTION_LOG', false );

define( 'MUCD_DEFAULT_OPTION_LOG_DIRNAME', '/multisite-clone-duplicator-logs/' );
