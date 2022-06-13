<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = sprintf( 'value="%s"', ! empty( $saved_options[ $field_key ] ) ? esc_attr( $saved_options[ $field_key ] ) : $field['default'] );

if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>

<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
	<?php if ( ! empty( $field['label'] ) ) { ?>
		<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
	<?php } ?>
		<input <?php echo $value; ?> class="wpmm-form-check-input" type="color" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]">
	</div>
</div>
<?php } else { ?>
	<input <?php echo $value; ?> class="wpmm-form-control" type="color" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]" placeholder="<?php esc_attr_e( $field['placeholder'], 'wp-megamenu' ); ?>">
<?php } ?>
