<?php echo '<input type="hidden" id="'.MUCD_SLUG_ACTION_SETTINGS.'" name="'.MUCD_SLUG_ACTION_SETTINGS.'" value="_'.MUCD_SLUG_ACTION_SETTINGS.'" />'; ?>

<h3 id="mucd_duplication"><?php echo __( 'Duplication default options', MUCD_DOMAIN ); ?></h3>
<table class="form-table">

    <tr>
		<th scope="row"><?php echo __( 'Files', MUCD_DOMAIN ); ?></th>
		<td>
			<label><input <?php checked( get_site_option( 'mucd_copy_files', 'yes' ), 'yes' ); ?> name="mucd_copy_files" type="checkbox" value="yes" /><?php echo __( 'Duplicate files from duplicated site upload directory', MUCD_DOMAIN ); ?></label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php echo __( 'Users and roles', MUCD_DOMAIN ); ?></th>
		<td>
			<label><input <?php checked( get_site_option( 'mucd_keep_users', 'yes' ), 'yes' ); ?> name="mucd_keep_users" type="checkbox" value="yes" /><?php echo __( 'Keep users and roles from duplicated site', MUCD_DOMAIN ); ?></label>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php echo __( 'Log', MUCD_DOMAIN ); ?></th>
		<td>
			<label><input <?php checked( get_site_option( 'mucd_log', 'no' ), 'yes' ); ?> id="log-box" name="mucd_log" type="checkbox" value="yes" /><?php echo __( 'Generate log file', MUCD_DOMAIN ); ?></label>
			<br /><br /><label><?php echo __( 'Log directory', MUCD_DOMAIN ); ?> : <input id="log-path" name="mucd_log_dir" type="text"  class="large-text" value="<?php echo MUCD_Option::get_option_log_directory(); ?>" /></label>
		</td>
	</tr>

</table>
