<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value  = isset( $saved_options[ $field_key ] ) ? $saved_options[ $field_key ] : $field['default'];

$attr_name = true === $combine ? null : "name=options[$field_key]";

if ( isset( $field['layout'] ) && 'full' === $field['layout'] ) {
	?>
<div class="wpmm-item-field">
	<label>Top</label>
	<select class="form-select" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" <?php echo esc_attr( $attr_name ); ?>>
		<?php foreach ( $field['options'] as $key => $option ) { ?>
			<option and <?php selected( $key, $value, true ); ?> value="<?php esc_attr_e( $key, 'wp-megamenu' ); ?>">
				<?php esc_attr_e( $option, 'wp-megamenu' ); ?>
			</option>
		<?php } ?>
	</select>
</div>
	<?php
} elseif ( isset( $field['label'] ) && false !== $field['label'] && ! isset( $field['layout'] ) ) {
	?>

	<div class="wpmm-item-field">
		<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
			<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>">
			<?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?>
			</label>
			<div>
				<select class="form-select" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" <?php echo esc_attr( $attr_name ); ?>>
				<?php foreach ( $field['options'] as $key => $option ) { ?>
						<option <?php selected( $key, $value, true ); ?> value="<?php esc_attr_e( $key, 'wp-megamenu' ); ?>">
							<?php esc_attr_e( $option, 'wp-megamenu' ); ?>
						</option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
<?php } else { ?>
	<select class="form-select" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" <?php echo esc_attr( $attr_name ); ?>>
		<?php foreach ( $field['options'] as $key => $option ) { ?>
			<option false <?php selected( $key, $value, true ); ?> value="<?php esc_attr_e( $key, 'wp-megamenu' ); ?>">
				<?php esc_attr_e( $option, 'wp-megamenu' ); ?>
			</option>
		<?php } ?>
	</select>
<?php } ?>
