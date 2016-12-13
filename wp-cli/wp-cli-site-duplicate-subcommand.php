<?php

/**
 * Implements duplication command.
 */

if( class_exists( 'MUCD_Functions' ) && !class_exists( 'Site_Duplicate_Subcommand' ) ) :

class Site_Duplicate_Subcommand extends WP_CLI_Command {

    /**
     * Duplicate a site in a multisite install.
     *
     * ## OPTIONS
     *
     * --slug=<slug>
     * : Path for the new site. Subdomain on subdomain installs, directory on subdirectory installs.
     *
     * --source=<site_id>
     * : ID of the site to duplicate.
     *
     * [--title=<title>]
     * : Title of the new site. Default: prettified slug.
     *
     * [--email=<email>]
     * : Email for Admin user. User will be created if none exists. Assignement to Super Admin if not included.
     *
     * [--network_id=<network-id>]
     * : Network to associate new site with. Defaults to current network (typically 1).
     *
     * [--private]
     * : If set, the new site will be non-public (not indexed)
     *
     * [--porcelain]
     * : If set, only the site id will be output on success.
     *
     * [--v]
     * : If set, print more details about the new site (Verbose mode). Do not work if --procelain is set.
     *
     * [--do_not_copy_files]
     * : If set, files of the duplicated site will not be copied.
     *
     * [--keep_users]
     * : If set, the new site will have the same users as the duplicated site.
     *
     * [--log=<dir_path>]
     * : If set, a log will be written in this directory (please check this directory is writable).
     *
     * @alias clone
     *
     * @synopsis --slug=<slug> --source=<site_id> [--title=<title>] [--email=<email>] [--network_id=<network-id>] [--private] [--porcelain] [--v] [--do_not_copy_files] [--keep_users] [--log=<dir_path>]
     */
    public function __invoke( $_, $assoc_args ) {
        if ( !is_multisite() ) {
            WP_CLI::error( 'This is not a multisite install.' );
        }

        global $wpdb, $current_site;

        $base = $assoc_args['slug'];
        $title = isset( $assoc_args['title'] ) ? $assoc_args['title'] : ucfirst( $base );

        $email = empty( $assoc_args['email'] ) ? '' : $assoc_args['email'];

        // Network
        if ( !empty( $assoc_args['network_id'] ) ) {
            $network = MUCD_Functions::get_network( $assoc_args['network_id'] );
            if ( $network === false ) {
                WP_CLI::error( sprintf( 'Network with id %d does not exist.', $assoc_args['network_id'] ) );
            }
        }
        else {
            $network = $current_site;
        }
        $network_id = $network->id;

        $public = !isset( $assoc_args['private'] );

        // Sanitize
        if ( preg_match( '|^([a-zA-Z0-9-])+$|', $base ) ) {
            $base = strtolower( $base );
        }

        // If not a subdomain install, make sure the domain isn't a reserved word
        if ( !is_subdomain_install() ) {
            $subdirectory_reserved_names = apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) );
            if ( in_array( $base, $subdirectory_reserved_names ) ) {
                WP_CLI::error( 'The following words are reserved and cannot be used as blog names: ' . implode( ', ', $subdirectory_reserved_names ) );
            }
        }

        // Check for valid email, if not, use the first Super Admin found
        // Probably a more efficient way to do this so we dont query for the
        // User twice if super admin
        $email = sanitize_email( $email );
        if ( empty( $email ) || !is_email( $email ) ) {
            $super_admins = get_super_admins();
            $email = '';
            if ( !empty( $super_admins ) && is_array( $super_admins ) ) {
                // Just get the first one
                $super_login = $super_admins[0];
                $super_user = get_user_by( 'login', $super_login );
                if ( $super_user ) {
                    $email = $super_user->user_email;
                }
            }
        }

        if ( is_subdomain_install() ) {
            $newdomain = $base.'.'.preg_replace( '|^www\.|', '', $network->domain );
            $path = $network->path;
        }
        else {
            $newdomain = $network->domain;
            $path = $network->path . $base . '/';
        }

        // Source ?
        $source = $assoc_args['source'];
        if(! intval($source) != 0)
            WP_CLI::error($source . ' is not a valid site ID.');
        if (! MUCD_Functions::site_exists($source))
            WP_CLI::error('There is no site with ID=' . $source . '. The site to duplicate must be an existing site of the network.');


        // Copy files ?
        $copy_files = isset( $assoc_args['do_not_copy_files'] ) ? 'no' : 'yes';

        // Keep users ?
        $keep_users = isset( $assoc_args['keep_users'] ) ? 'yes' : 'no';

        // Write log
        if(isset( $assoc_args['log'] )) {
            $log = 'yes';
            $log_path = $assoc_args['log'];
        }
        else {
            $log = 'no';
            $log_path = '';
        }

        $data = array (
            'source' => $source,
            'domain' => $base,
            'title' => $title,
            'email' => $email,
            'copy_files' => $copy_files,
            'keep_users' => $keep_users,
            'log' => $log,
            'log-path' => $log_path,
            'from_site_id' => $source,
            'newdomain' => $newdomain,
            'path' => $path,
            'public' => $public,
            'network_id' => $network_id,
        );

        $wpdb->hide_errors();
        $form_message = MUCD_Duplicate::duplicate_site($data);
        $wpdb->show_errors();

        if(isset($form_message['error']) ) {
            WP_CLI::error($form_message['error']);
        }
        else {
            if ( isset( $assoc_args['porcelain'] ) ) {
                WP_CLI::line( $form_message['site_id'] );
            }
            else {
                switch_to_blog($form_message['site_id']);

                if(! isset( $assoc_args['v'] )) {
                    WP_CLI::success( 'Site ' . $form_message['site_id'] . ' created: ' . get_site_url() );                
                }
                else { // Verbose mode
                    WP_CLI::success($form_message['msg']);
                    $user = get_current_user_id();
                    WP_CLI::line("ID        : " . $form_message['site_id']);
                    WP_CLI::line("Title     : " . get_bloginfo('name'));
                    WP_CLI::line("Front     : " . get_site_url()); 
                    WP_CLI::line("Dashboard : " . admin_url());
                    WP_CLI::line("Customize : " . admin_url( 'customize.php' )); 
                }

                restore_current_blog();
            }

        }
    }

}

WP_CLI::add_command( 'site duplicate', 'Site_Duplicate_Subcommand' );

endif;