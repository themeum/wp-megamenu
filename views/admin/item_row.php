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

<div id="wpmm_layout_wrapper" class="wpmm-item-wrapper">
	<?php
	if ( ! empty( $wpmm_layout['layout'] ) && count( $wpmm_layout['layout'] ) ) {
		foreach ( $wpmm_layout['layout'] as $layout_key => $layout_value ) {
			?>
			<div class="wpmm-layout-row" data-row-id="<?php echo esc_attr( $layout_key ); ?>">
				<div class="wpmm-row-toolbar wpmm-item-row wpmm-space-between wpmm-align">
					<div class="wpmm-row-toolbar-left wpmm-row-sorting-icon">
						<i class="fa fa-sort wpmm-mr-2"></i>
						<span>Row</span>
					</div>
					<div class="wpmm-row-toolbar-right">
						<span class="wpmm-row-delete-icon">
							<i class="fa fa-trash-o"></i>
						</span>
					</div>
				</div>
				<div class="wpmm-columns-container wpmm-item-row wpmm-gap-1">
					<?php
					if ( ! empty( $layout_value['row'] ) && count( $layout_value['row'] ) ) {
						foreach ( $layout_value['row'] as $col_key => $layout_col ) {
							$layout_columns = ! empty( $layout_col['col'] ) ? $layout_col['col'] : 6;
							?>
					<div class="wpmm-item-col wpmm-item-col-<?php echo esc_attr( $layout_columns ); ?>" data-rowid="2" data-col-id="<?php echo esc_attr( $col_key ); ?>">
						<div class="wpmm-column-contents-wrapper">
							<div class="wpmm-column-toolbar wpmm-column-drag-handler">
								<span class="wpmm-col-sorting-icon">
									<i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
								</span>
							</div>
							<div class="wpmm-column-contents">
								<?php
								foreach ( $layout_col['items'] as $key => $value ) {
									if ( 'widget' === $value['item_type'] ) {
										wp_megamenu_widgets()->widget_item( $value['widget_id'], $get_menu_settings, $key );
									} else {
										wp_megamenu_widgets()->menu_items( $value, $key );
									}
								}
								?>
							</div>
							<div class="wpmm-add-item-wrapper">
								<button class="wpmm-add-new-item" title="Add Module">
									<span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
								</button>
							</div>
						</div>
					</div>
							<?php
						}
					}
					?>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
