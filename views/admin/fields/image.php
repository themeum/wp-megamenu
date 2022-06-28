<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = sprintf( 'value="%s"', ! empty( $saved_options[ $field_key ] ) ? esc_attr( $saved_options[ $field_key ] ) : $field['default'] );

if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>
<div class="wpmm-item-field">
	<div class="wpmm_image_uploader">
		<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm_image_preview">
			<button class="fa fa-times"></button>
		</div>
		<input class="upload_image" type="hidden" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]"  />
		<button class="upload_image_button button" type="button">Upload Image</button>
	</div>
</div>
<?php } else { ?>
	<div class="wpmm_image_uploader">
		<div class="wpmm_image_preview">
			<button class="fa fa-times"></button>
		</div>
		<input class="upload_image" type="hidden" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]"  />
		<button class="upload_image_button button" type="button">Upload Image</button>
	</div>
<?php } ?>
