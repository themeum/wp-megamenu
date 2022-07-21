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

$if_new = isset( $wpmm_layout['data_type'] ) && 'new' === $wpmm_layout['data_type'] ? true : false;

?>
	<div class="wpmm-modal" tabindex="-1" role="dialog">
		<div class="wpmm-modal-dialog" role="document">
			<div class="wpmm-modal-content">
				<div class="wpmm-modal-header wpmm-space-between">
					<h5 class="wpmm-modal-title"><?php echo _e( 'Mega Menu', 'wp-megamenu' ); ?></h5>
					<button type="button" class="close-modal fa fa-close" data-dismiss="wpmm-modal" aria-label="Close"></button>
				</div>
				<div class="wpmm-modal-body">
					<div class="wpmm-option-content">
						<div class="wpmm-item-sidebar"><!-- onclick="wpmm_image_uploader(this)"  -->
							<form id="wpmm_nav_item_settings" method="POST" onsubmit="wpmmSaveNavItemFunction(this, event)">
								<div class="wpmm-item-fields">
								<?php
								foreach ( wpmm_settings()->wpmm_item_setting_fields() as $setting_field ) {
									wpmm_settings()->wpmm_field_type( $setting_field, $wpmm_layout['options'] );
								}
								?>
								</div>
							</form>
						</div>

						<div class="wpmm-item-content loading">
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
													<div class="colum_maker area_toggler">
														<button class="fa fa-cog toggler_button"></button>
														<div class="dropdown_buttons">
															<button onclick="add_new_column(this)">
																<i class="fa fa-plus"></i>
																<span><?php _e( 'Add Column', 'wp-megamenu' ); ?></span>
															</button>
														</div>
													</div>
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
														<div style="--col-width: calc(<?php echo esc_attr( $layout_width ); ?>% - 1em)" class="wpmm-item-col" data-col="<?php echo esc_attr( $layout_columns ); ?>" data-width="<?php echo esc_attr( $layout_width ); ?>" data-rowid="<?php echo esc_attr( $layout_key ); ?>" data-col-id="<?php echo esc_attr( $col_key ); ?>">
															<div class="wpmm-column-contents-wrapper">
																<div class="wpmm-column-toolbar wpmm-column-drag-handler">
																	<div class="wpmm-col-sorting-icon">
																		<i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
																	</div>
																	<div class="colum_resizer area_toggler">
																		<button class="fa fa-cog toggler_button"></button>
																		<div class="dropdown_buttons">
																			<div class="btn-col">
																				<div class="col_item">
																					<input type="number" min="20" max="100" value="<?php echo esc_attr( $layout_width ); ?>">
																				</div>
																				<div class="col_item">
																					<button class="fa fa-plus increment"></button>
																				</div>
																				<div class="col_item">
																					<button class="fa fa-minus decrement"></button>
																				</div>
																				<div class="col_item">
																					<button class="fa fa-trash remove"></button>
																				</div>
																			</div>

																		</div>
																	</div>
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
															<div draggable="true" class="resizer"></div>
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

							<div class="wpmm-add-row position-relative">
								<button onclick="wpmm_toggle_layout_builder(event, this)" class="select_layout button-primary"><i class="fa fa-plus"></i> Add New Row</button>
								<div class="wpmm-add-slots" id="layout-modal">
									<?php echo apply_filters( 'wpmm_kses', include_view( 'views/admin/columns_layouts.php' ) ); // PHPCS:ignore ?>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="wpmm-modal-footer wpmm-justify-end">
					<button type="button" class="button-secondary close-modal" data-dismiss="wpmm-modal"><?php _e( 'Close', 'wp-megamenu' ); ?></button>
					<div class="wpmm-ml-3">
						<button type="submit" form="wpmm_nav_item_settings" class="button-primary wpmm-save-nav-item wpmm-btn-spinner- "><?php _e( 'Save changes', 'wp-megamenu' ); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
