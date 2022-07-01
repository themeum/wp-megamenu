<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
$value       = ! empty( $saved_options[ $field_key ] ) ? (int) esc_attr( $saved_options[ $field_key ] ) : '';
if ( 'integer' === gettype( $value ) ) {
	$url = wp_get_attachment_image_src( $value, 'full' )[0];
}
if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>
<div class="wpmm-item-field">
	<div class="wpmm_image_uploader">
		<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
		<div class="wpmm_image_preview">
			<?php if ( $url ) { ?>
				<img src="<?php echo esc_url( $url ); ?>">
			<?php } ?>
			<button class="fa fa-trash delete_image"></button>
		</div>
		<input class="upload_image" type="hidden" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]"  />
		<button class="upload_image_button button" type="button">Upload Image</button>
	</div>
</div>
<?php } else { ?>
	<div class="wpmm_image_uploader">
		<div class="wpmm_image_preview">
			<?php if ( $url ) { ?>
				<img src="<?php echo esc_url( $url ); ?>">
			<?php } ?>
			<button class="fa fa-trash delete_image"></button>
		</div>
		<input class="upload_image" type="hidden" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]"  />
		<button class="upload_image_button button" type="button">Upload Image</button>
	</div>
<?php } ?>
