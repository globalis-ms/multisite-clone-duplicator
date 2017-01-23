<?php
/**
 * Option management for the plugin
 */
if( !class_exists( 'MUCD_Option' ) ) {

    class MUCD_Option {

        /**
         * Init 'mucd_duplicable' options
         * @param string $blogs_value the value for blogs options
         * @param string $network_value the value for site option
         * @since 0.2.0
         */
        public static function init_duplicable_option($blogs_value = "no", $network_value = "all") {
            $network_blogs = MUCD_Functions::get_sites();
            foreach( $network_blogs as $blog ){
                $blog_id = $blog['blog_id'];
                add_blog_option( $blog_id, 'mucd_duplicable', $blogs_value);
            }
            add_site_option( 'mucd_duplicables', $network_value );
        }

        /**
         * Delete 'mucd_duplicable' option for all sites
         * @since 0.2.0
         */
        public static function delete_duplicable_option() {
            $network_blogs = MUCD_Functions::get_sites();
            foreach( $network_blogs as $blog ){
                $blog_id = $blog['blog_id'];
                delete_blog_option( $blog_id, 'mucd_duplicable');
            }
            delete_site_option( 'mucd_duplicables');
        }

        /**
         * Set 'mucd_duplicable' option to "yes" for the list of blogs, other to "no"
         * @since 0.2.0
         * @param array $blogs list of blogs we want the option set to "yes"
         */
        public static function set_duplicable_option($blogs) {
            $network_blogs = MUCD_Functions::get_sites();
            foreach( $network_blogs as $blog ){
                if(in_array($blog['blog_id'], $blogs)) {
                    update_blog_option( $blog['blog_id'], 'mucd_duplicable', "yes");
                }
                else {
                    update_blog_option($blog['blog_id'], 'mucd_duplicable', "no");
                }
            }
        }

        /**
         * Add plugin default options
         * @since 1.3.0
         */
        public static function init_options() {
            add_site_option('mucd_copy_files', 'yes');
            add_site_option('mucd_keep_users', 'yes');
            add_site_option('mucd_log', 'no');
            $upload_dir = wp_upload_dir();
            add_site_option('mucd_log_dir', $upload_dir['basedir'] . '/multisite-clone-duplicator-logs/');
            add_site_option('mucd_disable_enhanced_site_select', 'no');
            MUCD_Option::init_duplicable_option();
        }

        /**
         * Removes plugin options
         * @since 1.3.0
         */
        public static function delete_options() {
            delete_site_option('mucd_copy_files');
            delete_site_option('mucd_keep_users');
            delete_site_option('mucd_log');
            delete_site_option('mucd_log_dir');
            delete_site_option('mucd_disable_enhanced_site_select');
            MUCD_Option::delete_duplicable_option();
        }
      
        /**
         * Get log directory option
         * @since 0.2.0
         * @return string the path
         */
        public static function get_option_log_directory() {
            $upload_dir = wp_upload_dir();   
            return get_site_option('mucd_log_dir', $upload_dir['basedir'] . '/multisite-clone-duplicator-logs/');
        }

        /**
         * Get directories to exclude from file copy when duplicated site is primary site
         * @since 0.2.0
         * @return  array of string
         */
        public static function get_primary_dir_exclude() {
            return array(
                'sites',
            );
        }

        /**
         * Get default options that should be preserved in the new blog.
         * @since 0.2.0
         * @return  array of string
         */
        public static function get_default_saved_option() {
            return array(
                'siteurl'=>'',
                'home'=>'',
                'upload_path'=>'',
                'fileupload_url'=>'',
                'upload_url_path'=>'',
                'admin_email'=>'',
                'blogname'=>''
            );
        }

        /**
         * Get filtered options that should be preserved in the new blog.
         * @since 0.2.0
         * @return  array of string (filtered)
         */
        public static function get_saved_option() {
            return apply_filters('mucd_copy_blog_data_saved_options', MUCD_Option::get_default_saved_option());
        }   
        
        /**
         * Get default fields to scan for an update after data copy
         * @since 0.2.0
         * @return array '%table_name' => array('%field_name_1','%field_name_2','%field_name_3', ...)
         */
        public static function get_default_fields_to_update() {
            return array (
                'commentmeta' => array(),
                'comments' => array(),
                'links' => array('link_url', 'link_image'),
                'options' => array('option_name', 'option_value'),
                'postmeta' => array('meta_value'),
                'posts' => array('post_content', 'guid'),
                'terms' => array(),
                'term_relationships' => array(),
                'term_taxonomy' => array(),
            );
        }

        /**
         * Get filtered fields to scan for an update after data copy
         * @since 0.2.0
         * @return  array of string (filtered)
         */
        public static function get_fields_to_update() {
            return apply_filters('mucd_default_fields_to_update', MUCD_Option::get_default_fields_to_update());
        }
      
        /**
         * Get default tables to duplicate when duplicated site is primary site
         * @since 0.2.0
         * @return  array of string
         */
        public static function get_default_primary_tables_to_copy() {
            return array (
                'commentmeta',
                'comments',
                'links',
                'options',
                'postmeta',
                'posts',
                'terms',
                'term_relationships',
                'term_taxonomy',
                'termmeta',
            );
        }

        /**
         * Get filtered tables to duplicate when duplicated site is primary site
         * @since 0.2.0
         * @return  array of string (filtered)
         */
        public static function get_primary_tables_to_copy() {
            return apply_filters('mucd_default_primary_tables_to_copy', MUCD_Option::get_default_primary_tables_to_copy());
        }

    }
}