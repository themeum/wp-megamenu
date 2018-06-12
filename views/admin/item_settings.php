<?php
$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
$menu_item_depth = (int) sanitize_text_field($_POST['menu_item_depth']);

$get_menu_settings = get_post_meta($menu_item_id, 'wpmm_layout', true);
$wpmm_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

$menu_type = '';
if ( ! empty($get_menu_settings['menu_type'])){
    $menu_type = $get_menu_settings['menu_type'];
}

$menu_strees_row = '';
if ( ! empty($get_menu_settings['menu_strees_row'])){
    $menu_strees_row = $get_menu_settings['menu_strees_row'];
}
$item_width = '';
if ( ! empty( $get_menu_settings['options']['width'] )){
    $item_width =  $get_menu_settings['options']['width'];
}
$item_strees_row_width = '';
if ( ! empty( $get_menu_settings['options']['strees_row_width'] )){
    $item_strees_row_width =  $get_menu_settings['options']['strees_row_width'];
}
//Widgets Manager
$widgets_manager = new wp_megamenu_widgets();
$widgets = $widgets_manager->get_all_registered_widget();
//Get Menu Name
?>

<div class="wpmm-item-settings-top-bar">

    <div class="wpmm-item-settings-title">
        <h1><i class="fa fa-bars"></i> <span class="wpmm-item-settings-heading"></span></h1>
    </div>

    <div class="wpmm-onoff-wrap">
        <h1><?php _e('Mega Menu', 'wp-megamenu'); ?></h1>

        <div class="wpmm-stylish-checkbox"> <!-- Custom Checkbox -->
            <input id="wpmm-onoff" type="checkbox" value="1" <?php checked($menu_type, 'wpmm_mega_menu') ; ?> >
            <label for="wpmm-onoff">
                <span>
                    <span></span>
                    <strong class="custom-checkbox-1"><?php _e('YES', 'wp-megamenu'); ?></strong>
                    <strong class="custom-checkbox-2"><?php _e('NO', 'wp-megamenu'); ?></strong>
                </span>
            </label>
        </div> <!-- //.custom-checkbox -->

        <div class="wpmm-width-stress">
            <label>
                <select name="wpmm_strees_row">
                    <option value=""><?php _e('-- Select Stretch --'); ?></option>

                    <option value="wpmm-strees-default" <?php selected($menu_strees_row, 'wpmm-strees-default') ; ?> ><?php _e('Stretch Default', 'wp-megamenu'); ?></option>
                    <option value="wpmm-strees-row" <?php selected($menu_strees_row, 'wpmm-strees-row') ; ?> ><?php _e('Stretch Row', 'wp-megamenu'); ?></option>
                    <option value="wpmm-strees-row-and-content" <?php selected($menu_strees_row, 'wpmm-strees-row-and-content') ; ?> ><?php _e('Stretch Row and Content', 'wp-megamenu'); ?></option>
                </select>
            </label>

            <label id="wpmm_stress_row_width_label" style="display: <?php echo ($menu_strees_row ===
            'wpmm-strees-row' || $menu_strees_row === 'wpmm-strees-default' ) ? 'inline-block': 'none'; ?>;">
                <?php _e('Width', 'wp-megamenu'); ?>
                <input id="wpmm_stress_row_width" type="text" name="wpmm_stress_row_width" size="10" value="<?php echo $item_strees_row_width; ?>" placeholder="<?php _e('ex: 1170px', 'wp-megamenu'); ?>" />
            </label>

            <!--<label>
                <?php /*_e('Width', 'wp-megamenu'); */?>
                <input id="wpmm_item_row_width" type="text" name="wpmm_width" size="10" value="<?php /*echo $item_width; */?>" placeholder="<?php /*_e('ex: 500px', 'wp-megamenu'); */?>" />
            </label>-->
        </div>

        <!--<div class="builder-refresh">
            <a href="javascript:;" id="refresh_wpmm_builder"><i class="fa fa-refresh"></i></a>
        </div>-->

    </div>

    <a href="javascript:;" class="wpmm-isp-close-btn"><i class="fa fa-window-close"></i> </a>
    <a href="javascript:;" class="wpmm-saving-indecator" style="display: none;"><?php _e('Saving...', 'wp-megamenu'); ?></a>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<div id="wpmm-item-settings-tabs" class="wpmm-item-settings-panel wpmm-menu-builder-settings-panel" data-id="<?php echo $menu_item_id; ?>">

    <div class="wpmm-tabs-menu wpmm-tabs-menu-builder">
        <ul>
            <li class="active wpmmWidgetListLi" <?php if ($menu_type != 'wpmm_mega_menu'){ echo ' style="display: none;" '; } ?> >
                <a href="#wpmm-builder"> <i class="fa fa-th"></i> <?php _e
                    ('WP Mega Menu', 'wp-megamenu');
                    ?></a>
                <?php
                if ($menu_item_depth == 0){
                    ?>
                    <ul class="wpmm-widget-lists">
                        <li><?php _e('Widgets', 'wp-megamenu'); ?></li>
                        <li>
                            <div class="wmmDraggableWidgetLists innerLi">
                                <?php
                                if (count($widgets)){
                                    foreach ($widgets as $key => $value) {
                                        echo '<div class="draggableWidget" data-widget-id-base="' . $value['id_base'] . '" data-type="outsideWidget"> ' . $value['name'] . ' <span class="widgetsDragBtn"><i class="fa fa-arrows"></i> '.__('Drag', 'wp-megamenu').'</span></div>';
                                    }
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                <?php } ?>
            </li>
            <li><a href="#wpmm-options"> <i class="fa fa-gear"></i> <?php _e('Options', 'wp-megamenu'); ?></a></li>
            <li><a href="#wpmm-icons"> <i class="fa fa-cube"></i> <?php _e('Icon', 'wp-megamenu'); ?></a></li>
        </ul>
    </div>

    <div class="wpmm-tabs-content">
        <div id="wpmm-builder">
            <?php
            if ($menu_item_depth == 0){
                ?>
                <div class="wpmmDraggableWidgetArea <?php if ($menu_type != 'wpmm_mega_menu'){ echo ' disabled '; } ?> ">
                    <div class="shortable item-widgets-wrap wpmm-limit-height">
                        <?php
                        /**
                         * Get layout
                         */
                        echo '<div id="wpmm_item_layout_wrap">';
                        if ( count($wpmm_layout['layout']) ){
                            foreach ($wpmm_layout['layout'] as $layout_key => $layout_value){
                                echo '<div class="wpmm-row" data-row-id="'.$layout_key.'">';

                                echo '<div class="wpmm-row-actions">';
                                    echo '<p class="wpmm-row-left wpmmRowSortingIcon"> <i class="fa fa-sort"></i> '.__('Row', 'wp-megamenu').'  </p>';
                                    echo '<p class="wpmm-row-right"> <span class="wpmmRowDeleteIcon"><i class="fa fa-trash-o"></i> </span>  </p>';
                                echo '<div class="clear"></div>';
                                echo '</div>';

                                foreach ($layout_value['row'] as $col_key => $layout_col){
                                    echo '<div class="wpmm-col wpmm-col-'.$layout_col['col'].'" data-col-id="'.$col_key.'">';

                                    echo '<div class="wpmm-item-wrap">';
                                        echo '<div class="wpmm-column-actions">';
                                        echo '<span class="wpmmColSortingIcon"><i class="fa fa-arrows"></i> '.__('Column', 'wp-megamenu').' 
                                    </span>';
                                        echo '</div>';

                                    //echo '<p>'.__('Drop here', 'wp-megamenu').'</p>';

                                    foreach ($layout_col['items'] as $key => $value){
                                        if ($value['item_type'] == 'widget'){
                                            wp_megamenu_widgets()->widget_items($value['widget_id'], $get_menu_settings, $key);
                                        }else{
                                            wp_megamenu_widgets()->menu_items($value, $key);
                                        }
                                    }

                                    echo '</div>';

                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        }
                        echo '</div>';

                        echo '<div class="wpmm-addrow-btn-wrap">';
                        echo '<button id="choose_layout" class="choose_layout" name="choose_layout"> <i class="fa fa-plus-circle"></i> '.__('Add Row', 'wp-megamenu').' </button>';

                        $builderLayout = '<div class="wpmm-modal in" id="layout-modal" style="display: none;">
                            <ul class="menu-layout-list clearfix">
                                <li><a href="#" class="layout12" data-layout="12" data-design="layout12"><div class="first-grid last-grid grid-design"></div></a></li>
                                
                                <li><a href="#" class="layout66" data-layout="6,6" data-design="layout66"><div class="first-grid middle-grid grid-design grid-design33"></div> <div class="last-grid grid-design grid-design33"></div></a></li>
                                
                                <li><a href="#" class="layout444" data-layout="4,4,4" data-design="layout444"><div class="first-grid grid-design grid-design444"></div> <div class="grid-design middle-grid grid-design444"></div> <div class="last-grid grid-design grid-design444"></div></a></li>
                                
                                <li><a href="#" class="layout3333" data-layout="3,3,3,3" data-design="layout3333"><div class="first-grid grid-design grid-design3333"></div> <div class="grid-design middle-grid grid-design3333"></div> <div class="grid-design middle-grid-left grid-design3333"></div> <div class="last-grid grid-design grid-design3333"></div></a></li>
                                                                
                                <li><a href="#" class="layout222222" data-layout="2,2,2,2,2,2" data-design="layout222222"><div class="first-grid grid-design grid-design6"></div> <div class="grid-design middle-grid grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="grid-design middle-grid-left grid-design6"></div> <div class="last-grid grid-design grid-design6"></div></a></li>
                                
                                <li><a href="#" class="layout48" data-layout="4,8" data-design="layout48"><div class="first-grid middle-grid grid-design grid-design24"></div> <div class="last-grid grid-design grid-design24"></div></a></li>
                                
                                <li><a href="#" class="layout84" data-layout="8,4" data-design="layout84"><div class="first-grid middle-grid grid-design grid-design42"></div> <div class="last-grid grid-design grid-design42"></div></a></li>                                
                                <li><a href="#" class="layout210" data-layout="2,10" data-design="layout210"><div class="first-grid middle-grid grid-design grid-design15"></div> <div class="last-grid grid-design grid-design15"></div></a></li>                                
                                <li><a href="#" class="layout102" data-layout="10,2" data-design="layout102"><div class="first-grid middle-grid grid-design grid-design51"></div> <div class="last-grid grid-design grid-design51"></div></a></li>
                            </ul>
                        </div>';
                        echo $builderLayout;

                        echo '</div>';
                        ?>
                    </div>
                </div>
            <?php
            }else{
                echo '<p> '._e('WP Megamenu will be work only in top level menu', 'wp-megamenu').' </p>';
            } ?>
        </div>

        <div id="wpmm-options">

            <form method="post" action="options.php" class="wpmm_item_options_form">
                <?php //wp_nonce_field('wpmm_nonce_action','wpmm_nonce_field'); ?>

                <table class="wpmm-item-options">

                    <?php if ($menu_item_depth == 0){ ?>
                    <tr>
                        <td><?php _e('Upload Background Image', 'wp-megamenu'); ?></td>
                        <td>
                            <div class="wpmm-image-upload-wrap">
                                <?php $brand_logo = wpmm_get_item_settings($menu_item_id, 'menu_bg_image'); ?>
                                <input type="button" class="wpmm_upload_image_button button" value="<?php _e( 'Upload image', 'wp-megamenu' ); ?>" /> <br />
                                <div class="wpmm_upload_image_preview_wrap">
                                    <?php
                                    if ( ! empty($brand_logo)){
                                        echo '<img src="'.$brand_logo.'" class="wpmm_upload_image_preview" >';
                                        echo '<a href="javascript:;" class="wpmm_img_delete"><i class="fa fa-trash-o"></i> </a>';
                                    }
                                    ?>
                                </div>
                                <input type="text" class="wpmm_upload_image_field" name="options[menu_bg_image]" value="<?php echo $brand_logo; ?>" />
                                <p class="field-description"><?php _e('Menu background image', 'wp-megamenu'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>


                    <tr>
                        <td><?php _e('Hide Text', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_text]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_text'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Arrow', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_arrow]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_arrow'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Disable Link', 'wp-megamenu'); ?></td>
                        <td><input name="options[disable_link]" value="true" type="checkbox"  <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'disable_link'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Item on Mobile', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_item_on_mobile]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_item_on_mobile'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Hide Item on Desktop', 'wp-megamenu'); ?></td>
                        <td><input name="options[hide_item_on_desktop]" value="true" type="checkbox" <?php checked
                            (wpmm_get_item_settings($menu_item_id, 'hide_item_on_desktop'), 'true' ) ?> ></td>
                    </tr>

                    <tr>
                        <td><?php _e('Menu Item Alignment', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[item_align]" >
                                <option value="left" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                                <option value="center" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'center' ) ?> ><?php _e('Center', 'wp-megamenu'); ?></option>
                                <option value="right" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'item_align'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><?php _e('Dropdown alignment', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[dropdown_alignment]" >
                                <option value="right" <?php selected(wpmm_get_item_settings($menu_item_id, 'dropdown_alignment'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                                <option value="left" <?php selected(wpmm_get_item_settings($menu_item_id, 'dropdown_alignment'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><?php _e('Icon Position', 'wp-megamenu'); ?></td>
                        <td>
                            <select name="options[icon_position]">
                                <option value="left" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'left' ) ?> ><?php _e('Left', 'wp-megamenu'); ?></option>
                                <option value="top" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'top' ) ?> ><?php _e('Top', 'wp-megamenu'); ?></option>
                                <option value="right" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'icon_position'), 'right' ) ?> ><?php _e('Right', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Badge Text', 'wp-megamenu'); ?></td>
                        <td>

                            <input type="text" name="options[badge_text]" value="<?php echo wpmm_get_item_settings($menu_item_id, 'badge_text'); ?>" placeholder="<?php _e('Badge Text', 'wp-megamenu'); ?>">

                            <select name="options[badge_style]">
                                <option value=""><?php _e('Select Style', 'wp-megamenu'); ?></option>
                                <option value="default" <?php selected
                                (wpmm_get_item_settings($menu_item_id, 'badge_style'), 'default' ) ?> ><?php _e('Default', 'wp-megamenu'); ?></option>
                                <option value="primary" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'primary' ) ?> ><?php _e('Primary', 'wp-megamenu'); ?></option>
                                <option value="success" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'success' ) ?> ><?php _e('Success', 'wp-megamenu'); ?></option>
                                <option value="info" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'info' ) ?> ><?php _e('Info', 'wp-megamenu'); ?></option>
                                <option value="warning" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'warning' ) ?> ><?php _e('Warning', 'wp-megamenu'); ?></option>
                                <option value="danger" <?php selected(wpmm_get_item_settings($menu_item_id, 'badge_style'), 'danger' ) ?> ><?php _e('Danger', 'wp-megamenu'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>&af;</td>
                        <td class="wpmm-save-btn"><?php submit_button(); ?></td>
                    </tr>

                </table>

            </form>
        </div>

        <div id="wpmm-icons">

            <div class="wpmm-icons-wrap">
                <div class="wpmm-icons-menu">

                    <div class="wpmm-icons-topbar-left">
                        <ul>
                            <li><a href="#icons-tabs-1"><?php _e('Dashicons', 'wp-megamenu'); ?></a></li>
                            <li><a href="#icons-tabs-2"><?php _e('Font Awesome', 'wp-megamenu'); ?></a></li>
                        </ul>
                    </div>

                    <div class="wpmm-icons-topbar-right">
                        <div class="wpmm-icon-search-wrap">
                            <input id="wpmm_icons_search" type="text" value="" placeholder="<?php _e('Search...', 'wp-megamenu'); ?>">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="wpmm-icons-tab-content wpmm-limit-height">

                    <div id="icons-tabs-1">
                        <?php
                        $dashicons = wpmm_dashicons();

                        $current_icon = '';
                        if ( ! empty($get_menu_settings['options']['icon'])){
                            $current_icon = $get_menu_settings['options']['icon'];
                        }
                        echo "<a href='javascript:;' class='wpmm-icons' data-icon='' title=''>&nbsp;</a>";
                        foreach ($dashicons as $dikey => $diname){
                            $selected_icon = ($current_icon == 'dashicons '.$dikey) ? 'wpmm-icon-selected' :'';
                            echo "<a href='javascript:;' class='wpmm-icons {$selected_icon} ' data-icon='dashicons {$dikey}' title='{$diname}'>
                            <i class='dashicons {$dikey}'></i></a>";
                        }
                        ?>
                    </div>

                    <div id="icons-tabs-2">
                        <?php
                        $font_awesome = wpmm_font_awesome();

                        $current_icon = '';
                        if ( ! empty($get_menu_settings['options']['icon'])){
                            $current_icon = $get_menu_settings['options']['icon'];
                        }
                        echo "<a href='javascript:;' class='wpmm-icons' data-icon='' title=''>&nbsp;</a>";
                        foreach ($font_awesome as $dikey => $diname){
                            $selected_icon = ($current_icon == 'fa '.$diname) ? 'wpmm-icon-selected' :'';
                            echo "<a href='javascript:;' class='wpmm-icons {$selected_icon} ' data-icon='fa {$diname}' title='{$diname}'>
                            <i class='fa {$diname}'></i></a>";
                        }
                        ?>
                    </div>

                </div>

            </div>


        </div>
    </div>

    <div class="clear"></div>
</div>
