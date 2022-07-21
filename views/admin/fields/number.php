<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = sprintf( 'value="%s"', ! empty( $saved_options[ $field_key ] ) ? esc_attr( $saved_options[ $field_key ] ) : $field['default'] );

$attr_name = true === $combine ? null : "name=options[$field_key]";

if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>
<div class="wpmm-item-field">
	<?php if ( ! empty( $field['label'] ) ) { ?>
	<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
	<?php } ?>
	<input <?php echo $value; ?> class="wpmm-form-control" type="number" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" <?php echo esc_attr($attr_name); ?> placeholder="<?php esc_attr_e( $field['placeholder'], 'wp-megamenu' ); ?>" min="<?php esc_attr_e( $field['min'], 'wp-megamenu' ); ?>" max="<?php esc_attr_e( $field['max'], 'wp-megamenu' ); ?>">

</div>
<?php } else { ?>
	<input <?php echo $value; ?> class="wpmm-form-control" type="number" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" <?php echo esc_attr($attr_name); ?> placeholder="<?php esc_attr_e( $field['placeholder'], 'wp-megamenu' ); ?>" min="<?php esc_attr_e( $field['min'], 'wp-megamenu' ); ?>" max="<?php esc_attr_e( $field['max'], 'wp-megamenu' ); ?>">

<?php } ?>
