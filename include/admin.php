<?php

if ( ! class_exists( 'MUCD_Admin' ) ) {

	require_once MUCD_COMPLETE_PATH . '/lib/duplicate.php';

	class MUCD_Admin {

		/**
		 * Register hooks used on admin side by the plugin
		 */
		public static function hooks() {
			// Network admin case
			if ( is_network_admin() ) {
				add_action( 'network_admin_menu', array( __CLASS__, 'network_menu_add_duplicate' ) );
				add_action( 'network_admin_menu', array( __CLASS__, 'network_menu_add_clone_over_primary_site' ) );
			}
			add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

			// ajax
			add_action( 'wp_ajax_mucd_fetch_sites', array( __CLASS__, 'mucd_fetch_sites' ) );
		}

		/**
		 * Do some actions at the beginning of an admin script
		 */
		public static function admin_init() {
			// Hook to rows on network sites listing
			add_filter( 'manage_sites_action_links', array( __CLASS__, 'add_site_row_action' ), 10, 2 );
			// Network admin bar
			add_action( 'admin_bar_menu', array( __CLASS__, 'admin_network_menu_bar' ) , 300 );
			// Network setting page
			add_action( 'wpmu_options', array( __CLASS__, 'admin_network_option_page' ) );
			// Save Network setting page
			add_action( 'wpmuadminedit', array( __CLASS__, 'save_admin_network_option_page' ) );
		}

		/**
		 * Adds 'Duplicate' entry to network admin-bar
		 * @since 0.2.0
		 * @param  WP_Admin_Bar $wp_admin_bar
		 */
		public static function admin_network_menu_bar($wp_admin_bar ) {

			if ( current_user_can( 'manage_sites' ) ) {

				$wp_admin_bar->add_menu( array(
					'parent' => 'network-admin',
					'id'     => 'network-admin-duplicate',
					'title'  => __( 'Duplication', MUCD_DOMAIN ),
					'href'   => network_admin_url( 'sites.php?page='. MUCD_SLUG_NETWORK_ACTION ),
				) );

				foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

					$menu_id  = 'blog-' . $blog->userblog_id;
					$wp_admin_bar->add_menu( array(
						'parent' => $menu_id,
						'id'     => $menu_id . '-duplicate',
						'title'  => __( 'Duplicate', MUCD_DOMAIN ),
						'href'   => network_admin_url( 'sites.php?page='. MUCD_SLUG_NETWORK_ACTION .'&amp;id=' . $blog->userblog_id ),
					) );
				}
			}

		}

		/**
		 * Adds row action 'Duplicate' on site list
		 * @since 0.2.0
		 * @param array $actions
		 * @param int $blog_id
		 */
		public static function add_site_row_action( $actions, $blog_id ) {
			$actions = array_merge( $actions, array(
				'duplicate_link' => '<a href="'. network_admin_url( 'sites.php?page='. MUCD_SLUG_NETWORK_ACTION .'&amp;id=' . $blog_id ).'">'. __( 'Duplicate', MUCD_DOMAIN ).'</a>'
			));
			return $actions;
		}

		/**
		* Adds 'Duplication' entry in sites menu
		* @since 0.2.0
		* @return [type] [description]
		*/
		public static function network_menu_add_duplicate() {
			add_submenu_page( 'sites.php', __( 'Duplicate Site', MUCD_DOMAIN ), __( 'Duplicate', MUCD_DOMAIN ), 'manage_sites', MUCD_SLUG_NETWORK_ACTION, array( __CLASS__, 'network_page_admin_duplicate_site' ) );
		}

		/**
		* Adds 'Duplication' entry in sites menu
		* @since 0.2.0
		* @return [type] [description]
		*/
		public static function network_menu_add_clone_over_primary_site() {
			add_submenu_page( 'sites.php', __( 'Clone over primary site', MUCD_DOMAIN ), __( 'Clone over primary site', MUCD_DOMAIN ), 'manage_sites', MUCD_SLUG_NETWORK_ACTION_CLONE_OVER, array( __CLASS__, 'network_page_admin_clone_over_primary' ) );
		}

		/**
		 * Check result from Duplication page / print the page
		 * @since 0.2.0
		 */
		public static function network_page_admin_duplicate_site() {
			global $current_site;

			// Capabilities test
			if ( ! current_user_can( 'manage_sites' ) ) {
				wp_die( __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN ) );
			}

			// Form Data
			$data = array(
				'source'        => ( isset( $_GET['id'] ))?intval( $_GET['id'] ):0,
				'domain'        => '',
				'title'         => '',
				'email'         => '',
				'copy_files'    => 'yes',
				'keep_users'    => 'no',
				'log'           => 'no',
				'log-path'      => '',
				'advanced'      => 'hide-advanced-options',
			);

			// Manage Form Post
			if ( isset($_REQUEST['action']) && MUCD_SLUG_ACTION_DUPLICATE == $_REQUEST['action'] && ! empty($_POST) ) {

				$data = self::check_form( $data );

				if ( isset($data['error']) ) {
					$form_message['error'] = $data['error']->get_error_message();
				}
				else {
					$form_message = MUCD_Duplicate::duplicate_site( $data );
				}
			}

			self::enqueue_script_network_duplicate();

			$select_site_list = self::select2_site_list();

			require_once MUCD_COMPLETE_PATH . '/template/network-admin-duplicate-site.php';

			MUCD_Duplicate::close_log();

		}

		/**
		 * Check result from Clone over page / print the page
		 * @since 0.2.0
		 */
		public static function network_page_admin_clone_over_primary() {

			global $current_site;

			// Capabilities test
			if ( ! current_user_can( 'manage_sites' ) ) {
				wp_die( __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN ) );
			}

			// Form Data
			$data = array(
				'source'        => ( isset( $_GET['id'] ))?intval( $_GET['id'] ):0,
				'copy_files'    => 'yes',
				'keep_users'    => 'yes',
				'log'           => 'no',
				'log-path'      => '',
			);


			// Manage Form Post
			if ( isset($_REQUEST['action']) && MUCD_SLUG_ACTION_DUPLICATE_OVER_PRIMARY == $_REQUEST['action'] && ! empty($_POST) ) {

				$data = self::check_form_clone_over( $data );

				if ( isset($data['error']) ) {
					$form_message['error'] = $data['error']->get_error_message();
				}
				else {
					$form_message = MUCD_Clone_Over_Primary::duplicate_site_over_primary( $data );
				}
			}

			self::enqueue_script_network_duplicate();

			$select_site_list = self::select2_site_list();

			require_once MUCD_COMPLETE_PATH . '/template/network-admin-clone-over-primary-site.php';

			MUCD_Duplicate::close_log();

		}

		/**
		 * Get select2 select-box
		 * @since 2.0.0.a.1
		 * @return string the output
		 */
		public static function select2_site_list() {
			$source_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
			$select2_html = '<select name="site[source]" id="mucd-site-source">';
			if ( $source_id ) {
				$value = self::fetch_initial_value( $source_id );
				$select2_html .= sprintf( '<option value="%s" selected="selected">%s</option>', esc_attr( $value['id'] ), esc_html( $value['text'] ) );
			}
			$select2_html .= '</select>';
			return $select2_html;
		}

		/**
		 * Search for sites using path
		 * @since 2.0.0.a.1
		 * @return    null    outputs a JSON string to be consumed by an AJAX call
		 */
		public static function mucd_fetch_sites() {
			$security_check_passes = (
				! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] )
				&& 'xmlhttprequest' === strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] )
				&& isset( $_GET['nonce'], $_GET['q'] )
				&& wp_verify_nonce( $_GET['nonce'],  'mucd-fetch-sites' )
			);

			if ( ! $security_check_passes ) {
				wp_send_json_error( $_GET );
			}

			// @info $site_id is actually the 'network' id
			global $wpdb, $site_id;

			$path_or_domain = defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL ? 'domain' : 'path';

			$query = "
				SELECT
					`blog_id`
				FROM
					`$wpdb->blogs`
				WHERE
					`$path_or_domain` LIKE '%%%s%%'
					AND `site_id` = %d
				LIMIT 10
			";

			// Get our sites based on the search string
			$results = $wpdb->get_results( $wpdb->prepare( $query, esc_attr( $_GET['q'] ), $site_id ) );

			// bail if we found no results
			if ( empty( $results ) ) {
				wp_send_json_error( $_GET );
			}

			self::send_sites_array_value( $results );
		}

		/**
		 * Returns select2 value based on the field's saved blog id value
		 *
		 * @since  2.0.0.a.1
		 *
		 * @param  int  $id Stored blog id
		 */
		protected static function fetch_initial_value( $id ) {
			$id = esc_attr( $id );
			$blog_details = get_blog_details( $id, true );

			$value = array(
				'id'   => $id,
				'text' => isset( $blog_details->domain )
					? $blog_details->domain . $blog_details->path
					: __( 'ERROR', MUCD_DOMAIN ),
				'details' => $blog_details,
			);

			return $value;
		}

		/**
		 * Returns select2 options based on the current search query
		 *
		 * @since  2.0.0.a.1
		 *
		 * @param  array  $results Array of DB results for the queried string
		 */
		protected static function send_sites_array_value( $results ) {
			$response  = array();
			foreach ( $results as $result ) {
				$blog = get_blog_details( $result->blog_id, true );

				if ( $blog && isset( $blog->domain ) ) {
					$response[] = array(
						'id'   => $result->blog_id,
						'text' => $blog->domain . $blog->path,
						'details' => $blog,
					);
				}
			}

			wp_send_json_success( $response );
		}

		/**
		 * Print log-error box
		 *
		 * @since 0.2.0
		 */
		public static function log_error_message() {
			$log_dir = MUCD_Duplicate::log_dir();
			echo '<div id="message" class="error">';
			echo '    <p>';
			if ( $log_dir == '' ) {
				echo __( 'The log file cannot be written', MUCD_DOMAIN );
			}
			else {
				echo __( 'The log file cannot be written to location', MUCD_DOMAIN ) . ' <strong>'. $log_dir .'</strong><br />';
				echo __( 'To enable logging, change permissions on log directory', MUCD_DOMAIN ) . '<br /><code>chmod 777 '. $log_dir .'</code>';
			}
			echo '    </p>';
			echo '</div>';
		}

		/**
		 * Print result message box error / updated
		 * @since 0.2.0
		 * @param  array $form_message messages to print
		 */
		public static function result_message( $form_message ) {
			if ( isset ( $form_message['error'] ) ) {
				echo '<div id="message" class="error">';
				echo '    <p>' . $form_message['error'] . '</p>';
				echo '</div>';
			}
			else {
				echo '<div id="message" class="updated">';
				echo '  <p>';
				echo '      <strong>' . $form_message['msg'] . ' : ' . '</strong>';
				switch_to_blog( $form_message['site_id'] );
				$user = get_current_user_id();
				echo '      <a href="' . get_dashboard_url( $user ) . '">' . __( 'Dashboard', MUCD_DOMAIN ) . '</a> - ';
				echo '      <a href="' . get_site_url() . '">' .  __( 'Visit', MUCD_DOMAIN ) . '</a> - ';
				echo '      <a href="' . admin_url( 'customize.php' ) . '">' .__( 'Customize', MUCD_DOMAIN ) . '</a>';
				if ( $log_url = MUCD_Duplicate::log_url() ) {
					echo ' - <a href="' . $log_url . '">' . __( 'View log', MUCD_DOMAIN ) . '</a>';
				}
				restore_current_blog();
				echo '  </p>';
				echo '</div>';
			}
		}

		/**
		 * Enqueue scripts for Duplication page
		 * @since 0.2.0
		 */
		public static function enqueue_script_network_duplicate( $select2 = true ) {
			// Enqueue script for user suggest on mail input
			wp_enqueue_script( 'user-suggest' );

			$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

			// Enqueue script for advanced options and enable / disable log path text input
			$dependencies = array( 'jquery' );

			// enqueue select2?
			if ( $select2 ) {
				$min = $debug ? '' : '.min';
				wp_enqueue_script( 'select2', MUCD_URL . "/js/select2/js/select2$min.js", array( 'jquery' ), '4.0.0', true );
				wp_enqueue_style( 'select2', MUCD_URL . '/js/select2/css/select2.css', array(), '4.0.0' );
				$dependencies[] = 'select2';

				// Load select2 language js file
				$select2_locale = get_locale();
				$select2_locale = str_replace( '_', '-', $select2_locale );
				if ( ! file_exists( MUCD_COMPLETE_PATH . "/js/select2/js/i18n/$select2_locale.js" ) ) {
					$select2_locale = strstr( $select2_locale, '-', true );
				}
				wp_enqueue_script( 'select2-i18n', MUCD_URL . "/js/select2/js/i18n/$select2_locale.js", $dependencies, '4.0.0', true );
				$dependencies[] = 'select2-i18n';
			}

			wp_enqueue_script( 'mucd-duplicate', MUCD_URL . '/js/network-admin-duplicate-site.js', $dependencies, MUCD::VERSION, true );

			// Localize variables for Javascript usage
			$localize_args = array(
				'use_select2'                 => $select2,
				'debug'                       => $debug,
				'nonce'                       => wp_create_nonce( 'mucd-fetch-sites' ),
				'placeholder_text'            => __( 'Start typing to search for a site', MUCD_DOMAIN ),
				'placeholder_value_text'      => __( 'This feature will not work without javascript', MUCD_DOMAIN ),
				'placeholder_no_results_text' => __( 'No results found', MUCD_DOMAIN ),
				'blogname'                    => __( 'Site Name', MUCD_DOMAIN ),
				'the_id'                      => __( 'ID', MUCD_DOMAIN ),
				'post_count'                  => __( 'Post Count', MUCD_DOMAIN ),
				'is_public'                   =>  __( 'Public', MUCD_DOMAIN ),
				'is_archived'                 => __( 'Archived', MUCD_DOMAIN ),
				'yes'                         => __( 'Yes', MUCD_DOMAIN ),
				'no'                          => __( 'No', MUCD_DOMAIN ),
			);

			// Add select2 language option
			if ( isset( $select2_locale ) && ! empty( $select2_locale ) ) {
				$localize_args['locale'] = $select2_locale;
			}

			wp_localize_script( 'mucd-duplicate', 'mucd_config', $localize_args );
		}

		/**
		 * Enqueue scripts and style for Network Settings page
		 * @since 0.2.0
		 */
		public static function enqueue_script_network_settings() {
			// Enqueue script for network settings page
			wp_enqueue_script( 'mucd-duplicate', MUCD_URL . '/js/network-admin-settings.js', array( 'jquery' ), MUCD::VERSION, true );
		}

		/**
		 * Duplication form validation
		 * @since 0.2.0
		 * @param  array $init_data default data
		 * @return array $data validated data, or errors
		 */
		public static function check_form( $init_data ) {

			$data = $init_data;
			$data['copy_files'] = 'no';
			$data['keep_users'] = 'no';
			$data['log'] = 'no';

			// Check referer and nonce
			if ( check_admin_referer( MUCD_DOMAIN ) ) {

				global $current_site;

				$error = array();

				// Merge $data / $_POST['site'] to get Posted data and fill form
				$data = array_merge( $data, $_POST['site'] );

				// format and check source
				$data['from_site_id'] = intval( $data['source'] );
				if ( $data['from_site_id'] < 1 || ! get_blog_details( $data['from_site_id'], false ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing fields', MUCD_DOMAIN ) );
				}

				$domain = '';
				if ( preg_match( '|^([a-zA-Z0-9-])+$|', $data['domain'] ) ) {
					$domain = strtolower( $data['domain'] );
				}

				// If not a subdomain install, make sure the domain isn't a reserved word
				if ( ! is_subdomain_install() ) {
					/** This filter is documented in wp-includes/ms-functions.php */
					$subdirectory_reserved_names = apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) );
					if ( in_array( $domain, $subdirectory_reserved_names ) ) {
						$error[] = new WP_Error( 'mucd_error', sprintf( __( 'The following words are reserved for use by WordPress functions and cannot be used as blog names : <code>%s</code>', MUCD_DOMAIN ) , implode( '</code>, <code>', $subdirectory_reserved_names ) ) );
					}
				}

				if ( empty( $domain ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid site address', MUCD_DOMAIN ) );
				}
				if ( is_subdomain_install() ) {
					$newdomain = $domain . '.' . preg_replace( '|^www\.|', '', $current_site->domain );
					$path      = $current_site->path;
				} else {
					$newdomain = $current_site->domain;
					$path      = $current_site->path . $domain . '/';
				}

				// format and check title
				if ( empty( $data['title'] ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid title', MUCD_DOMAIN ) );
				}

				// format and check email admin
				if ( empty( $data['email'] ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing admin email address', MUCD_DOMAIN ) );
				}
				$valid_mail = sanitize_email( $data['email'] );
				if ( is_email( $valid_mail ) ) {
					$data['email'] = $valid_mail;
				}
				else {
					$error[] = new WP_Error( 'mucd_error', __( 'Invalid admin email address', MUCD_DOMAIN ) );
				}

				$data['domain'] = $domain;
				$data['newdomain'] = $newdomain;
				$data['path'] = $path;

				$data['public'] = ! isset( $data['private'] );

				// Network
				$data['network_id'] = $current_site->id;

				if ( isset( $data['log'] ) && 'yes' == $data['log'] && ( ! isset( $data['log-path'] ) || $data['log-path'] == '' || ! MUCD_Functions::valid_path( $data['log-path'] ) ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid log directory path', MUCD_DOMAIN ) );
				}

				if ( isset( $error[0] ) ) {
					$data['error'] = $error[0];
				}
			}

			else {
				$data['error'] = __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN );
			}

			return $data;
		}

/**
		 * Duplication form validation
		 * @since 0.2.0
		 * @param  array $init_data default data
		 * @return array $data validated data, or errors
		 */
		public static function check_form_clone_over( $init_data ) {

			global $current_site;

			$data = $init_data;
			$data['copy_files'] = 'yes';
			$data['keep_users'] = 'yes';
			$data['log'] = 'no';
			$data['domain'] = 'primary';
			$data['newdomain'] = $current_site->domain;
			$data['path'] = $current_site->path;
			$data['network_id'] = $current_site->id;

			// Check referer and nonce
			if ( check_admin_referer( MUCD_DOMAIN ) ) {

				global $current_site;

				$error = array();

				// Merge $data / $_POST['site'] to get Posted data and fill form
				$data = array_merge( $data, $_POST['site'] );

				// format and check source
				$data['from_site_id'] = intval( $data['source'] );
				if ( $data['from_site_id'] < 2 || ! get_blog_details( $data['from_site_id'], false ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing fields', MUCD_DOMAIN ) );
				}			

				if ( isset( $data['log'] ) && 'yes' == $data['log'] && ( ! isset( $data['log-path'] ) || $data['log-path'] == '' || ! MUCD_Functions::valid_path( $data['log-path'] ) ) ) {
					$error[] = new WP_Error( 'mucd_error', __( 'Missing or invalid log directory path', MUCD_DOMAIN ) );
				}

				if ( isset( $error[0] ) ) {
					$data['error'] = $error[0];
				}
			}

			else {
				$data['error'] = __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN );
			}

			return $data;
		}

		/**
		 * Save duplication options on network settings page
		 * @since 0.2.0
		 */
		public static function save_admin_network_option_page() {

			if ( ! empty( $_POST ) && isset( $_POST[ MUCD_SLUG_ACTION_SETTINGS ] ) ) {

				if ( check_admin_referer( 'siteoptions' ) ) {

					if ( isset( $_POST['mucd_copy_files']) && 'yes' == $_POST['mucd_copy_files'] ) {
						update_site_option( 'mucd_copy_files', 'yes' );
					}
					else {
						update_site_option( 'mucd_copy_files', 'no' );
					}

					if ( isset( $_POST['mucd_keep_users']) && 'yes' == $_POST['mucd_keep_users'] ) {
						update_site_option( 'mucd_keep_users', 'yes' );
					}
					else {
						update_site_option( 'mucd_keep_users', 'no' );
					}

					if ( isset( $_POST['mucd_log']) && 'yes' == $_POST['mucd_log'] ) {

						update_site_option( 'mucd_log', 'yes' );

						if ( isset( $_POST['mucd_log_dir'] ) ) {
							update_site_option( 'mucd_log_dir', $_POST['mucd_log_dir'] );
						}
					}
					else {
						update_site_option( 'mucd_log', 'no' );
					}

				}
			}

		}

		/**
		 * Print duplication options on network settings page
		 * @since 0.2.0
		 */
		public static function admin_network_option_page() {
			self::enqueue_script_network_settings();
			require_once MUCD_COMPLETE_PATH . '/template/network-admin-network-settings.php';
		}

	}
}
