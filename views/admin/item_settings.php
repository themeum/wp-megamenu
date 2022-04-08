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
			<h5 class="wpmm-modal-title">Modal title</h5>
			<button type="button" class="close" data-dismiss="wpmm-modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="wpmm-modal-body">
			<p>Modal body text goes here.</p>
		</div>
		<div class="wpmm-modal-footer">
			<button type="button" class="btn btn-primary">Save changes</button>
			<button type="button" class="btn btn-secondary" data-dismiss="wpmm-modal">Close</button>
		</div>
		</div>
	</div>
	</div>
