<?php

global $current_site;

$data['domain']     = isset( $validated_data['domain'] ) ? $validated_data['domain'] : '';
$data['title']      = isset( $validated_data['title'] ) ? $validated_data['title'] : '';
$data['email']      = isset( $validated_data['email'] ) ? $validated_data['email'] : '';
$data['copy_files'] = isset( $validated_data['copy_files'] ) ? checked( $validated_data['copy_files'], true, false ) : checked( MUCD_Option::mod_copy_files(), true, false );
$data['keep_users'] = isset( $validated_data['keep_users'] ) ? checked( $validated_data['keep_users'], true, false ) : checked( MUCD_Option::mod_keep_users(), true, false );
$data['log']        = isset( $validated_data['log'] ) ? checked( $validated_data['log'], true, false ) : checked( MUCD_Option::mod_log(), true, false );
$data['log-path']   = isset( $validated_data['log-path'] ) ? $validated_data['log-path'] : MUCD_Option::log_directory();

?>

<div class="wrap">
	<h2 id="duplicate-site"><?php _e( 'Duplicate Site', MUCD_DOMAIN ) ?></h2>

	<?php MUCD_Functions::print_notices(); ?>

	<form method="post" action="<?php echo network_admin_url( 'sites.php?page=' . MUCD_SLUG_NETWORK_ACTION . '&action=' . MUCD_SLUG_ACTION_DUPLICATE ); ?>">
		<?php wp_nonce_field( MUCD_DOMAIN ); ?>

		<table class="form-table">
		   <tr class="form-required">
				<th scope='row'><?php _e( 'Original site to copy', MUCD_DOMAIN ) ; ?></th>
				<td>
					<?php echo $select_site_list; ?>
				</td>
			</tr>

			<tr class="form-required">
				<th scope='row'><?php _e( 'New Site - Address', MUCD_DOMAIN ) ; ?></th>
				<td>
				<?php if ( is_subdomain_install() ) { ?>
				<input id="site_domain" name="site[domain]" type="text" class="large-text" title="<?php _e( 'New Site - Address', MUCD_DOMAIN ) ; ?>"  value="<?php echo $data['domain']; ?>"/><span class="no-break">.<?php echo preg_replace( '|^www\.|', '', $current_site->domain ); ?></span>
				<?php } else {
					echo $current_site->domain . $current_site->path ?><br /><input id="site_domain" name="site[domain]" class="large-text" type="text" title="<?php _e( 'New Site - Address', MUCD_DOMAIN ) ; ?>" value="<?php echo $data['domain']; ?>"/>
				<?php }
				echo '<p>' . __( 'Only lowercase letters (a-z) and numbers are allowed.', MUCD_DOMAIN ) . '</p>';
				?>
				</td>
			</tr>

			<tr class="form-required">
				<th scope='row'><?php _e( 'New Site - Title', MUCD_DOMAIN ); ?></th>
				<td><input id="site_title" name="site[title]" type="text" title="<?php _e( 'New Site - Title', MUCD_DOMAIN ) ; ?>" class="large-text" value="<?php echo $data['title']; ?>"/></td>
			</tr>

			<!-- Copy from  wp-admin/network/site-new.php : 141 to 147 -->
			<!-- Warning : name="blog[email] changed to site[email] -->
			<tr class="form-required">
				<th scope="row"><?php _e( 'New Site - Admin Email', MUCD_DOMAIN ); ?></th>
				<td><input id="site_email" name="site[email]" type="text" class="large-text wp-suggest-user" data-autocomplete-type="search" data-autocomplete-field="user_email" value="<?php echo $data['email']; ?>" title="<?php _e( 'New Site - Admin Email', MUCD_DOMAIN ); ?>"/></td>
			</tr>
			<tr class="form-field">
				<td colspan="2"><?php _e( 'A new user will be created if the above email address is not in the database.', MUCD_DOMAIN ); ?><br /><?php _e( 'The username and password will be mailed to this email address.', MUCD_DOMAIN ); ?></td>
			</tr>
			<!-- END Copy from  wp-admin/network/site-new.php : 141 to 147 -->
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
					<br /><br /><label><?php _e( 'Log directory', MUCD_DOMAIN ); ?> : <input id="log-path" name="site[log-path]" type="text"  class="large-text" value="<?php echo $data['log-path']; ?>" /></label>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input class='button button-primary' type='submit' value='<?php _e( 'Duplicate', MUCD_DOMAIN ) ; ?>' />
		</p>

	</form>
</div>
