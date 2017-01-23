<?php echo '<input type="hidden" id="'.MUCD_SLUG_ACTION_SETTINGS.'" name="'.MUCD_SLUG_ACTION_SETTINGS.'" value="_'.MUCD_SLUG_ACTION_SETTINGS.'" />'; ?>

<h3 id="mucd_duplication"><?php echo MUCD_NETWORK_MENU_DUPLICATION; ?></h3>
<table class="form-table">

    <tr>
        <th scope="row"><?php echo MUCD_NETWORK_SETTINGS_DUPLICABLE_WEBSITES; ?></th>
        <td>
            <label><input <?php checked( get_site_option( 'mucd_duplicables', 'all' ), 'all' ); ?> type="radio" id="radio-duplicables-all" name="duplicables" value="all"><?php echo MUCD_NETWORK_SETTINGS_DUPLICABLE_ALL; ?></label><br><br>
            <label><input <?php checked( get_site_option( 'mucd_duplicables', 'all' ), 'selected' ); ?> type="radio" id="radio-duplicables-selected" name="duplicables" value="selected"><?php echo MUCD_NETWORK_SETTINGS_DUPLICABLE_SELECTED; ?></label><br><br>


            <?php
            $network_blogs = MUCD_Functions::get_sites();
            echo '<div class="multiselect" id="site-select-box">';
            foreach( $network_blogs as $blog ) {
                echo '    <label><input ' . checked(get_blog_option( $blog['blog_id'], 'mucd_duplicable', "no"), 'yes', false) . ' class="duplicables-list" type="checkbox" name="duplicables-list[]" value="'.$blog['blog_id'].'" />' . substr($blog['domain'] . $blog['path'], 0, -1) . '</label>';
            }
            echo '</div>';
            ?>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php echo MUCD_NETWORK_PAGE_USE_ENHANCED_FOR_SITE_SELECT; ?></th>
        <td>
            <label><input <?php checked( get_site_option( 'mucd_disable_enhanced_site_select', 'no' ), 'yes' ); ?> id="use-enhanced-select" name="mucd_disable_enhanced_site_select" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_USE_ENHANCED_FOR_SITE_SELECT_TEXT_1; ?></label>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES; ?></th>
        <td>
            <label><input <?php checked( get_site_option( 'mucd_copy_files', 'yes' ), 'yes' ); ?> name="mucd_copy_files" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES_TEXT_1; ?></label>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS; ?></th>
        <td>
            <label><input <?php checked( get_site_option( 'mucd_keep_users', 'yes' ), 'yes' ); ?> name="mucd_keep_users" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS_TEXT_1; ?></label>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG; ?></th>
        <td>
            <label><input <?php checked( get_site_option( 'mucd_log', 'no' ), 'yes' ); ?> id="log-box" name="mucd_log" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_1; ?></label>
            <br /><br /><label><?php echo MUCD_NETWORK_PAGE_DUPLICATE_LOG_TEXT_2; ?> : <input id="log-path" name="mucd_log_dir" type="text"  class="large-text" value="<?php echo MUCD_Option::get_option_log_directory(); ?>" /></label>
        </td>
    </tr>

</table>
