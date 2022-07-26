<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
	<label><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm-row-column">
			<div class="wpmm-input-group">
				<?php
				$combine = true;
				foreach ( $field['fields'] as $key => $field ) {
					?>
					<?php
					if ( 0 == $key ) {
						include wpmm()->path . "views/admin/fields/{$field['type']}.php";
					} else {
						?>
					<div style="width: <?php echo isset( $field['width'] ) ? esc_attr( $field['width'] ) : ''; ?>">
						<?php include wpmm()->path . "views/admin/fields/{$field['type']}.php"; ?>
					</div>
						<?php
					}
				}
				?>
				<input type="hidden" name="<?php echo $field['key'] ?>" value="">
			</div>
		</div>
	</div>
</div>
