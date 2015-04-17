<?php

require_once MUCD_COMPLETE_PATH . '/include/admin.php';
require_once 'phar:///usr/local/bin/wp/php/commands/site.php';

/**
 * Implements example command.
 */
class Duplicate_Command extends Site_Command {

    /**
     * Prints a greeting.
     * 
     * ## OPTIONS
     * 
     * <name>
     * : The name of the person to greet.
     * 
     * ## EXAMPLES
     * 
     *     wp example hello Newman
     *
     * @synopsis --source=<bar> --domain=<bar> --title=<bar> [--email=<bar>] [--copy_files=<bar>] [--keep_users=<bar>] [--log-path=<bar>] [--copy_files=<bar>]
     */
    function duplicate( $args, $assoc_args ) {

        //WP_CLI::line("ORIGDIR = " . ORIGDIR);
        //WP_CLI::line("SELF_PATH = " . SELF_PATH);
        //WP_CLI::line("\$WP_CLI_PHP = " . $WP_CLI_PHP);
        //WP_CLI::line("\$php = " . $php);
        //WP_CLI::line("\$WP_CLI_PHP_ARGS = " . $WP_CLI_PHP_ARGS);
        //WP_CLI::line("\$SCRIPT_PATH = " . $SCRIPT_PATH);

            $default_data = array(
                'source'        => '',
                'domain'        => '',
                'title'         => '',
                'email'         => '',
                'copy_files'    => 'yes',
                'keep_users'    => 'no',
                'log-path'      => '',
            );

            if(isset($assoc_args['log-path'])) {
                $assoc_args['log'] = 'yes';
            }
            else {
                $assoc_args['log'] = 'no';
            }

            $data = MUCD_Admin::check_cli($default_data, $assoc_args);
            $form_message = MUCD_Duplicate::duplicate_site($data);

           if(isset($form_message['error']) ) {
                WP_CLI::error($form_message['error']);
            }
            else {
                WP_CLI::success($form_message['msg']);
                switch_to_blog($form_message['site_id']);
                $user = get_current_user_id();
                WP_CLI::line("ID        : " . $form_message['site_id']);
                WP_CLI::line("Title     : " . get_bloginfo('name'));
                WP_CLI::line("Front     : " . get_site_url()); 
                WP_CLI::line("Dashboard : " . admin_url());
                WP_CLI::line("Customize : " . admin_url( 'customize.php' )); 
                restore_current_blog();
            }


    }
}

WP_CLI::add_command( 'site', 'Duplicate_Command' );