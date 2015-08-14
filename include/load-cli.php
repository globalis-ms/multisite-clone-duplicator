<?php

// Configuration
require_once realpath( dirname( __FILE__ ) ) . '/config.php';

// Options
require_once MUCD_PATH_INCLUDE . '/options.php';

// Functions
require_once MUCD_PATH_LIB . '/mucd-functions.php';

// Log
require_once MUCD_PATH_LIB . '/mucd-log.php';

// Clone-db
require_once MUCD_PATH_LIB . '/clone-db.php';

// Clone-files
require_once MUCD_PATH_LIB . '/clone-files.php';

// Clone-users
require_once MUCD_PATH_LIB . '/clone-users.php';

// Clone-site
require_once MUCD_PATH_LIB . '/clone-site.php';

// WP-CLI
require_once MUCD_PATH_CLI . '/wp-cli-site-duplicate-subcommand.php';
