<?php
$field_label = isset( $field['label'] ) ? $field['label'] : '';
$field_key   = isset( $field['key'] ) ? $field['key'] : '';
?>
<label for="field_id_<?php esc_attr_e( $field_key, 'megamenu' ); ?>"><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
<div class="wpmm-item-row wpmm-gap-1 input-sm">
	<?php
	foreach ( $field['fields'] as $key => $field ) {
		include wpmm()->path . "views/admin/fields/{$field['type']}.php";
	}
	?>
</div>
