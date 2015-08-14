<?php

if ( ! class_exists( 'MUCD_Scripts' ) ) {

	class MUCD_Scripts {


		public static function hooks() {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_conditionnal' ) );
		}

		public static function enqueue_conditionnal( $hook ) {
			if ( 'settings.php' == $hook ) {
				self::enqueue_script_network_settings();
			}
			else if ( 'sites_page_multisite-clone-duplicator-clone-site' == $hook ) {
				self::enqueue_script_network_clone_site();
			}
			else if ( 'sites_page_multisite-clone-duplicator-clone-site-over-primary' == $hook ) {
				self::enqueue_script_network_clone_site_over_primary();
			}
		}


		/**
		 * Enqueue scripts and style for Network Settings page
		 * @since 0.2.0
		 */
		public static function enqueue_script_network_settings() {
			// Enqueue script for network settings page
			wp_enqueue_script( 'mucd-duplicate', MUCD_URL_PLUGIN_SCRIPTS . '/network-admin-settings.js', array( 'jquery' ), MUCD::VERSION, true );
		}

		/**
		 * Enqueue scripts for Duplication page
		 * @since 0.2.0
		 */
		public static function enqueue_script_network_clone_site() {

			// Enqueue script for user suggest on mail input
			wp_enqueue_script( 'user-suggest' );

			$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

			// Enqueue script for advanced options and enable / disable log path text input
			$dependencies = array( 'jquery' );

			// SELECT2
			$min = $debug ? '' : '.min';
			wp_enqueue_script( 'select2', MUCD_URL_PLUGIN_SCRIPTS . "/select2/js/select2$min.js", array( 'jquery' ), '4.0.0', true );
			wp_enqueue_style( 'select2',  MUCD_URL_PLUGIN_SCRIPTS . '/select2/css/select2.css', array(), '4.0.0' );
			$dependencies[] = 'select2';

			// Load select2 language js file
			$select2_locale = get_locale();
			$select2_locale = str_replace( '_', '-', $select2_locale );
			if ( ! file_exists( MUCD_PATH_PLUGIN . "/js/select2/js/i18n/$select2_locale.js" ) ) {
				$select2_locale = strstr( $select2_locale, '-', true );
			}
			wp_enqueue_script( 'select2-i18n', MUCD_URL_PLUGIN_SCRIPTS . "/select2/js/i18n/$select2_locale.js", $dependencies, '4.0.0', true );
			$dependencies[] = 'select2-i18n';

			wp_enqueue_script( 'mucd-duplicate',  MUCD_URL_PLUGIN_SCRIPTS . '/network-admin-clone-site.js', $dependencies, MUCD::VERSION, true );

			// Localize variables for Javascript usage
			$localize_args = array(
				'debug'                       => $debug,
				'nonce'                       => wp_create_nonce( 'mucd-fetch-sites' ),
				'placeholder_text'            => __( 'Start typing to search for a site', MUCD_DOMAIN ),
				'placeholder_value_text'      => __( 'This feature will not work without javascript', MUCD_DOMAIN ),
				'placeholder_no_results_text' => __( 'No results found', MUCD_DOMAIN ),
				'blogname'                    => __( 'Site Name', MUCD_DOMAIN ),
				'the_id'                      => __( 'ID', MUCD_DOMAIN ),
				'post_count'                  => __( 'Post Count', MUCD_DOMAIN ),
				'is_public'                   => __( 'Public', MUCD_DOMAIN ),
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

		public static function enqueue_script_network_clone_site_over_primary() {
			self::enqueue_script_network_clone_site();
		}

	}

	MUCD_Scripts::hooks();
}