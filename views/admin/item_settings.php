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
            <div class="wpmm-modal-header wpmm-space-between">
                <h5 class="wpmm-modal-title">Mega Menu</h5>
                <button type="button" class="close-modal fa fa-close" data-dismiss="wpmm-modal" aria-label="Close">
                </button>
            </div>
            <div class="wpmm-modal-body">
                <div class="wpmm-option-content">
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
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label>Menu Width</label>
                                    <div class="wpmm-row-column">
                                        <div class="input-group">
                                            <input type="number" max="9999" class="form-control" aria-label="">
                                            <select class="form-select">
                                                <option value="1">px</option>
                                                <option value="2">em</option>
                                                <option value="2">rem</option>
                                                <option value="3">%</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wpmm-item-row wpmm-gap-1">
                                <div class="wpmm-item-field">
                                    <label>Alignment</label>
                                    <select class="form-select">
                                        <option value="1">Full</option>
                                        <option value="2">Left</option>
                                        <option value="2">Center</option>
                                        <option value="3">Right</option>
                                    </select>
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Icon</label>
                                    <select class="form-select">
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
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_show_menu_title">Show Menu Title</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_show_menu_title">
                                    </div>
                                </div>
                            </div>


                            <div class="wpmm-item-row wpmm-gap-1">
                                <div class="wpmm-item-field">
                                    <label>Badge</label>
                                    <select class="form-select">
                                        <option value="1">Full</option>
                                        <option value="2">Left</option>
                                        <option value="2">Center</option>
                                        <option value="3">Right</option>
                                    </select>
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Left</label>
                                    <select class="form-select">
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
                            <div class="wpmm-item-field">
                                <label>Upload Background Image</label>
                                <input type="file" value="red">
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_logged_in_only">Logged in only</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_logged_in_only">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_hide_text">Hide Text</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_text">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_hide_arrow">Hide Arrow</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_arrow">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_disable_link">Disable Link</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_disable_link">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_hide_on_mobile">Hide Item on Mobile</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_on_mobile">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label for="wpmm_hide_on_desktop">Hide Item on Desktop</label>
                                    <div class="wpmm-form-check wpmm-form-switch">
                                        <input class="wpmm-form-check-input" type="checkbox" role="switch" id="wpmm_hide_on_desktop">
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-item-field">
                                <div class="wpmm-item-row wpmm-space-between wpmm-align-center">
                                    <label>Menu Item Alignment</label>
                                    <div>
                                        <select class="form-select">
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
                                        <select class="form-select">
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
                                        <select class="form-select">
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
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="" placeholder="Badge Text">
                                    <div>
                                        <select class="form-select">
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
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Right</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Bottom</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Left</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                            </div>


                            <label>Margin</label>
                            <div class="wpmm-item-row wpmm-gap-1 input-sm">
                                <div class="wpmm-item-field">
                                    <label>Top</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Right</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Bottom</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                                <div class="wpmm-item-field">
                                    <label>Left</label>
                                    <input type="text" class="form-control wpmm-text-center" placeholder="0px">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="wpmm-item-content">

                        <div id="wpmm_layout_wrapper" class="wpmm-item-wrapper">
                            <div class="wpmm-layout-row">
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
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 1</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 2</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 3</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 4</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 5</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 6</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 7</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="wpmm-layout-row">
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
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 1</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 2</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 3</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 4</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpmm-item-col wpmm-item-col-4" data-rowid="2" data-columnid="1">
                                        <div class="wpmm-column-contents-wrapper">
                                            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                                                <span class="wpmm-col-sorting-icon">
                                                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                                                </span>
                                            </div>
                                            <div class="wpmm-column-contents">
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 5</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 6</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                                <div class="wpmm-cell" data-rowid="2" data-columnid="1" data-cellid="1">
                                                    <span>Breadcrumbs 7</span>
                                                    <button class="wpmm-btn wpmm-btn-link wpmm-cell-remove">
                                                        <span class="fa fa-caret-down" aria-hidden="true"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="wpmm-add-item-wrapper">
                                                <button class="wpmm-add-new-item" title="Add Module">
                                                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="wpmm-add-row">
                            <button class="button-primary"><i class="fa fa-plus"></i> Add New Row</button>
                        </div>
                        <div class="wpmm-add-slots" style="display: block;">
                            <div class="wpmm-columns-layout">
                                <div class="wpmm-item-grid wpmm-gap-1 wpmm-text-center">
                                        <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="12" class="layout12" data-layout="12" data-design="layout12">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="50.78" height="16.927" fill-opacity=".3" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">12</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="6+6">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="23.79" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="23.79" height="16.221" fill-opacity=".7" rx="2"></rect><rect width="23.79" height="16.221" x="25.681" fill-opacity=".3" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">6+6</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="4+4+4">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="15.139" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="17.302" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="17.302" fill-opacity=".7" rx="2"></rect><rect width="15.139" height="16.221" x="34.605" fill-opacity=".3" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">4+4+4</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="3+3+3+3">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="10.814" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="10.814" height="16.221" x="12.974" fill-opacity=".3" rx="2"></rect><rect width="10.814" height="16.221" x="12.974" fill-opacity=".7" rx="2"></rect><rect width="10.814" height="16.221" x="25.95" fill-opacity=".3" rx="2"></rect><rect width="11.354" height="16.221" x="38.929" fill-opacity=".3" rx="2"></rect><rect width="11.354" height="16.221" x="38.929" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">3+3+3+3</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="4+8">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="15.139" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="33" height="16" x="17" fill-opacity=".3" rx="2"></rect><rect width="33" height="16" x="17" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">4+8</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="3+9">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="10.814" height="16.221" fill-opacity=".7" rx="2"></rect><rect width="37" height="16" x="13" fill-opacity=".3" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">3+9</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="3+6+3">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="10.543" height="16.221" fill-opacity=".3" rx="2"></rect><rect width="11.084" height="16.221" x="38.659" fill-opacity=".3" rx="2"></rect><rect width="23.79" height="16.221" x="12.704" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">3+6+3</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="2+6+4">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="51" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".7" rx="2"></rect><rect width="23.79" height="16.221" x="9" fill-opacity=".3" rx="2"></rect><rect width="15.139" height="16.221" x="35" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">2+6+4</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="2+10">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".3" rx="2"></rect><rect width="41" height="16" x="9" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">2+10</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="5+7">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="28.927" height="16.221" x="20.653" fill-opacity=".7" rx="2"></rect><rect width="18.654" height="16.221" fill-opacity=".3" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">5+7</span>
                                            </a>
                                        </div>
                                                <div class="wpmm-grid-item">
                                            <a href="#" class="wpmm-column-layout" data-layout="2+3+7">
                                                <div class="wpmm-column-layout-preview">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" fill="none"><rect width="6.488" height="16.221" x=".143" fill-opacity=".7" rx="2"></rect><rect width="10" height="16.221" x="8.7" fill-opacity=".3" rx="2"></rect><rect width="28.927" height="16.221" x="20.653" fill-opacity=".7" rx="2"></rect></svg>					</div>
                                                <span class="wpmm-column-layout-name">2+3+7</span>
                                            </a>
                                        </div>

                                    <div class="wpmm-grid-item">
                                        <a href="#" class="wpmm-column-layout wpmm-custom" data-layout="custom">
                                            <div class="wpmm-column-layout-preview">Custom</div>
                                            <span class="wpmm-column-layout-name hu-sr-only">Custom</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="wpmm-custom-layout">
                                    <label>Custom Layout</label>
                                    <div class="wpmm-item-row wpmm-space-between">
                                        <input type="text" class="wpmm-custom-layout-field wpmm-item-grow" value="6+3+3">
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
                    <button type="button" class="button-primary">Save changes</button>
                </div>
            </div>
		</div>
	</div>
	</div>
