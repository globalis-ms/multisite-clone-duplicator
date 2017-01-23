<?php

/**
 * ERRORS
 */
define( 'MUCD_GAL_ERROR_CAPABILITIES', __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN ) );
define( 'MUCD_GAL_ERROR_NO_SITE', __( 'Sorry, there is no site available for duplication.', MUCD_DOMAIN ) );
define( 'MUCD_LOG_ERROR', __( 'The log file cannot be written', MUCD_DOMAIN ) );
define( 'MUCD_CANT_WRITE_LOG', __( 'The log file cannot be written to location', MUCD_DOMAIN ) );
define( 'MUCD_CHANGE_RIGHTS_LOG', __( 'To enable logging, change permissions on log directory', MUCD_DOMAIN ) );
define( 'MUCD_JAVASCRIPT_REQUIRED', __( 'This feature will not work without javascript', MUCD_DOMAIN ) );
define( 'MUCD_NO_RESULTS', __( 'No results found', MUCD_DOMAIN ) );
define( 'MUCD_GENERAL_ERROR', __( 'ERROR', MUCD_DOMAIN ) );

/**
 * LABELS
 */
define( 'MUCD_NETWORK_MENU_DUPLICATE', __( 'Duplicate', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_MENU_DUPLICATION', __( 'Duplication', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_DUPLICABLE', __( 'Duplicable', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_SELECT_SITE', __( 'Start typing to search for a site', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_CUSTOMIZE', __( 'Customize', MUCD_DOMAIN ) );
define( 'MUCD_YES', __( 'Yes', MUCD_DOMAIN ) );
define( 'MUCD_NO', __( 'No', MUCD_DOMAIN ) );
define( 'MUCD_BLOGNAME', __( 'Blog Name', MUCD_DOMAIN ) );
define( 'MUCD_THE_ID', __( 'ID', MUCD_DOMAIN ) );
define( 'MUCD_POST_COUNT', __( 'Post Count', MUCD_DOMAIN ) );
define( 'MUCD_IS_PUBLIC', __( 'Public', MUCD_DOMAIN ) );
define( 'MUCD_IS_ARCHIVED', __( 'Archived', MUCD_DOMAIN ) );

/**
 * Admin Page Duplicate MESSAGES
 */
define( 'MUCD_NETWORK_PAGE_DUPLICATE_DASHBOARD', __( 'Dashboard', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_VISIT', __( 'Visit', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_VIEW_LOG', __( 'View log', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_MISSING_FIELDS', __( 'Missing fields', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_TITLE_ERROR_REQUIRE', __( 'Missing or invalid title', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_DOMAIN_ERROR_RESERVED_WORDS', __( 'The following words are reserved for use by WordPress functions and cannot be used as blog names : <code>%s</code>', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_DOMAIN_ERROR_REQUIRE', __( 'Missing or invalid site address', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_EMAIL_MISSING', __( 'Missing admin email address', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_EMAIL_ERROR_FORMAT', __( 'Invalid admin email address', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_VIEW_LOG_PATH_EMPTY', __( 'Missing or invalid log directory path', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_NOTICE_CREATED', __( 'New site was created', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_ADMIN_ERROR_CREATE_USER', __( 'There was an error creating the user.', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_COPY_FILE_ERROR', __( 'Failed to copy files : check permissions on <strong>%s</strong>', MUCD_DOMAIN ) );

/**
 * Admin Page Duplicate FORM
 */
define( 'MUCD_NETWORK_PAGE_DUPLICATE_TITLE', __( 'Duplicate Site', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_SOURCE', __( 'Original site to copy', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS', __( 'New Site - Address', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS_INFO', __( 'Only lowercase letters (a-z) and numbers are allowed.', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_TITLE', __( 'New Site - Title', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL', __( 'New Site - Admin Email', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL_INFO_1', __( 'A new user will be created if the above email address is not in the database.', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL_INFO_2', __( 'The username and password will be mailed to this email address.', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_ADVANCED_SHOW', __( 'Show advanced options', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_ADVANCED_HIDE', __( 'Hide advanced options', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FILES', __( 'Files', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_FILES_TEXT_1', __( 'Duplicate files from duplicated site upload directory', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_USERS', __( 'Users and roles', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_USERS_TEXT_1', __( 'Keep users and roles from duplicated site', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_LOG', __( 'Log', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_1', __( 'Generate log file', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_2', __( 'Log directory', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_BUTTON_COPY', __( 'Duplicate', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_DUPLICATE_TOOLTIP', __( 'Edit duplicable sites list', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_USE_ENHANCED_FOR_SITE_SELECT', __( 'Disable Enhanced Site Select', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_PAGE_USE_ENHANCED_FOR_SITE_SELECT_TEXT_1', __( 'Disable Select2 for Site Select ', MUCD_DOMAIN ) );

/**
 * Settings
 */
define( 'MUCD_NETWORK_SETTINGS_DUPLICABLE_WEBSITES', __( 'Dublicable websites', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_SETTINGS_DUPLICABLE_ALL', __( 'Allow duplication of all sites of the network', MUCD_DOMAIN ) );
define( 'MUCD_NETWORK_SETTINGS_DUPLICABLE_SELECTED', __( 'Allow duplication of following sites only :', MUCD_DOMAIN ) );
