<?php

if ( ! class_exists( 'MUCD_Admin' ) ) {

	class MUCD_Admin {

		/**
		 * Register hooks used on admin side by the plugin
		 */
		public static function hooks() {
			// Network menu
			add_action( 'network_admin_menu', array( __CLASS__, 'network_menu' ) );
			// Hook to rows on network sites listing
			add_filter( 'manage_sites_action_links', array( __CLASS__, 'add_site_row_action' ), 10, 2 );
			// Network admin bar
			add_action( 'admin_bar_menu', array( __CLASS__, 'admin_network_menu_bar' ) , 300 );
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
		public static function network_menu() {
			add_submenu_page( 'sites.php', __( 'Duplicate Site', MUCD_DOMAIN ), __( 'Duplicate', MUCD_DOMAIN ), 'manage_sites', MUCD_SLUG_NETWORK_ACTION, array( __CLASS__, 'network_page_admin_clone_site' ) );
			add_submenu_page( 'sites.php', __( 'Clone over primary site', MUCD_DOMAIN ), __( 'Primary site', MUCD_DOMAIN ), 'manage_sites', MUCD_SLUG_NETWORK_ACTION_CLONE_OVER, array( __CLASS__, 'network_page_admin_clone_site_over_primary' ) );
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
		 * Check result from Duplication page / print the page
		 * @since 0.2.0
		 */
		public static function network_page_admin_clone_site() {

			// Capabilities test
			if ( ! current_user_can( 'manage_sites' ) ) {
				wp_die( __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN ) );
			}

			// Manage Form Post
			if ( isset($_REQUEST['action']) && MUCD_SLUG_ACTION_DUPLICATE == $_REQUEST['action'] && ! empty($_POST) ) {

				$data = MUCD_Data_Validation::check_form_clone_site();

				if ( isset($data['error'] ) ) {
					$form_message['error'] = $data['error']->get_error_message();
				}
				else {
					$form_message = MUCD_Duplicate::duplicate_site( $data );
				}

			}

			$select_site_list = MUCD_Select2::select2_site_list();

			require_once MUCD_PATH_TEMPLATES . '/network-admin-clone-site.php';
		}

		public static function network_page_admin_clone_site_over_primary() {

			// Capabilities test
			if ( ! current_user_can( 'manage_sites' ) ) {
				wp_die( __( 'Sorry, you don\'t have permissions to use this page.', MUCD_DOMAIN ) );
			}

			// Manage Form Post
			if ( isset($_REQUEST['action']) && MUCD_SLUG_ACTION_DUPLICATE_OVER_PRIMARY == $_REQUEST['action'] && ! empty($_POST) ) {

				$data = MUCD_Data_Validation::check_form_clone_site_over_primary();

				if ( isset($data['error'] ) ) {
					$form_message['error'] = $data['error']->get_error_message();
				}
				else {
					$form_message = MUCD_Duplicate::duplicate_site_over_primary( $data );
				}

			}

			$select_site_list = MUCD_Select2::select2_site_list();

			require_once MUCD_PATH_TEMPLATES . '/network-admin-clone-site-over-primary.php';
		}

	}

	MUCD_Admin::hooks();
}
