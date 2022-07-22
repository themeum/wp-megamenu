<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = isset( $saved_options[ $field_key ] ) ? $saved_options[ $field_key ] : $field['default'];

$attr_name = true === $combine ? null : "name=options[$field_key]";
?>
<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
	<label><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm-row-column">
			<div class="wpmm-input-group combine_group">
				<input type="hidden" <?php echo esc_attr( $attr_name ); ?> value="<?php echo esc_attr( $value ); ?>">
				<?php
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
			</div>
		</div>
	</div>
</div>
