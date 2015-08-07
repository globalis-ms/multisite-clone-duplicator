<div class="wrap">
	<h2 id="duplicate-site"><?php echo __( 'Clone over primary site', MUCD_DOMAIN ) ?></h2>

	<?php

	if ( MUCD_Duplicate::log_error() ) {
		MUCD_Admin::log_error_message();
	}

	if ( isset( $form_message ) ) {
		MUCD_Admin::result_message( $form_message );
	}

	?>

	<form method="post" action="<?php echo network_admin_url( 'sites.php?page=' . MUCD_SLUG_NETWORK_ACTION_CLONE_OVER . '&action=' . MUCD_SLUG_ACTION_DUPLICATE_OVER_PRIMARY ); ?>">
		<?php wp_nonce_field( MUCD_DOMAIN ); ?>

		<table class="form-table">
		   <tr class="form-required">
				<th scope='row'><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FIELD_SOURCE ; ?></th>
				<td>
					<?php echo $select_site_list; ?>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES; ?></th>
				<td>
					<label><input <?php checked( get_site_option( 'mucd_copy_files', 'yes' ), 'yes' ); ?> name="site[copy_files]" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_FILES_TEXT_1; ?></label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS; ?></th>
				<td>
					<label><input id="site_keep_users" <?php checked( get_site_option( 'mucd_keep_users', 'yes' ), 'yes' ); ?> name="site[keep_users]" type="checkbox" value="yes" /><?php echo MUCD_NETWORK_PAGE_DUPLICATE_USERS_TEXT_1; ?></label>
				</td>
			</tr>

		</table>


		<p class="submit">
			<input class='button button-primary' type='submit' value='<?php echo MUCD_NETWORK_PAGE_CLONE_OVER_BUTTON_COPY ; ?>' />
		</p>

	</form>
</div>
