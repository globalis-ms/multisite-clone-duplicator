<?php

// Configuration
require_once realpath( dirname( __FILE__ ) ) . '/config.php';

// Load textdomain
load_plugin_textdomain( MUCD_DOMAIN, null, MUCD_PATH . '/language/' );

// Options
require_once MUCD_PATH_INCLUDE . '/options.php';

// Functions
require_once MUCD_PATH_LIB . '/mucd-functions.php';

// Log
require_once MUCD_PATH_LIB . '/mucd-log.php';

// Data validation
require_once MUCD_PATH_LIB . '/mucd-data-validation.php';

// Clone-db
require_once MUCD_PATH_LIB . '/clone-db.php';

// Clone-files
require_once MUCD_PATH_LIB . '/clone-files.php';

// Clone-users
require_once MUCD_PATH_LIB . '/clone-users.php';

// Clone-site
require_once MUCD_PATH_LIB . '/clone-site.php';

// Select2
require_once MUCD_PATH_LIB . '/mucd-select2.php';

// Enqueue scripts
require_once MUCD_PATH_INCLUDE . '/scripts.php';

// Admin
require_once MUCD_PATH_INCLUDE . '/admin.php';
