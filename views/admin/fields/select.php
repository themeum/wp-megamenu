<?php

$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';

if ( isset( $field['label'] ) && false !== $field['label'] ) { ?>
	<div class="wpmm-item-field">
		<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
			<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>">
				<?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?>
			</label>
			<div>
				<select class="form-select" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]">
					<?php foreach ( $field['options'] as $key => $option ) { ?>
						<option value="<?php esc_attr_e( $key, 'wp-megamenu' ); ?>">
							<?php esc_attr_e( $option, 'wp-megamenu' ); ?>
						</option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
<?php } else { ?>
	<select class="form-select" id="field_id_<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>" name="options[<?php esc_attr_e( $field_key, 'wp-megamenu' ); ?>]">
		<?php foreach ( $field['options'] as $key => $option ) { ?>
			<option value="<?php esc_attr_e( $key, 'wp-megamenu' ); ?>">
				<?php esc_attr_e( $option, 'wp-megamenu' ); ?>
			</option>
		<?php } ?>
	</select>
<?php } ?>
