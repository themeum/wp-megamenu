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
// $widgets_manager    = new wp_megamenu_widgets();
// $widgets            = $widgets_manager->get_all_registered_widget();
$if_new         = isset( $wpmm_layout['data_type'] ) && 'new' === $wpmm_layout['data_type'] ? true : false;
$_settings      = wpmm_settings();
$setting_fields = $_settings->wpmm_item_setting_fields();
// pr($get_menu_settings);
// Get Menu Name
// pr( wp_get_nav_menus() );
// pr( get_registered_nav_menus() );
// pr( get_nav_menu_locations() );

// pr( $wp_meta_boxes );

$data_serial = 'a:30:{i:2;a:0:{}i:3;a:3:{s:5:"title";s:0:"";s:6:"sortby";s:10:"menu_order";s:7:"exclude";s:0:"";}i:4;a:0:{}i:5;a:0:{}i:6;a:0:{}i:7;a:0:{}i:8;a:0:{}i:9;a:0:{}i:10;a:3:{s:5:"title";s:0:"";s:6:"sortby";s:10:"post_title";s:7:"exclude";s:0:"";}i:11;a:0:{}i:12;a:3:{s:5:"title";s:10:"Good pages";s:6:"sortby";s:10:"post_title";s:7:"exclude";s:3:"1,2";}i:13;a:3:{s:5:"title";s:4:"NEws";s:6:"sortby";s:10:"post_title";s:7:"exclude";s:6:"12, 32";}i:14;a:0:{}i:15;a:0:{}i:16;a:0:{}i:17;a:0:{}i:18;a:0:{}i:19;a:0:{}i:20;a:0:{}i:21;a:0:{}i:22;a:0:{}i:23;a:3:{s:5:"title";s:1:"1";s:6:"sortby";s:10:"post_title";s:7:"exclude";s:0:"";}i:24;a:3:{s:5:"title";s:1:"2";s:6:"sortby";s:10:"post_title";s:7:"exclude";s:0:"";}i:25;a:0:{}i:26;a:0:{}i:27;a:0:{}s:12:"_multiwidget";i:1;i:28;a:0:{}i:29;a:0:{}i:30;a:0:{}}';
// pr(maybe_unserialize($data_serial));
?>
	<div class="wpmm-modal" tabindex="-1" role="dialog">
	<div class="wpmm-modal-dialog" role="document">
		<div class="wpmm-modal-content">
			<div class="wpmm-modal-header wpmm-space-between">
				<h5 class="wpmm-modal-title">Mega Menu</h5>
				<button type="button" class="close-modal fa fa-close" data-dismiss="wpmm-modal" aria-label="Close">
				</button>
			</div>
			<div class="wpmm-modal-body">
				<div class="wpmm-option-content">
					<div class="wpmm-item-sidebar">
						<form id="wpmm_nav_item_settings" method="POST">
							<div class="wpmm-item-fields">
							<?php
							$saved_options = $wpmm_layout['options'];
							// pr($setting_saved);
							foreach ( $setting_fields as $setting_field ) {
								// echo $saved_settings[ $setting_field['key'] ];
								if ( isset( $setting_field['has_child'] ) ) {
									echo wpmm_settings()->wpmm_field_type( $setting_field, $saved_options );
								} elseif ( isset( $setting_field['type'] ) ) {
									echo wpmm_settings()->wpmm_field_type( $setting_field, $saved_options );
								}
							}
							?>


								<!-- <div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_enable">Mega Menu</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_enable" name="wpmm_enable">
										</div>
									</div>
								</div>

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

								<div class="wpmm-item-row wpmm-gap-1">
									<div class="wpmm-item-field">
										<label>Alignment</label>
										<select class="form-select" name="menu_alignment">
											<option value="full">Full</option>
											<option value="left">Left</option>
											<option value="center">Center</option>
											<option value="right">Right</option>
										</select>
									</div>
									<div class="wpmm-item-field">
										<label>Icon</label>
										<select class="form-select" name="menu_icon">
											<option value="1">Full</option>
											<option value="2">Left</option>
											<option value="2">Center</option>
											<option value="3">Right</option>
										</select>
									</div>
								</div>

								<div class="wpmm-item-field">
									<label>Custom Class</label>
									<input class="wpmm-form-control" type="text" role="switch" id="wpmm_item_custom_class" placeholder="add custom classes" name="wpmm_item_custom_class">
								</div>

								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_show_menu_title">Show Menu Title</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_show_menu_title" name="wpmm_show_menu_title">
										</div>
									</div>
								</div>


								<div class="wpmm-item-row wpmm-gap-1">
									<div class="wpmm-item-field">
										<label>Badge</label>
										<select class="form-select" name="menu_badge">
											<option value="1">Full</option>
											<option value="2">Left</option>
											<option value="2">Center</option>
											<option value="3">Right</option>
										</select>
									</div>
									<div class="wpmm-item-field">
										<label>Position</label>
										<select class="form-select" name="badge_position">
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
										<input type="color" value="gray" name="badge_background">
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label>Badge Text Color</label>
										<input type="color" value="red" name="badge_text_color">
									</div>
								</div>
								<div class="wpmm-item-field">
									<label>Upload Background Image</label>
									<input type="file" value="red" name="menu_background_image">
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_logged_in_only">Logged in only</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_logged_in_only" name="wpmm_logged_in_only">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_hide_text">Hide Text</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_text" name="wpmm_hide_text">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_hide_arrow">Hide Arrow</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_arrow" name="wpmm_hide_arrow">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_disable_link">Disable Link</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_disable_link" name="wpmm_disable_link">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_hide_on_mobile">Hide Item on Mobile</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_on_mobile" name="wpmm_hide_on_mobile">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label for="wpmm_hide_on_desktop">Hide Item on Desktop</label>
										<div class="wpmm-form-check wpmm-form-switch">
											<input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_on_desktop" name="wpmm_hide_on_desktop">
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label>Menu Item Alignment</label>
										<div>
											<select class="form-select" name="menu_item_alignment">
												<option value="1">Full</option>
												<option value="2">Left</option>
												<option value="2">Center</option>
												<option value="3">Right</option>
											</select>
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label>Dropdown alignment</label>
										<div>
											<select class="form-select" name="dropdown_alignment">
												<option value="1">Full</option>
												<option value="2">Left</option>
												<option value="2">Center</option>
												<option value="3">Right</option>
											</select>
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<div class="wpmm-item-row wpmm-space-between wpmm-align-center">
										<label>Icon Position</label>
										<div>
											<select class="form-select" name="icon_position">
												<option value="1">Full</option>
												<option value="2">Left</option>
												<option value="2">Center</option>
												<option value="3">Right</option>
											</select>
										</div>
									</div>
								</div>
								<div class="wpmm-item-field">
									<label>Badge Text</label>
									<div class="wpmm-input-group">
										<input type="text" class="wpmm-form-control" name="badge_text" placeholder="Badge Text">
										<div>
											<select class="form-select" name="badge_status">
												<option value="default">Default</option>
												<option value="primary" selected="selected">Primary</option>
												<option value="success">Success</option>
												<option value="info">Info</option>
												<option value="warning">Warning</option>
												<option value="danger">Danger</option>
											</select>
										</div>
									</div>
								</div>


								<label>Padding</label>
								<div class="wpmm-item-row wpmm-gap-1 input-sm">
									<div class="wpmm-item-field">
										<label>Top</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="padding_top">
									</div>
									<div class="wpmm-item-field">
										<label>Right</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="padding_right">
									</div>
									<div class="wpmm-item-field">
										<label>Bottom</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="padding_bottom">
									</div>
									<div class="wpmm-item-field">
										<label>Left</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="padding_left">
									</div>
								</div>


								<label>Margin</label>
								<div class="wpmm-item-row wpmm-gap-1 input-sm">
									<div class="wpmm-item-field">
										<label>Top</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="margin_top">
									</div>
									<div class="wpmm-item-field">
										<label>Right</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="margin_right">
									</div>
									<div class="wpmm-item-field">
										<label>Bottom</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="margin_bottom">
									</div>
									<div class="wpmm-item-field">
										<label>Left</label>
										<input type="text" class="form-control wpmm-text-center" placeholder="0px" name="margin_left">
									</div>
								</div> -->

							</form>
						</div>
					</div>
					<div class="wpmm-item-content loading">
						<div id="wpmm_layout_wrapper" class="wpmm-item-wrapper">
							<?php

							if ( ! empty( $wpmm_layout['layout'] ) && count( $wpmm_layout['layout'] ) ) {
								foreach ( $wpmm_layout['layout'] as $layout_key => $layout_value ) {
									// pr($layout_value);
									?>
									<div class="wpmm-layout-row" data-row-id="<?php echo esc_attr( $layout_key ); ?>">
										<div class="wpmm-row-toolbar wpmm-item-row wpmm-space-between wpmm-align">
											<div class="wpmm-row-toolbar-left wpmm-row-sorting-icon">
												<i class="fa fa-sort wpmm-mr-2"></i>
												<span>Row</span>
											</div>
											<div class="wpmm-row-toolbar-right">
												<span class="wpmm-row-delete-icon" onclick="wpmm_delete_any_row(this)">
													<i class="fa fa-trash-o"></i>
												</span>
											</div>
										</div>
										<div class="wpmm-columns-container wpmm-item-row wpmm-gap-1 wpmm-flex-wrap">
											<?php
											if ( ! empty( $layout_value['row'] ) && count( $layout_value['row'] ) ) {
												foreach ( $layout_value['row'] as $col_key => $layout_col ) {
													$layout_columns = ! empty( $layout_col['col'] ) ? $layout_col['col'] : 3;
													if ( isset( $layout_col['width'] ) ) {
														$layout_width = $layout_col['width'];
													} else {
														$layout_width = ( $layout_columns * 100 ) / 12;
													}

													?>
											<div style="--col-width: calc(<?php echo esc_attr( $layout_width ); ?>% - 1em)" class="wpmm-item-col wpmm-item-<?php echo esc_attr( $layout_columns ); ?>" data-col="<?php echo esc_attr( $layout_columns ); ?>" data-width="<?php echo esc_attr( $layout_width ); ?>" data-rowid="<?php echo esc_attr( $layout_key ); ?>" data-col-id="<?php echo esc_attr( $col_key ); ?>">
												<div class="wpmm-column-contents-wrapper">
													<div class="wpmm-column-toolbar wpmm-column-drag-handler">
														<span class="wpmm-col-sorting-icon">
															<i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
														</span>
													</div>
													<div class="wpmm-column-contents">
														<?php
														foreach ( $layout_col['items'] as $key => $value ) {
															if ( true === $if_new ) {
																$value['base_id'] = wp_megamenu_widgets()->wpmm_get_id_base_by_widget_id( $value['widget_id'] );
															}
															if ( 'widget' === $value['item_type'] ) {
																wp_megamenu_widgets()->widget_item( $value['widget_id'], $get_menu_settings, $key, $value['base_id'] );
															} else {
																wp_megamenu_widgets()->menu_items( $value, $key );
															}
														}
														?>
													</div>
													<div class="wpmm-add-item-wrapper">
														<button class="wpmm-add-new-item"
														onclick="wpmm_add_new_item(this)" data-col-index="<?php echo esc_attr( $col_key ); ?>" data-row-index="<?php echo esc_attr( $layout_key ); ?>" title="Add Module">
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


						<div class="wpmm-add-row">
							<button onclick="wpmm_toggle_layout_builder(this)" class="select_layout button-primary"><i class="fa fa-plus"></i> Add New Row</button>
						</div>
						<div class="wpmm-add-slots" id="layout-modal">
							<div class="wpmm-columns-layout">
								<div class="wpmm-item-grid wpmm-gap-1 wpmm-text-center">
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="100" data-design="layout1">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="50.78" height="16.927" fill-opacity=".3" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1:1</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="50,50" data-design="layout55">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="23.79" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="23.79" height="16.221" fill-opacity=".7" rx="2"></rect><rect width="23.79" height="16.221" x="25.681" fill-opacity=".3" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/2 + 1/2</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="33.33,33.33,33.33" data-design="layout444">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="15.139" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="17.302" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="17.302" fill-opacity=".7" rx="2"></rect><rect width="15.139" height="16.221" x="34.605" fill-opacity=".3" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/3 + 1/3 + 1/3</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="25,25,25,25" data-design="layout3333">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="10.814" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="10.814" height="16.221" x="12.974" fill-opacity=".3" rx="2"></rect><rect width="10.814" height="16.221" x="12.974" fill-opacity=".7" rx="2"></rect><rect width="10.814" height="16.221" x="25.95" fill-opacity=".3" rx="2"></rect><rect width="11.354" height="16.221" x="38.929" fill-opacity=".3" rx="2"></rect><rect width="11.354" height="16.221" x="38.929" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/4 + 1/4 + 1/4 + 1/4</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="33.33,66.66" data-design="layout48">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="15.139" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="33" height="16" x="17" fill-opacity=".3" rx="2"></rect><rect width="33" height="16" x="17" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/3 + 2/3</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="25,75" data-design="layout39">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="10.814" height="16.221" fill-opacity=".7" rx="2"></rect><rect width="37" height="16" x="13" fill-opacity=".3" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/4 + 3/4</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="25,50,25" data-design="layout363">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="10.543" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="11.084" height="16.221" x="38.659" fill-opacity=".3" rx="2"></rect><rect width="23.79" height="16.221" x="12.704" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/4 + 2/4 + 1/4</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="16.66,50,33.33" data-design="layout264">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".7" rx="2"></rect><rect width="23.79" height="16.221" x="9" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="35" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/6 + 3/6 + 2/6</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="16.66,83.33" data-design="layout210">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".3" rx="2"></rect><rect width="41" height="16" x="9" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/6 + 5/6</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="41.64,58.36" data-design="layout57">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="28.927" height="16.221" x="20.653" fill-opacity=".7" rx="2"></rect><rect width="18.654" height="16.221" fill-opacity=".3" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">5/12 + 7/12</span>
											</div>
										</div>
										<div class="wpmm-grid-item">
											<div class="wpmm-column-layout" data-layout="16.64,25,58.36" data-design="layout237">
												<div class="wpmm-column-layout-preview">
													<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".7" rx="2"></rect><rect width="10" height="16.221" x="8.7" fill-opacity=".3" rx="2"></rect><rect width="28.927" height="16.221" x="20.653" fill-opacity=".7" rx="2"></rect></svg>					</div>
												<span class="wpmm-column-layout-name">1/6 + 1/4 + 7/12</span>
											</div>
										</div>

									<div class="wpmm-grid-item">
										<div class="wpmm-column-layout wpmm-custom" data-layout="custom">
											<div class="wpmm-column-layout-preview">Custom</div>
											<span class="wpmm-column-layout-name hu-sr-only">Custom</span>
										</div>
									</div>
								</div>
								<div class="wpmm-custom-layout">
									<label>Custom Layout</label>
									<div class="wpmm-item-row wpmm-space-between">
										<input type="text" class="wpmm-custom-layout-field wpmm-item-grow" value="60+20+20">
										<button class="button-primary wpmm-custom-layout-apply">Apply</button>
									</div>
								</div>
							</div>
								</div>
					</div>
				</div>
			</div>
			<div class="wpmm-modal-footer wpmm-justify-end">
				<button type="button" class="button-secondary close-modal" data-dismiss="wpmm-modal">Close</button>
				<div class="wpmm-ml-3">
					<button type="submit" form="wpmm_nav_item_settings" onclick="wpmmSaveNavItemFunction(this)" class="button-primary wpmm-save-nav-item wpmm-btn-spinner- ">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	</div>
