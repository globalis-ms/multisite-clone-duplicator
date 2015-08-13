<?php

global $current_site;

$data['copy_files'] = isset( $validated_data['copy_files'] ) ? checked( $validated_data['copy_files'], true, false ) : checked( MUCD_Option::mod_copy_files(), true, false );
$data['keep_users'] = isset( $validated_data['keep_users'] ) ? checked( $validated_data['keep_users'], true, false ) : checked( MUCD_Option::mod_keep_users(), true, false );
$data['log']        = isset( $validated_data['log'] ) ? checked( $validated_data['log'], true, false ) : checked( MUCD_Option::mod_log(), true, false );
$data['log-path']   = isset( $validated_data['log-path'] ) ? $validated_data['log-path'] : MUCD_Option::log_directory();

?>

<div class="wrap">
	<h2 id="duplicate-site"><?php _e( 'Clone over primary site', MUCD_DOMAIN ) ?></h2>

	<div id="message" class="error">
		<p><?php _e( '<strong>WARNING</strong> : cloning a site over your primary site will delete all its uploads files and totally delete its previous database tables. All the media and the content you have in this site will be deleted.', MUCD_DOMAIN ) ?></p>
		<p><?php _e( '<strong>This operation will make hard changes in database and could totally break your primary site. Please ensure you have a fresh backup of all your files and database before going further !</strong>', MUCD_DOMAIN ) ?></p>
	</div>

	<?php MUCD_Functions::print_notices(); ?>

	<form method="post" action="<?php echo network_admin_url( 'sites.php?page=' . MUCD_SLUG_NETWORK_ACTION_CLONE_OVER . '&action=' . MUCD_SLUG_ACTION_DUPLICATE_OVER_PRIMARY ); ?>">
		<?php wp_nonce_field( MUCD_DOMAIN ); ?>

		<table class="form-table">
		   <tr class="form-required">
				<th scope='row'><?php _e( 'Original site to copy', MUCD_DOMAIN ) ; ?></th>
				<td>
					<?php echo $select_site_list; ?>
				</td>
			</tr>

		</table>

		<p>
			<a id="show-advanced-options" href="#"><?php _e( 'Show advanced options', MUCD_DOMAIN ); ?> &rsaquo;</a>
			<a id="hide-advanced-options" style="display: none;" href="#"><?php _e( 'Hide advanced options', MUCD_DOMAIN ); ?> &lsaquo;</a>
		</p>

		<table class="form-table" id="advanced-options" style="display: none;">
			<tr>
				<th scope="row"><?php _e( 'Files', MUCD_DOMAIN ); ?></th>
				<td>
					<label><input <?php echo $data['copy_files']; ?> id="site_copy_files" name="site[copy_files]" type="checkbox" value="yes" /><?php _e( 'Duplicate files from duplicated site upload directory', MUCD_DOMAIN ); ?></label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e( 'Users and roles', MUCD_DOMAIN ); ?></th>
				<td>
					<label><input <?php echo $data['keep_users']; ?> id="site_keep_users" name="site[keep_users]" type="checkbox" value="yes" /><?php _e( 'Keep users and roles from duplicated site', MUCD_DOMAIN ); ?></label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e( 'Log', MUCD_DOMAIN ); ?></th>
				<td>
					<label><input <?php echo $data['log']; ?> id="log-box" name="site[log]" type="checkbox" value="yes" /><?php _e( 'Generate log file', MUCD_DOMAIN ); ?></label>
					<br /><br /><label><?php _e( 'Log directory', MUCD_DOMAIN ); ?> : <input id="log-path" name="site[log-path]" type="text"  class="large-text" value="<?php echo $data['log-path']; ?>"/></label>
				</td>
			</tr>
		</table>


		<p class="submit">
			<input class='button button-primary' type='submit' value='<?php _e( 'Clone over', MUCD_DOMAIN ); ?>' />
		</p>

	</form>
</div>
