<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';

if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>
<div class="wpmm-item-field">
	<?php if ( ! empty( $field['label'] ) ) { ?>
	<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
	<?php } ?>
	<input class="wpmm-form-control" type="number" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]" placeholder="<?php esc_attr_e( $field['placeholder'], 'wp-megamenu' ); ?>" min="<?php esc_attr_e( $field['min'], 'wp-megamenu' ); ?>" max="<?php esc_attr_e( $field['max'], 'wp-megamenu' ); ?>">

</div>
<?php } else { ?>
	<input class="wpmm-form-control" type="number" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]" placeholder="<?php esc_attr_e( $field['placeholder'], 'wp-megamenu' ); ?>" min="<?php esc_attr_e( $field['min'], 'wp-megamenu' ); ?>" max="<?php esc_attr_e( $field['max'], 'wp-megamenu' ); ?>">

<?php } ?>
