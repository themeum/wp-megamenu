<?php if ( $field['inline'] ) { ?>

<div class="wpmm-item-field">
	<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
		<label>Menu Width</label>
		<div class="wpmm-row-column">
			<div class="wpmm-input-group">
				<input type="number" max="9999" class="wpmm-form-control" name="wpmm_width">
				<select class="form-select" name="width_type">
					<option value="px">px</option>
					<option value="em">em</option>
					<option value="rem">rem</option>
					<option value="%">%</option>
				</select>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="wpmm-item-field">
	<label><?php esc_attr_e( $field['label'], 'wp-megamenu' ); ?></label>
	<div class="wpmm-input-group">
		<?php foreach ( $field['fields'] as $key => $field ) { ?>
			<?php
			if ( 0 == $key ) {
				include wpmm()->path . "views/admin/fields/{$field['type']}.php";
			} else {
				?>
			<div style="width: <?php echo esc_attr( $field['width'] ); ?>">
				<?php include wpmm()->path . "views/admin/fields/{$field['type']}.php"; ?>
			</div>
				<?php
			}
		}
		?>
	</div>
</div>
<?php } ?>
