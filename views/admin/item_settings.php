<?php
$menu_item_id    = (int) sanitize_text_field( $_POST['menu_item_id'] );
$menu_item_depth = (int) sanitize_text_field( $_POST['menu_item_depth'] );

$get_menu_settings = get_post_meta( $menu_item_id, 'wpmm_layout', true );
$wpmm_layout       = get_post_meta( $menu_item_id, 'wpmm_layout', true );

$menu_type = '';
if ( ! empty( $get_menu_settings['menu_type'] ) ) {
	$menu_type = $get_menu_settings['menu_type'];
}

$menu_strees_row = '';
if ( ! empty( $get_menu_settings['menu_strees_row'] ) ) {
	$menu_strees_row = $get_menu_settings['menu_strees_row'];
}
$item_width = '';
if ( ! empty( $get_menu_settings['options']['width'] ) ) {
	$item_width = $get_menu_settings['options']['width'];
}
$item_strees_row_width = '';
if ( ! empty( $get_menu_settings['options']['strees_row_width'] ) ) {
	$item_strees_row_width = $get_menu_settings['options']['strees_row_width'];
}
// Widgets Manager
$widgets_manager = new wp_megamenu_widgets();
$widgets         = $widgets_manager->get_all_registered_widget();
// Get Menu Name
?>

	<div class="wpmm-modal" tabindex="-1" role="dialog">
	<div class="wpmm-modal-dialog" role="document">
		<div class="wpmm-modal-content">
		<div class="wpmm-modal-header">
			<h5 class="wpmm-modal-title">Mega Menu</h5>
			<button type="button" class="close-modal" data-dismiss="wpmm-modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="wpmm-modal-body">
			<div class="wpmm-item-sidebar">
				<div class="wpmm-item-fields">
					<div class="wpmm-item-field">
						<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
							<label for="wpmm_enable">Mega Menu</label>
							<div class="wpmm-form-check wpmm-form-switch">
								<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_enable">
							</div>
						</div>
					</div>
					<div class="wpmm-item-field">
						<div class="wpmm-item-row wpmm-space-between">
							<label>Menu Width</label>
							<div class="input-group mb-3">
								<input type="text" class="form-control" aria-label="">
								<select class="form-select" id="inputGroupSelect01">
									<option value="1">px</option>
									<option value="2">em</option>
									<option value="2">rem</option>
									<option value="3">%</option>
								</select>
							</div>
						</div>
					</div>

					<div class="wpmm-item-row">
						<div class="wpmm-item-field">
							<label>Alignment</label>
							<select class="form-select" id="inputGroupSelect01">
								<option value="1">Full</option>
								<option value="2">Left</option>
								<option value="2">Center</option>
								<option value="3">Right</option>
							</select>
						</div>
						<div class="wpmm-item-field">
							<label>Icon</label>
							<select class="form-select" id="inputGroupSelect01">
								<option value="1">Full</option>
								<option value="2">Left</option>
								<option value="2">Center</option>
								<option value="3">Right</option>
							</select>
						</div>
					</div>

					<div class="wpmm-item-field">
						<label>Custom Class</label>
						<input class="form-control" type="text" role="switch" id="wpmm_item_custom_class" placeholder="add custom classes">
					</div>

					<div class="wpmm-item-field">
						<div class="wpmm-item-row wpmm-space-between">
							<label for="wpmm_show_menu_title">Show Menu Title</label>
							<div class="wpmm-form-check wpmm-form-switch">
								<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_show_menu_title">
							</div>
						</div>
					</div>


					<div class="wpmm-item-row">
						<div class="wpmm-item-field">
							<label>Badge</label>
							<select class="form-select" id="inputGroupSelect01">
								<option value="1">Full</option>
								<option value="2">Left</option>
								<option value="2">Center</option>
								<option value="3">Right</option>
							</select>
						</div>
						<div class="wpmm-item-field">
							<label>Left</label>
							<select class="form-select" id="inputGroupSelect01">
								<option value="1">Full</option>
								<option value="2">Left</option>
								<option value="2">Center</option>
								<option value="3">Right</option>
							</select>
						</div>
					</div>


					<div class="wpmm-item-field">
						<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
							<label>Badge Background</label>
							<input type="color" value="gray">
						</div>
					</div>
					<div class="wpmm-item-field">
					<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
						<label>Badge Text Color</label>
						<input type="color" value="red">
					</div>
					</div>

				</div>
			</div>
			<div class="wpmm-item-content">
			One of three columns
			</div>
		</div>
		<div class="wpmm-modal-footer">
			<button type="button" class="button-primary">Save changes</button>
			<button type="button" class="button-danger" data-dismiss="wpmm-modal">Close</button>
		</div>
		</div>
	</div>
	</div>
