<?php echo '<input type="hidden" id="'.MUCD_SLUG_ACTION_SETTINGS.'" name="'.MUCD_SLUG_ACTION_SETTINGS.'" value="_'.MUCD_SLUG_ACTION_SETTINGS.'" />'; ?>

<h3 id="mucd_duplication"><?php echo __( 'Duplication default options', MUCD_DOMAIN ); ?></h3>
<table class="form-table">

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
