<div class="wrap">
    <h2 id="duplicate-site"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_TITLE ?></h2>

    <?php

    if(MUCD_Duplicate::log_error()) { 
        MUCD_Admin::log_error_message();
    }

    if( isset( $form_message ) ) {
        MUCD_Admin::result_message($form_message);
    }

    ?>

    <form method="post" action="<?php echo network_admin_url('sites.php?page=' . MUCD_SLUG_NETWORK_ACTION . '&action=' . MUCD_SLUG_ACTION_DUPLICATE); ?>">
        <?php wp_nonce_field( MUCD_DOMAIN ); ?>

        <table class="form-table">
           <tr class="form-required">
                <th scope='row'><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_SOURCE ; ?></th>
                <td>
                    <?php echo $select_site_list; ?>                      
                </td>
            </tr>

            <tr class="form-required">
                <th scope='row'><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS ; ?></th>
                <td>
                <?php if ( is_subdomain_install() ) { ?>
                <input name="site[domain]" type="text" class="large-text" title="<?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS ; ?>"  value="<?php echo $data['domain']?>"/><span class="no-break">.<?php echo preg_replace( '|^www\.|', '', $current_site->domain ); ?></span>
                <?php } else {
                    echo $current_site->domain . $current_site->path ?><br /><input name="site[domain]" class="large-text" type="text" title="<?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS ; ?>" value="<?php echo $data['domain']?>"/>
                <?php }
                echo '<p>' . MUCD_NETWORK_PAGE_DUPLICATE_FIELD_ADDRESS_INFO . '</p>';
                ?>
                </td>
            </tr>

            <tr class="form-required">
                <th scope='row'><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_TITLE; ?></th>
                <td><input name="site[title]" type="text" title="<?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_TITLE ; ?>" class="large-text" value="<?php echo $data['title']?>"/></td>
            </tr>

            <!-- Copy from  wp-admin/network/site-new.php : 141 to 147 -->
            <!-- Warning : name="blog[email] changed to site[email] -->
            <tr class="form-required">
                <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL; ?></th>
                <td><input name="site[email]" type="text" class="large-text wp-suggest-user" data-autocomplete-type="search" data-autocomplete-field="user_email" value="<?php echo $data['email']; ?>" title="<?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL; ?>"/></td>
            </tr>
            <tr class="form-field">
                <td colspan="2"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL_INFO_1; ?><br /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_EMAIL_INFO_2; ?></td>
            </tr>
            <!-- END Copy from  wp-admin/network/site-new.php : 141 to 147 -->
        </table>

        <p>
            <a id="show-advanced-options" href="#"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_ADVANCED_SHOW; ?> &rsaquo;</a>
            <a id="hide-advanced-options" style="display: none;" href="#"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_ADVANCED_HIDE; ?> &lsaquo;</a>
            <input id="status-advanced-options" type="hidden" name="site[advanced]" value="<?php echo $data['advanced']; ?>" />
        </p>

        <table class="form-table" id="advanced-options" style="display: none;">
            <tr>
                <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES; ?></th>
                <td>
                    <label><input <?php checked( get_site_option( 'mucd_copy_files', 'yes' ), 'yes' ); ?> name="site[copy_files]" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES_TEXT_1; ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS; ?></th>
                <td>
                    <label><input <?php checked( get_site_option( 'mucd_keep_users', 'yes' ), 'yes' ); ?> name="site[keep_users]" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS_TEXT_1; ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG; ?></th>
                <td>
                    <label><input <?php checked( get_site_option( 'mucd_log', 'no' ), 'yes' ); ?> id="log-box" name="site[log]" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_1; ?></label>
                    <br /><br /><label><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_2; ?> : <input id="log-path" name="site[log-path]" type="text"  class="large-text" value="<?php echo MUCD_Option::get_option_log_directory(); ?>"/></label>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class='button button-primary' type='submit' value='<?php echo MUCD_NETWORK_PAGE_DUPLICATE_BUTTON_COPY ; ?>' />
        </p>

    </form>
</div>