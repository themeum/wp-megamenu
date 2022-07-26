<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = ! empty( $saved_options[ $field_key ] ) ? $saved_options[ $field_key ] : $field['default'];
?>
<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
		<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm-form-check wpmm-form-switch">
			<input type="hidden" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]">
			<input class="wpmm-form-check-input" <?php checked( 'true', $value, true ); ?> type="checkbox" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>">
		</div>
	</div>
</div>
