<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
?>
<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
		<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm-form-check wpmm-form-switch">
			<input class="wpmm-form-check-input" type="checkbox" role="switch" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]">
		</div>
	</div>
</div>
