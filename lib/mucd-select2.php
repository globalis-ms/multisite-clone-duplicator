<?php

if ( ! class_exists( 'MUCD_Admin' ) ) {

	class MUCD_Select2 {

		public static function hooks() {
			add_action( 'wp_ajax_mucd_fetch_sites', array( __CLASS__, 'mucd_fetch_sites' ) );
		}

		/**
		 * Get select2 select-box
		 * @since 2.0.0.a.1
		 * @return string the output
		 */
		public static function select2_site_list( $validated_data ) {

			if( isset( $validated_data['from_site_id'] ) ) {
				$source_id = $validated_data['from_site_id'];
			}
			else if ( isset( $_GET['id'] ) ) {
				$source_id = intval( $_GET['id'] );
			}
			else {
				$source_id = false;
			}
			
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
					( `$path_or_domain` LIKE '%%%s%%'
						OR `blog_id` LIKE '%d%%' )
					AND `site_id` = %d
				LIMIT 10
			";

			// Get our sites based on the search string
			$results = $wpdb->get_results( $wpdb->prepare( $query, esc_attr( $_GET['q'] ), esc_attr( $_GET['q'] ), $site_id ) );

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

	}

	MUCD_Select2::hooks();
}
