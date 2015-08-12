<div class="wrap">
	<h2 id="duplicate-site"><?php _e( 'Clone over primary site', MUCD_DOMAIN ) ?></h2>

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

			<tr>
				<th scope="row"><?php _e( 'Files', MUCD_DOMAIN ); ?></th>
				<td>
					<label><input <?php checked( get_site_option( 'mucd_copy_files', 'yes' ), 'yes' ); ?> name="site[copy_files]" type="checkbox" value="yes" /><?php _e( 'Duplicate files from duplicated site upload directory', MUCD_DOMAIN ); ?></label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php _e( 'Users and roles', MUCD_DOMAIN ); ?></th>
				<td>
					<label><input id="site_keep_users" <?php checked( get_site_option( 'mucd_keep_users', 'yes' ), 'yes' ); ?> name="site[keep_users]" type="checkbox" value="yes" /><?php _e( 'Keep users and roles from duplicated site', MUCD_DOMAIN ); ?></label>
				</td>
			</tr>

		</table>


		<p class="submit">
			<input class='button button-primary' type='submit' value='<?php _e( 'Clone over', MUCD_DOMAIN ); ?>' />
		</p>

	</form>
</div>
