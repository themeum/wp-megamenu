<?php
if ( ! empty($_GET['theme_id'])){
    $theme_id = (int) $_GET['theme_id'];
}else{
    $theme_id = 0;
}
$wpmm_theme_title =  get_wpmm_theme_option('wpmm_theme_title', $theme_id);
$wpmm_theme_name =  get_wpmm_theme_option('wpmm_theme_name', $theme_id);
?>

<form method="post" action="">

    <div class="wrap wpmm-wrap wpmm-theme-settings">
        <div class="wpmm-theme-head wpmm-theme-settings-head clearfix">
            <div class="left">
                <h3><?php esc_html_e('Themes Settings', 'wp-megamenu'); ?></h3>
            </div>
            <div class="right">
                <?php submit_button(); ?>
            </div>
        </div>

        <div id="wpmm-tabs" class="wpmm-settings-wrap wpmm-theme-settings-wrap">
            <div class="wpmm-settings-tabs-menu">
                <ul>
                    <li><a href="#tabs-1"><?php esc_html_e('General Settings', 'wp-megamenu'); ?></a></li>
                    <?php 
                        do_action('wpmm_settings_tab_menu_before', $theme_id);
                        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                        if (!is_plugin_active('wp-megamenu-pro/wp-megamenu-pro.php')) { ?>

                        <li class="wpmm_settings_pro"><a href="#tabs-wpmm-pro"><?php esc_html_e('Vertical Menu', 'wp-megamenu'); ?><sup class="badge">pro</sup></a></li>
                        <li class="wpmm_settings_pro"><a href="#tabs-wpmm-pro"><?php esc_html_e('CTA Button', 'wp-megamenu'); ?><sup class="badge">pro</sup></a></li>
                            
                        <!-- New Featured -->
                        <li class="wpmm_settings_pro"><a href="#tabs-wpmm-pro"><?php esc_html_e('Login/Signup', 'wp-megamenu'); ?><sup class="badge">pro</sup></a></li>
                        <li class="wpmm_settings_pro"><a href="#tabs-wpmm-pro"><?php esc_html_e('Woo Cart Button', 'wp-megamenu'); ?><sup class="badge">pro</sup></a></li>

                    <?php } ?>

                    <li><a href="#tabs-3"><?php esc_html_e('Menu Bar', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-2"><?php esc_html_e('Brand Logo', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-4"><?php esc_html_e('First Level Menu Items', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-6"><?php esc_html_e('Sub Menu Items', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-5"><?php esc_html_e('Dropdown Menu', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-7"><?php esc_html_e('Mega Menu', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-8"><?php esc_html_e('Widgets', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-13"><?php esc_html_e('Animation', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-10"><?php esc_html_e('Mobile Menu', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-12"><?php esc_html_e('Social Links', 'wp-megamenu'); ?></a></li>
                    <?php do_action('wpmm_settings_tab_menu_after', $theme_id); ?>
                    <li><a href="#tabs-11"><?php esc_html_e('Additional CSS/JS', 'wp-megamenu'); ?></a></li>
                    <li><a href="#tabs-14"><?php esc_html_e('Export', 'wp-megamenu'); ?></a></li>
                </ul>
            </div>

            <div class="wpmm-tabs-content-wrap">
                <div id="tabs-1">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('General Settings', 'wp-megamenu'); ?> </th>
                        </tr>
                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Theme Title', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <input type="text" name="wpmm_theme_title" class="wpmm_theme_title" value="<?php esc_attr_e( $wpmm_theme_title ); ?>" required="required" placeholder="<?php esc_attr_e('Theme title', 'wp-megamenu');
                                ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Enable Sticky Menu', 'wp-megamenu'); ?>
                            </th>

                            <td>

                                <?php
                                    $enable_sticky_menu = get_wpmm_theme_option('enable_sticky_menu', $theme_id)
                                ?>

                                <label> <input type='checkbox' name='wpmm_theme_option[enable_sticky_menu]' value='true' <?php checked($enable_sticky_menu, 'true'); ?> > <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                </label>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Z-index', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <?php $zindex = get_wpmm_theme_option('zindex', $theme_id) ?>

                                <input type="number" name="wpmm_theme_option[zindex]" value="<?php esc_attr_e( $zindex ); ?>"  />
                                <p class="field-description"><?php esc_html_e( 'Recommended value 100. Increase more if the menu gets overflown by other sections.','wp-megamenu' ); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Dropdown Indicator Arrow', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <p><?php esc_html_e('Down', 'wp-megamenu'); ?></p>
                                    <select name="wpmm_theme_option[dropdown_arrow_down]" class="wpmm_theme_dropdown_arrow wpmm-select2-no-search">
                                        <option value="">&nbsp;</option>

                                        <?php
                                        $dropdown_icons = wpmm_dropdown_indicator_icon();
                                        $dropdown_arrow_down = get_wpmm_theme_option('dropdown_arrow_down', $theme_id);
                                        foreach ($dropdown_icons as $key => $icon){
                                            echo '<option value="'.esc_attr( $icon ).'"'. selected($dropdown_arrow_down, $icon, false) .' data-icon="'.esc_attr( $icon ).'"> &nbsp;</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>
                                    <select name="wpmm_theme_option[dropdown_arrow_left]" class="wpmm_theme_dropdown_arrow wpmm-select2-no-search">
                                        <option value="">&nbsp;</option>

                                        <?php
                                        $dropdown_icons = wpmm_dropdown_indicator_icon();
                                        $dropdown_arrow_left = get_wpmm_theme_option('dropdown_arrow_left', $theme_id);
                                        foreach ($dropdown_icons as $key => $icon){
                                            echo '<option value="'.esc_attr( $icon ).'"'. selected($dropdown_arrow_left, $icon, false) .' data-icon="'.esc_attr( $icon ).'"> &nbsp;</option>';
                                        }
                                        ?>

                                    </select>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>
                                    <select name="wpmm_theme_option[dropdown_arrow_right]" class="wpmm_theme_dropdown_arrow wpmm-select2-no-search">
                                        <option value="">&nbsp;</option>

                                        <?php
                                        $dropdown_icons = wpmm_dropdown_indicator_icon();
                                        $dropdown_arrow_right = get_wpmm_theme_option('dropdown_arrow_right', $theme_id);
                                        foreach ($dropdown_icons as $key => $icon){
                                            echo '<option value="'.esc_attr( $icon ).'"'. selected($dropdown_arrow_right, $icon, false) .' data-icon="'.esc_attr( $icon ).'"> &nbsp;</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Enable Search Bar', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php
                                    $enable_search_bar = get_wpmm_theme_option('enable_search_bar', $theme_id);
                                ?>
                                <label> <input type='checkbox' name='wpmm_theme_option[enable_search_bar]' value='true' <?php checked($enable_search_bar, 'true'); ?> /> <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                </label>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Search field placeholder', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php
                                $search_field_placeholder = get_wpmm_theme_option('search_field_placeholder', $theme_id);
                                ?>
                                <input type="text" name="wpmm_theme_option[search_field_placeholder]" class="search_field_placeholder" value="<?php esc_attr_e( $search_field_placeholder ); ?>" placeholder="<?php esc_attr_e('Search', 'wp-megamenu'); ?>" />
                            </td>
                        </tr>

                    </table>
                </div>
                <?php
                    do_action('wpmm_settings_tab_content', $theme_id);
                    if (!is_plugin_active('wp-megamenu-pro/wp-megamenu-pro.php')) { ?>
                            <div id="tabs-wpmm-pro"></div>
                        <?php
                    }
                ?>


                <div id="tabs-2">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">

                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Brand Logo', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Add Logo', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm-image-upload-wrap">
                                    <?php $brand_logo = get_wpmm_theme_option('brand_logo', $theme_id); ?>

                                    <input id="upload_image_button" type="button" class="wpmm_upload_image_button button" value="<?php esc_attr_e( 'Upload image', 'wp-megamenu' ); ?>" /> <br />

                                    <div class="wpmm_upload_image_preview_wrap">
                                        <?php
                                        if ( ! empty($brand_logo)){
                                            echo '<img src="'.esc_url( $brand_logo ).'" class="wpmm_upload_image_preview" >';
                                            echo '<a href="javascript:;" class="wpmm_img_delete"><i class="fa fa-trash-o"></i> </a>';
                                        }
                                        ?>
                                    </div>
                                    <input type="text" class="wpmm_upload_image_field" name="wpmm_theme_option[brand_logo]" value="<?php esc_attr_e( $brand_logo ); ?>" />
                                    <p class="field-description"><?php esc_html_e('Brand Logo URL', 'wp-megamenu'); ?></p>
                                </div>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Logo Width', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[brand_logo_width]" value="<?php esc_attr_e( get_wpmm_theme_option('brand_logo_width', $theme_id) ); ?>" placeholder="32px" />
                                <p class="field-description"><?php esc_html_e('Logo Width.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Logo Height', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[brand_logo_height]" value="<?php esc_attr_e( get_wpmm_theme_option('brand_logo_height', $theme_id) ); ?>" placeholder="31px"/>
                                <p class="field-description"><?php esc_html_e('Logo Height.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Logo Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>
                                        <?php
                                        $logo_margin_top = get_wpmm_theme_option('logo_margin_top', $theme_id);
                                        if( ! $logo_margin_top){
                                            $logo_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[logo_margin_top]' value="<?php esc_attr_e( $logo_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $logo_margin_right = get_wpmm_theme_option('logo_margin_right', $theme_id);
                                        if( ! $logo_margin_right){
                                            $logo_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[logo_margin_right]' value="<?php esc_attr_e( $logo_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $logo_margin_bottom = get_wpmm_theme_option('logo_margin_bottom', $theme_id);
                                        if( ! $logo_margin_bottom){
                                            $logo_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[logo_margin_bottom]' value="<?php esc_attr_e( $logo_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $logo_margin_left = get_wpmm_theme_option('logo_margin_left', $theme_id);
                                        if( ! $logo_margin_left){
                                            $logo_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[logo_margin_left]' value="<?php esc_attr_e( $logo_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                            </tr>
                        </tr>
                    </table>
                </div>

                <div id="tabs-3">

                    <!-- Position and layout -->
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th> <?php esc_html_e('Menu Bar', 'wp-megamenu'); ?> </th>
                            <td></td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Align', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <select name="wpmm_theme_option[menu_align]">
                                    <?php
                                        $menu_align = get_wpmm_theme_option('menu_align', $theme_id);
                                    ?>
                                    <option value="left" <?php selected($menu_align, 'left') ?>> <?php esc_html_e('Left', 'wp-megamenu'); ?> </option>
                                    <option value="center"  <?php selected($menu_align, 'center') ?> > <?php esc_html_e('Center', 'wp-megamenu'); ?> </option>
                                    <option value="right"  <?php selected($menu_align, 'right') ?> > <?php esc_html_e('Right', 'wp-megamenu'); ?> </option>
                                </select>

                                <p class="field-description"> <?php esc_html_e('Align menu to left, center or right. Default: Left.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Bar Height', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[menubar_height]" value="<?php esc_attr_e( get_wpmm_theme_option('menubar_height', $theme_id) ); ?>" placeholder="54px" />
                                <p class="field-description"><?php esc_html_e('Define the height of menu bar. Ex. px', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Background', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[menubar_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('menubar_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set transparent or solid color for menu background.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[menubar_bg_2]" value="<?php esc_attr_e( get_wpmm_theme_option('menubar_bg_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set second color for gradient background.', 'wp-megamenu'); ?></p>
                                        </td>

                                        <?php
                                        $menubar_bg_gradient_angle = get_wpmm_theme_option('menubar_bg_gradient_angle', $theme_id);
                                        $menubar_bg_gradient_angle = !empty($menubar_bg_gradient_angle) ? intval($menubar_bg_gradient_angle) : -90;
                                        if($menubar_bg_gradient_angle >= 360 || $menubar_bg_gradient_angle  <= -360){
                                            $menubar_bg_gradient_angle = -90;
                                        }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[menubar_bg_gradient_angle]" value="<?php esc_attr_e( $menubar_bg_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Sticky Menu Background', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[sticky_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('sticky_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set transparent or solid color for menu background.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[sticky_bg_2]" value="<?php esc_attr_e( get_wpmm_theme_option('sticky_bg_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set second color for gradient background.', 'wp-megamenu'); ?></p>
                                        </td>

                                        <?php
                                        $sticky_bg_gradient_angle = get_wpmm_theme_option('sticky_bg_gradient_angle', $theme_id);
                                        $sticky_bg_gradient_angle = !empty($sticky_bg_gradient_angle) ? intval($sticky_bg_gradient_angle) : -90;
                                        if($sticky_bg_gradient_angle >= 360 || $sticky_bg_gradient_angle  <= -360){
                                            $sticky_bg_gradient_angle = -90;
                                        }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[sticky_bg_gradient_angle]" value="<?php esc_attr_e( $sticky_bg_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>



                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_padding_top = get_wpmm_theme_option('menu_padding_top', $theme_id);
                                        if( ! $menu_padding_top){
                                            $menu_padding_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_padding_top]' value="<?php esc_attr_e( $menu_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_padding_right = get_wpmm_theme_option('menu_padding_right', $theme_id);
                                        if( ! $menu_padding_right){
                                            $menu_padding_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_padding_right]' value="<?php esc_attr_e( $menu_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_padding_bottom = get_wpmm_theme_option('menu_padding_bottom', $theme_id);
                                        if( ! $menu_padding_bottom){
                                            $menu_padding_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_padding_bottom]' value="<?php esc_attr_e( $menu_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_padding_left = get_wpmm_theme_option('menu_padding_left', $theme_id);
                                        if( ! $menu_padding_left){
                                            $menu_padding_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_padding_left]' value="<?php esc_attr_e( $menu_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border Radius', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_border_radius_top_left = get_wpmm_theme_option('menu_border_radius_top_left', $theme_id);
                                        if( ! $menu_border_radius_top_left){
                                            $menu_border_radius_top_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_border_radius_top_left]' value="<?php esc_attr_e( $menu_border_radius_top_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_radius_top_right = get_wpmm_theme_option('menu_radius_top_right', $theme_id);
                                        if( ! $menu_radius_top_right){
                                            $menu_radius_top_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_radius_top_right]' value="<?php esc_attr_e( $menu_radius_top_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_radius_bottom_left = get_wpmm_theme_option('menu_radius_bottom_left', $theme_id);
                                        if( ! $menu_radius_bottom_left){
                                            $menu_radius_bottom_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_radius_bottom_left]' value="<?php esc_attr_e( $menu_radius_bottom_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $menu_radius_bottom_right = get_wpmm_theme_option('menu_radius_bottom_right', $theme_id);
                                        if( ! $menu_radius_bottom_right){
                                            $menu_radius_bottom_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[menu_radius_bottom_right]'
                                               value="<?php esc_attr_e( $menu_radius_bottom_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <p class="field-description"><?php esc_html_e('Set border radius on the menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Shadow', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment block">
                                    <label>
                                        <?php $shadow_enable = get_wpmm_theme_option('shadow_enable', $theme_id); ?>
                                        <input type='checkbox' name='wpmm_theme_option[shadow_enable]' value='false' <?php checked($shadow_enable, 'false'); ?> />
                                        <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                    </label>

                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('h-shadow', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_shadow = get_wpmm_theme_option('top_shadow', $theme_id);
                                        if( ! $top_shadow){
                                            $top_shadow = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[top_shadow]" value="<?php esc_attr_e( $top_shadow ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('v-shadow', 'wp-megamenu'); ?></p>

                                        <?php
                                        $right_shadow = get_wpmm_theme_option('right_shadow', $theme_id);
                                        if( ! $right_shadow){
                                            $right_shadow = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[right_shadow]" value="<?php esc_attr_e( $right_shadow ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Blur', 'wp-megamenu'); ?></p>

                                        <?php
                                        $bottom_shadow = get_wpmm_theme_option('bottom_shadow', $theme_id);
                                        if( ! $bottom_shadow){
                                            $bottom_shadow = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[bottom_shadow]" value="<?php esc_attr_e( $bottom_shadow ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Spread', 'wp-megamenu'); ?></p>

                                        <?php
                                        $left_shadow = get_wpmm_theme_option('left_shadow', $theme_id);
                                        if( ! $left_shadow){
                                            $left_shadow = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[left_shadow]" value="<?php esc_attr_e( $left_shadow ); ?>" placeholder="0px"  />
                                    </label>
                                </div>

                                <br />  <br />
                                <input type="text" name="wpmm_theme_option[wpmm_theme_shadow]" value="<?php esc_attr_e( get_wpmm_theme_option('wpmm_theme_shadow', $theme_id) ); ?>"
                                       class="wpmmColorPicker" data-alpha="true" />
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[menubar_top_border_width]' value="<?php esc_attr_e( get_wpmm_theme_option('menubar_top_border_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[menubar_right_border_width]' value="<?php esc_attr_e( get_wpmm_theme_option('menubar_right_border_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[menubar_bottom_border_width]' value="<?php esc_attr_e( get_wpmm_theme_option('menubar_bottom_border_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[menubar_left_border_width]' value="<?php esc_attr_e( get_wpmm_theme_option('menubar_left_border_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[menu_border_type]">

                                            <?php $menu_border_type = get_wpmm_theme_option('menu_border_type', $theme_id); ?>
                                            <option value="none" <?php selected($menu_border_type, 'none'); ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($menu_border_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($menu_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($menu_border_type, 'dotted') ?> >  <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($menu_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($menu_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($menu_border_type, 'ridge') ?> >  <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($menu_border_type, 'inset') ?> >  <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($menu_border_type, 'outset') ?> >  <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[menu_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('menu_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"><?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="tabs-4">
                    <!-- Top Level Menu Items -->

                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('First Level Menu Items', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Text', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font', 'wp-megamenu'); ?></span>

                                        <?php
                                        $google_fonts = wpmm_get_google_fonts();
                                        $top_lavel_saved_font = get_wpmm_theme_option('top_level_item_text_font',
                                            $theme_id);
                                        ?>
                                        <select name="wpmm_theme_option[top_level_item_text_font]" class="select2">
                                            <option value=""><?php esc_html_e('Select font', 'wp-megamenu'); ?></option>
                                            <?php
                                            foreach ($google_fonts as $font){
                                                $is_top_lavel_font_selected = ($top_lavel_saved_font === $font) ? ' selected="selected" ':'';
                                                echo '<option value="' . esc_attr( $font ) . '" ' . $is_top_lavel_font_selected . '>' . esc_html( $font ) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>

                                    <p class="field-description"><?php echo sprintf(__('%d google fonts available', 'wp-megamenu'), count($google_fonts)); ?></p>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[top_level_item_text_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_text_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Hover Color', 'wp-megamenu'); ?> </span>
                                        <input type="text" name="wpmm_theme_option[top_level_item_text_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_text_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $top_level_item_text_font_size = get_wpmm_theme_option('top_level_item_text_font_size', $theme_id);
                                        if( ! $top_level_item_text_font_size){
                                            $top_level_item_text_font_size = '14px';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[top_level_item_text_font_size]" value="<?php esc_attr_e( $top_level_item_text_font_size ); ?>" data-alpha="true" placeholder="14px" />
                                    </label>
                                </div>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Font Weight', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $top_level_item_text_font_weight = get_wpmm_theme_option('top_level_item_text_font_weight', $theme_id);
                                        if( ! $top_level_item_text_font_weight){
                                            $top_level_item_text_font_weight = '400';
                                        }
                                        ?>
                                        <select name="wpmm_theme_option[top_level_item_text_font_weight]">
                                            <option value="normal" <?php selected($top_level_item_text_font_weight, 'normal') ?> > <?php esc_html_e('Normal', 'wp-megamenu'); ?> </option>
                                            <option value="bold" <?php selected($top_level_item_text_font_weight, 'bold') ?> > <?php esc_html_e('Bold', 'wp-megamenu'); ?> </option>
                                            <option value="bolder" <?php selected($top_level_item_text_font_weight, 'bolder') ?> > <?php esc_html_e('Bolder', 'wp-megamenu'); ?> </option>
                                            <option value="lighter" <?php selected($top_level_item_text_font_weight, 'lighter') ?> > <?php esc_html_e('Lighter', 'wp-megamenu'); ?> </option>
                                            <option value="100" <?php selected($top_level_item_text_font_weight, '100') ?> > <?php esc_html_e('100', 'wp-megamenu'); ?> </option>
                                            <option value="200" <?php selected($top_level_item_text_font_weight, '200') ?> > <?php esc_html_e('200', 'wp-megamenu'); ?> </option>
                                            <option value="300" <?php selected($top_level_item_text_font_weight, '300') ?> > <?php esc_html_e('300', 'wp-megamenu'); ?> </option>
                                            <option value="400" <?php selected($top_level_item_text_font_weight, '400') ?> > <?php esc_html_e('400', 'wp-megamenu'); ?> </option>
                                            <option value="500" <?php selected($top_level_item_text_font_weight, '500') ?> > <?php esc_html_e('500', 'wp-megamenu'); ?> </option>
                                            <option value="600" <?php selected($top_level_item_text_font_weight, '600') ?> > <?php esc_html_e('600', 'wp-megamenu'); ?> </option>
                                            <option value="700" <?php selected($top_level_item_text_font_weight, '700') ?> > <?php esc_html_e('700', 'wp-megamenu'); ?> </option>
                                            <option value="800" <?php selected($top_level_item_text_font_weight, '800') ?> > <?php esc_html_e('800', 'wp-megamenu'); ?> </option>
                                            <option value="900" <?php selected($top_level_item_text_font_weight, '900') ?> > <?php esc_html_e('900', 'wp-megamenu'); ?> </option>
                                        </select>

                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Line Height', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $top_level_item_text_line_height = get_wpmm_theme_option('top_level_item_text_line_height', $theme_id);
                                        if( ! $top_level_item_text_line_height){
                                            $top_level_item_text_line_height = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[top_level_item_text_line_height]" value="<?php esc_attr_e( $top_level_item_text_line_height ); ?>" data-alpha="true" placeholder="24px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Text Transform', 'wp-megamenu'); ?> </span>

                                        <select name="wpmm_theme_option[top_level_item_text_transform]">
                                            <?php
                                            $top_level_item_text_transform = get_wpmm_theme_option('top_level_item_text_transform', $theme_id);
                                            ?>
                                            <option value="none" <?php selected($top_level_item_text_transform, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="inherit" <?php selected($top_level_item_text_transform, 'inherit') ?> > <?php esc_html_e('Inherit', 'wp-megamenu'); ?> </option>
                                            <option value="uppercase" <?php selected($top_level_item_text_transform, 'uppercase') ?> > <?php esc_html_e('Uppercase', 'wp-megamenu'); ?> </option>
                                            <option value="lowercase" <?php selected($top_level_item_text_transform, 'lowercase') ?> > <?php esc_html_e('Lowercase', 'wp-megamenu'); ?> </option>
                                            <option value="capitalize" <?php selected($top_level_item_text_transform, 'capitalize') ?> > <?php esc_html_e('Capitalize', 'wp-megamenu'); ?> </option>
                                        </select>

                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Letter Spacing', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $top_level_item_text_letter_spacing = get_wpmm_theme_option('top_level_item_text_letter_spacing', $theme_id);
                                        if( ! $top_level_item_text_letter_spacing){
                                            $top_level_item_text_letter_spacing = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[top_level_item_text_letter_spacing]" value="<?php esc_attr_e( $top_level_item_text_letter_spacing ); ?>" data-alpha="true" placeholder="0px"/>
                                    </label>
                                </div>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Background Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_bg_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set item background color.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_color_2]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_bg_color_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set second background color for gradient.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <?php
                                        $top_level_item_bg_color_gradient_angle = get_wpmm_theme_option('top_level_item_bg_color_gradient_angle', $theme_id);
                                        $top_level_item_bg_color_gradient_angle = !empty($top_level_item_bg_color_gradient_angle) ? intval($top_level_item_bg_color_gradient_angle) : -90;
                                        if($top_level_item_bg_color_gradient_angle >= 360 || $top_level_item_bg_color_gradient_angle  <= -360){
                                            $top_level_item_bg_color_gradient_angle = -90;
                                        }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_color_gradient_angle]" value="<?php esc_attr_e( $top_level_item_bg_color_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Background Hover Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_bg_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set item background hover color.', 'wp-megamenu'); ?></p>
                                        </td>

                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_hover_color_2]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_bg_hover_color_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set second background hover color for gradient.', 'wp-megamenu'); ?></p>
                                        </td>

                                        <?php
                                        $top_level_item_bg_hover_color_gradient_angle = !empty(get_wpmm_theme_option('top_level_item_bg_hover_color_gradient_angle', $theme_id)) ? intval(get_wpmm_theme_option('top_level_item_bg_hover_color_gradient_angle', $theme_id)) : -90;
                                        if($top_level_item_bg_hover_color_gradient_angle >= 360 || $top_level_item_bg_hover_color_gradient_angle  <= -360){
                                            $top_level_item_bg_hover_color_gradient_angle = -90;
                                        }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[top_level_item_bg_hover_color_gradient_angle]" value="<?php esc_attr_e( $top_level_item_bg_hover_color_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <?php
                                        $top_level_item_padding_top = get_wpmm_theme_option('top_level_item_padding_top', $theme_id);
                                        if( ! $top_level_item_padding_top){
                                            $top_level_item_padding_top = '';
                                        }
                                        ?>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[top_level_item_padding_top]' value="<?php esc_attr_e( $top_level_item_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_padding_right = get_wpmm_theme_option('top_level_item_padding_right', $theme_id);
                                        if( ! $top_level_item_padding_right){
                                            $top_level_item_padding_right = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[top_level_item_padding_right]' value="<?php esc_attr_e( $top_level_item_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_padding_bottom = get_wpmm_theme_option('top_level_item_padding_bottom', $theme_id);
                                        if( ! $top_level_item_padding_bottom){
                                            $top_level_item_padding_bottom = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[top_level_item_padding_bottom]' value="<?php esc_attr_e( $top_level_item_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_padding_left = get_wpmm_theme_option('top_level_item_padding_left', $theme_id);
                                        if( ! $top_level_item_padding_left){
                                            $top_level_item_padding_left = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[top_level_item_padding_left]' value="<?php esc_attr_e( $top_level_item_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to the menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>
                                        <?php
                                        $top_level_item_margin_top = get_wpmm_theme_option('top_level_item_margin_top', $theme_id);
                                        if( ! $top_level_item_margin_top){
                                            $top_level_item_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_margin_top]' value="<?php esc_attr_e( $top_level_item_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_margin_right = get_wpmm_theme_option('top_level_item_margin_right', $theme_id);
                                        if( ! $top_level_item_margin_right){
                                            $top_level_item_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_margin_right]' value="<?php esc_attr_e( $top_level_item_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_margin_bottom = get_wpmm_theme_option('top_level_item_margin_bottom', $theme_id);
                                        if( ! $top_level_item_margin_bottom){
                                            $top_level_item_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_margin_bottom]' value="<?php esc_attr_e( $top_level_item_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_margin_left = get_wpmm_theme_option('top_level_item_margin_left', $theme_id);
                                        if( ! $top_level_item_margin_left){
                                            $top_level_item_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_margin_left]' value="<?php esc_attr_e( $top_level_item_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>



                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_border_width = get_wpmm_theme_option('top_level_item_border_width', $theme_id);
                                        if( ! $top_level_item_border_width){
                                            $top_level_item_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_border_width]' value="<?php esc_attr_e( $top_level_item_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_border_width_right = get_wpmm_theme_option('top_level_item_border_width_right', $theme_id);
                                        if( ! $top_level_item_border_width_right){
                                            $top_level_item_border_width_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_border_width_right]' value="<?php esc_attr_e( $top_level_item_border_width_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_border_width_bottom = get_wpmm_theme_option('top_level_item_border_width_bottom', $theme_id);
                                        if( ! $top_level_item_border_width_bottom){
                                            $top_level_item_border_width_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_border_width_bottom]' value="<?php esc_attr_e( $top_level_item_border_width_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_border_width_left = get_wpmm_theme_option('top_level_item_border_width_left', $theme_id);
                                        if( ! $top_level_item_border_width_left){
                                            $top_level_item_border_width_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_border_width_left]' value="<?php esc_attr_e( $top_level_item_border_width_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[top_level_item_border_type]">
                                            <?php
                                                $top_level_item_border_type = get_wpmm_theme_option('top_level_item_border_type', $theme_id);
                                            ?>
                                            <option value="" <?php selected($top_level_item_border_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($top_level_item_border_type, 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($top_level_item_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($top_level_item_border_type, 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($top_level_item_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($top_level_item_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($top_level_item_border_type, 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($top_level_item_border_type, 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($top_level_item_border_type, 'outset') ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[top_level_item_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>
                                <p class="field-description"> <?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Border Hover', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_hover_border_width = get_wpmm_theme_option('top_level_item_hover_border_width', $theme_id);
                                        if( ! $top_level_item_hover_border_width){
                                            $top_level_item_hover_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_hover_border_width]' value="<?php esc_attr_e( $top_level_item_hover_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                               <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_hover_border_width_right = get_wpmm_theme_option('top_level_item_hover_border_width_right', $theme_id);
                                        if( ! $top_level_item_hover_border_width_right){
                                            $top_level_item_hover_border_width_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_hover_border_width_right]' value="<?php esc_attr_e( $top_level_item_hover_border_width_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                               <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_hover_border_width_bottom = get_wpmm_theme_option('top_level_item_hover_border_width_bottom', $theme_id);
                                        if( ! $top_level_item_hover_border_width_bottom){
                                            $top_level_item_hover_border_width_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_hover_border_width_bottom]' value="<?php esc_attr_e( $top_level_item_hover_border_width_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                               <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $top_level_item_hover_border_width_left = get_wpmm_theme_option('top_level_item_hover_border_width_left', $theme_id);
                                        if( ! $top_level_item_hover_border_width_left){
                                            $top_level_item_hover_border_width_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[top_level_item_hover_border_width_left]' value="<?php esc_attr_e( $top_level_item_hover_border_width_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[top_level_item_hover_border_type]">
                                            <option value="none" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>                                            
                                            <option value="solid" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected(get_wpmm_theme_option('top_level_item_hover_border_type', $theme_id), 'outset') ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[top_level_item_hover_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('top_level_item_hover_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set hover border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group ">
                            <th>
                                <?php esc_html_e('Highlight Current Item', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php $top_level_item_highlight_current_item = get_wpmm_theme_option('top_level_item_highlight_current_item', $theme_id); ?>
                                <label> <input type='checkbox' name='wpmm_theme_option[top_level_item_highlight_current_item]' value='false' <?php checked($top_level_item_highlight_current_item , 'false'); ?> > <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                </label>
                            </td>
                        </tr>

                    </table>

                </div>

                <div id="tabs-5">
                    <!-- Dropdown Menu -->

                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th> <?php esc_html_e('Dropdown Menu', 'wp-megamenu'); ?> </th>
                            <td></td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Dropdown Menu width', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php
                                $dropdown_menu_width = get_wpmm_theme_option('dropdown_menu_width', $theme_id);
                                if( ! $dropdown_menu_width){
                                    $dropdown_menu_width = '';
                                }
                                ?>
                                <input type="text" name="wpmm_theme_option[dropdown_menu_width]" value="<?php esc_attr_e( $dropdown_menu_width ); ?>" placeholder="0px" />
                                <p class="field-description"><?php esc_html_e('Define the width of dropdown, ex: 400px', 'wp-megamenu');
                                    ?></p>
                            </td>
                        </tr>


                        <tr>
                            <th>
                                <?php esc_html_e('Dropdown Menu Background', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[dropdown_menu_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('dropdown_menu_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set the dropdown menu background color.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[dropdown_menu_bg_2]" value="<?php esc_attr_e( get_wpmm_theme_option('dropdown_menu_bg_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set the second color for gradient.', 'wp-megamenu'); ?></p>
                                        </td>

                                        <?php
                                            $dropdown_menu_bg_gradient_angle = !empty(get_wpmm_theme_option('dropdown_menu_bg_gradient_angle', $theme_id)) ? intval(get_wpmm_theme_option('dropdown_menu_bg_gradient_angle', $theme_id)) : -90;
                                            if($dropdown_menu_bg_gradient_angle >= 360 || $dropdown_menu_bg_gradient_angle  <= -360){
                                                $dropdown_menu_bg_gradient_angle = -90;
                                            }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[dropdown_menu_bg_gradient_angle]" value="<?php esc_attr_e( $dropdown_menu_bg_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_padding_top = get_wpmm_theme_option('dropdown_menu_padding_top', $theme_id);
                                        if( ! $dropdown_menu_padding_top){
                                            $dropdown_menu_padding_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_padding_top]' value="<?php esc_attr_e( $dropdown_menu_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_padding_right = get_wpmm_theme_option('dropdown_menu_padding_right', $theme_id);
                                        if( ! $dropdown_menu_padding_right){
                                            $dropdown_menu_padding_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_padding_right]' value="<?php esc_attr_e( $dropdown_menu_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_padding_bottom = get_wpmm_theme_option('dropdown_menu_padding_bottom', $theme_id);
                                        if( ! $dropdown_menu_padding_bottom){
                                            $dropdown_menu_padding_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_padding_bottom]' value="<?php esc_attr_e( $dropdown_menu_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_padding_left = get_wpmm_theme_option('dropdown_menu_padding_left', $theme_id);
                                        if( ! $dropdown_menu_padding_left){
                                            $dropdown_menu_padding_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_padding_left]' value="<?php esc_attr_e( $dropdown_menu_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to dropdown menu.', 'wp-megamenu');
                                    ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border Radius', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_border_radius_top_left = get_wpmm_theme_option('dropdown_menu_border_radius_top_left', $theme_id);
                                        if( ! $dropdown_menu_border_radius_top_left){
                                            $dropdown_menu_border_radius_top_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_border_radius_top_left]' value="<?php esc_attr_e( $dropdown_menu_border_radius_top_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_radius_top_right = get_wpmm_theme_option('dropdown_menu_radius_top_right', $theme_id);
                                        if( ! $dropdown_menu_radius_top_right){
                                            $dropdown_menu_radius_top_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_radius_top_right]' value="<?php esc_attr_e( $dropdown_menu_radius_top_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_menu_radius_bottom_left = get_wpmm_theme_option('dropdown_menu_radius_bottom_left', $theme_id);
                                        if( ! $dropdown_menu_radius_bottom_left){
                                            $dropdown_menu_radius_bottom_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_radius_bottom_left]' value="<?php esc_attr_e( $dropdown_menu_radius_bottom_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropwodn_menu_radius_bottom_right = get_wpmm_theme_option('dropwodn_menu_radius_bottom_right', $theme_id);
                                        if( ! $dropwodn_menu_radius_bottom_right){
                                            $dropwodn_menu_radius_bottom_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropwodn_menu_radius_bottom_right]' value="<?php esc_attr_e( $dropwodn_menu_radius_bottom_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <p class="field-description"><?php esc_html_e('Set a border radius on the dropdown menu.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>
                                        <?php
                                        $dropdown_menu_border_width = get_wpmm_theme_option('dropdown_menu_border_width', $theme_id);
                                        if( ! $dropdown_menu_border_width){
                                            $dropdown_menu_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_menu_border_width]' value="<?php esc_attr_e( $dropdown_menu_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[dropdown_menu_border_type]">
                                            <?php
                                                $dropdown_menu_border_type = get_wpmm_theme_option('dropdown_menu_border_type', $theme_id);
                                            ?>
                                            <option value="none" <?php selected($dropdown_menu_border_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option> <option value="solid" <?php selected($dropdown_menu_border_type, 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($dropdown_menu_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($dropdown_menu_border_type, 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($dropdown_menu_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($dropdown_menu_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($dropdown_menu_border_type, 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($dropdown_menu_border_type, 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($dropdown_menu_border_type, 'outset') ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[dropdown_menu_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('dropdown_menu_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                    </table>

                </div>


                <div id="tabs-6">

                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Sub Menu Items', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Text', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font', 'wp-megamenu'); ?></span>

                                        <?php
                                        $google_fonts = wpmm_get_google_fonts();
                                        $top_lavel_saved_font = get_wpmm_theme_option('dropdown_submenu_item_text_font',
                                            $theme_id);
                                        ?>
                                        <select name="wpmm_theme_option[dropdown_submenu_item_text_font]" class="select2">
                                            <option value=""><?php esc_html_e('Select font', 'wp-megamenu'); ?></option>
                                            <?php
                                            foreach ($google_fonts as $font){
                                                $is_top_lavel_font_selected = ($top_lavel_saved_font === $font) ? ' selected="selected" ':'';
                                                echo '<option value="' . esc_attr( $font ) .' " ' . $is_top_lavel_font_selected . '>' . esc_html( $font ) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>

                                    <p class="field-description"><?php echo sprintf(__('%d google fonts available', 'wp-megamenu'), count($google_fonts)); ?></p>
                                </div>



                                <?php $dropdown_submenu_item_text_color = get_wpmm_theme_option('dropdown_submenu_item_text_color', $theme_id);
                                if ( ! $dropdown_submenu_item_text_color){
                                    $dropdown_submenu_item_text_color = 'inherit';
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_item_text_color]" value="<?php esc_attr_e( $dropdown_submenu_item_text_color ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <?php $dropdown_submenu_item_text_hover_color = get_wpmm_theme_option('dropdown_submenu_item_text_hover_color', $theme_id);
                                if ( ! $dropdown_submenu_item_text_hover_color){
                                    $dropdown_submenu_item_text_hover_color = 'inherit';
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Hover Color', 'wp-megamenu'); ?> </span>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_item_text_hover_color]" value="<?php esc_attr_e( $dropdown_submenu_item_text_hover_color ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_item_text_font_size = get_wpmm_theme_option('dropdown_submenu_item_text_font_size', $theme_id);
                                        if( ! $dropdown_submenu_item_text_font_size){
                                            $dropdown_submenu_item_text_font_size = '12px';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_item_text_font_size]" value="<?php esc_attr_e( $dropdown_submenu_item_text_font_size ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Font Weight', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_item_text_font_weight = get_wpmm_theme_option('dropdown_submenu_item_text_font_weight', $theme_id);
                                        if( ! $dropdown_submenu_item_text_font_weight){
                                            $dropdown_submenu_item_text_font_weight = 'normal';
                                        }
                                        ?>
                                        <select name="wpmm_theme_option[dropdown_submenu_item_text_font_weight]">
                                            <option value="normal" <?php selected($dropdown_submenu_item_text_font_weight, 'normal') ?> > <?php esc_html_e('Normal', 'wp-megamenu'); ?> </option>
                                            <option value="bold" <?php selected($dropdown_submenu_item_text_font_weight, 'bold') ?> > <?php esc_html_e('Bold', 'wp-megamenu'); ?> </option>
                                            <option value="bolder" <?php selected($dropdown_submenu_item_text_font_weight, 'bolder') ?> > <?php esc_html_e('Bolder', 'wp-megamenu'); ?> </option>
                                            <option value="lighter" <?php selected($dropdown_submenu_item_text_font_weight, 'lighter') ?> > <?php esc_html_e('Lighter', 'wp-megamenu'); ?> </option>
                                            <option value="100" <?php selected($dropdown_submenu_item_text_font_weight, '100') ?> > <?php esc_html_e('100', 'wp-megamenu'); ?> </option>
                                            <option value="200" <?php selected($dropdown_submenu_item_text_font_weight, '200') ?> > <?php esc_html_e('200', 'wp-megamenu'); ?> </option>
                                            <option value="300" <?php selected($dropdown_submenu_item_text_font_weight, '300') ?> > <?php esc_html_e('300', 'wp-megamenu'); ?> </option>
                                            <option value="400" <?php selected($dropdown_submenu_item_text_font_weight, '400') ?> > <?php esc_html_e('400', 'wp-megamenu'); ?> </option>
                                            <option value="500" <?php selected($dropdown_submenu_item_text_font_weight, '500') ?> > <?php esc_html_e('500', 'wp-megamenu'); ?> </option>
                                            <option value="600" <?php selected($dropdown_submenu_item_text_font_weight, '600') ?> > <?php esc_html_e('600', 'wp-megamenu'); ?> </option>
                                            <option value="700" <?php selected($dropdown_submenu_item_text_font_weight, '700') ?> > <?php esc_html_e('700', 'wp-megamenu'); ?> </option>
                                            <option value="800" <?php selected($dropdown_submenu_item_text_font_weight, '800') ?> > <?php esc_html_e('800', 'wp-megamenu'); ?> </option>
                                            <option value="900" <?php selected($dropdown_submenu_item_text_font_weight, '900') ?> > <?php esc_html_e('900', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Line Height', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_item_text_line_height = get_wpmm_theme_option('dropdown_submenu_item_text_line_height', $theme_id);
                                        if( ! $dropdown_submenu_item_text_line_height){
                                            $dropdown_submenu_item_text_line_height = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_item_text_line_height]" value="<?php esc_attr_e( $dropdown_submenu_item_text_line_height ); ?>" data-alpha="true" placeholder="0px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Text Transform', 'wp-megamenu'); ?> </span>
                                        <select name="wpmm_theme_option[dropdown_submenu_item_text_transform]">
                                            <?php
                                                $dropdown_submenu_item_text_transform = get_wpmm_theme_option('dropdown_submenu_item_text_transform', $theme_id);
                                            ?>
                                            <option value="none" <?php selected($dropdown_submenu_item_text_transform, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="inherit" <?php selected($dropdown_submenu_item_text_transform, 'inherit') ?> > <?php esc_html_e('Inherit', 'wp-megamenu'); ?> </option>
                                            <option value="uppercase" <?php selected($dropdown_submenu_item_text_transform, 'uppercase') ?> > <?php esc_html_e('Uppercase', 'wp-megamenu');
                                                ?> </option>
                                            <option value="lowercase" <?php selected($dropdown_submenu_item_text_transform, 'lowercase') ?> > <?php esc_html_e('Lowercase', 'wp-megamenu'); ?> </option>
                                            <option value="capitalize" <?php selected($dropdown_submenu_item_text_transform, 'capitalize') ?> > <?php esc_html_e('Capitalize', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Letter Spacing', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $dropdown_submenu_item_text_letter_spacing = get_wpmm_theme_option('dropdown_submenu_item_text_letter_spacing', $theme_id);
                                        if( ! $dropdown_submenu_item_text_letter_spacing){
                                            $dropdown_submenu_item_text_letter_spacing = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_item_text_letter_spacing]" value="<?php esc_attr_e( $dropdown_submenu_item_text_letter_spacing ); ?>" data-alpha="true" placeholder="0px" />
                                    </label>
                                </div>

                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('First Item', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $dropdown_submenu_first_item_text_color = get_wpmm_theme_option('dropdown_submenu_first_item_text_color', $theme_id);
                                if (!$dropdown_submenu_first_item_text_color) {
                                    $dropdown_submenu_first_item_text_color = $dropdown_submenu_item_text_color;
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_first_item_text_color]" value="<?php esc_attr_e( $dropdown_submenu_first_item_text_color ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <?php $dropdown_submenu_first_item_text_hover_color = get_wpmm_theme_option('dropdown_submenu_first_item_text_hover_color', $theme_id);
                                if (!$dropdown_submenu_first_item_text_hover_color) {
                                    $dropdown_submenu_first_item_text_hover_color = $dropdown_submenu_item_text_hover_color;
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Hover Color', 'wp-megamenu'); ?> </span>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_first_item_text_hover_color]" value="<?php esc_attr_e( $dropdown_submenu_first_item_text_hover_color ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_first_item_text_font_size = get_wpmm_theme_option('dropdown_submenu_first_item_text_font_size', $theme_id);
                                        if (!$dropdown_submenu_first_item_text_font_size) {
                                            $dropdown_submenu_first_item_text_font_size = '12px';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_first_item_text_font_size]" value="<?php esc_attr_e( $dropdown_submenu_first_item_text_font_size ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Font Weight', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_first_item_text_font_weight = get_wpmm_theme_option('dropdown_submenu_first_item_text_font_weight', $theme_id);
                                        if (!$dropdown_submenu_first_item_text_font_weight) {
                                            $dropdown_submenu_first_item_text_font_weight = 'normal';
                                        }
                                        ?>
                                        <select name="wpmm_theme_option[dropdown_submenu_first_item_text_font_weight]">
                                            <option value="normal" <?php selected($dropdown_submenu_first_item_text_font_weight, 'normal') ?> > <?php esc_html_e('Normal', 'wp-megamenu'); ?> </option>
                                            <option value="bold" <?php selected($dropdown_submenu_first_item_text_font_weight, 'bold') ?> > <?php esc_html_e('Bold', 'wp-megamenu'); ?> </option>
                                            <option value="bolder" <?php selected($dropdown_submenu_first_item_text_font_weight, 'bolder') ?> > <?php esc_html_e('Bolder', 'wp-megamenu'); ?> </option>
                                            <option value="lighter" <?php selected($dropdown_submenu_first_item_text_font_weight, 'lighter') ?> > <?php esc_html_e('Lighter', 'wp-megamenu'); ?> </option>
                                            <option value="100" <?php selected($dropdown_submenu_first_item_text_font_weight, '100') ?> > <?php esc_html_e('100', 'wp-megamenu'); ?> </option>
                                            <option value="200" <?php selected($dropdown_submenu_first_item_text_font_weight, '200') ?> > <?php esc_html_e('200', 'wp-megamenu'); ?> </option>
                                            <option value="300" <?php selected($dropdown_submenu_first_item_text_font_weight, '300') ?> > <?php esc_html_e('300', 'wp-megamenu'); ?> </option>
                                            <option value="400" <?php selected($dropdown_submenu_first_item_text_font_weight, '400') ?> > <?php esc_html_e('400', 'wp-megamenu'); ?> </option>
                                            <option value="500" <?php selected($dropdown_submenu_first_item_text_font_weight, '500') ?> > <?php esc_html_e('500', 'wp-megamenu'); ?> </option>
                                            <option value="600" <?php selected($dropdown_submenu_first_item_text_font_weight, '600') ?> > <?php esc_html_e('600', 'wp-megamenu'); ?> </option>
                                            <option value="700" <?php selected($dropdown_submenu_first_item_text_font_weight, '700') ?> > <?php esc_html_e('700', 'wp-megamenu'); ?> </option>
                                            <option value="800" <?php selected($dropdown_submenu_first_item_text_font_weight, '800') ?> > <?php esc_html_e('800', 'wp-megamenu'); ?> </option>
                                            <option value="900" <?php selected($dropdown_submenu_first_item_text_font_weight, '900') ?> > <?php esc_html_e('900', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Line Height', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $dropdown_submenu_first_item_text_line_height = get_wpmm_theme_option('dropdown_submenu_first_item_text_line_height', $theme_id);
                                        if (!$dropdown_submenu_first_item_text_line_height) {
                                            $dropdown_submenu_first_item_text_line_height = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_first_item_text_line_height]" value="<?php esc_attr_e( $dropdown_submenu_first_item_text_line_height ); ?>" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Text Transform', 'wp-megamenu'); ?> </span>
                                        <select name="wpmm_theme_option[dropdown_submenu_first_item_text_transform]">
                                            <?php $dropdown_submenu_first_item_text_transform =  get_wpmm_theme_option('dropdown_submenu_first_item_text_transform', $theme_id)?>
                                            <option value="none" <?php selected($dropdown_submenu_item_text_transform, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="inherit" <?php selected($dropdown_submenu_item_text_transform, 'inherit') ?> > <?php esc_html_e('Inherit', 'wp-megamenu'); ?> </option>
                                            <option value="uppercase" <?php selected($dropdown_submenu_first_item_text_transform, 'uppercase') ?> > <?php esc_html_e('Uppercase', 'wp-megamenu'); ?> </option>
                                            <option value="lowercase" <?php selected($dropdown_submenu_first_item_text_transform, 'lowercase') ?> > <?php esc_html_e('Lowercase', 'wp-megamenu'); ?> </option>
                                            <option value="capitalize" <?php selected($dropdown_submenu_first_item_text_transform, 'capitalize') ?> > <?php esc_html_e('Capitalize', 'wp-megamenu'); ?> </option>
                                        </sele ct>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Letter Spacing', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $dropdown_submenu_first_item_text_letter_spacing = get_wpmm_theme_option('dropdown_submenu_first_item_text_letter_spacing', $theme_id);
                                        if (!$dropdown_submenu_first_item_text_letter_spacing) {
                                            $dropdown_submenu_first_item_text_letter_spacing = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[dropdown_submenu_first_item_text_letter_spacing]" value="<?php esc_attr_e( $dropdown_submenu_first_item_text_letter_spacing ); ?>" data-alpha="true" />
                                    </label>
                                </div>

                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Background Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $dropdown_submenu_item_bg_color = get_wpmm_theme_option('dropdown_submenu_item_bg_color', $theme_id);
                                if ( ! $dropdown_submenu_item_bg_color){
                                    $dropdown_submenu_item_bg_color = 'inherit';
                                }
                                ?>

                                <input type="text" name="wpmm_theme_option[dropdown_submenu_item_bg_color]" value="<?php esc_attr_e( $dropdown_submenu_item_bg_color ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set item background color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Background Hover Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $dropdown_submenu_item_bg_hover_color = get_wpmm_theme_option('dropdown_submenu_item_bg_hover_color', $theme_id);
                                if ( ! $dropdown_submenu_item_bg_hover_color){
                                    $dropdown_submenu_item_bg_hover_color = 'inherit';
                                }
                                ?>

                                <input type="text" name="wpmm_theme_option[dropdown_submenu_item_bg_hover_color]" value="<?php esc_attr_e( $dropdown_submenu_item_bg_hover_color ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set item background hover color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_padding_top = get_wpmm_theme_option('dropdown_submenu_item_padding_top', $theme_id);
                                        if( ! $dropdown_submenu_item_padding_top){
                                            $dropdown_submenu_item_padding_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_padding_top]' value="<?php esc_attr_e( $dropdown_submenu_item_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_padding_right = get_wpmm_theme_option('dropdown_submenu_item_padding_right', $theme_id);
                                        if( ! $dropdown_submenu_item_padding_right){
                                            $dropdown_submenu_item_padding_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_padding_right]' value="<?php esc_attr_e( $dropdown_submenu_item_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_padding_bottom = get_wpmm_theme_option('dropdown_submenu_item_padding_bottom', $theme_id);
                                        if( ! $dropdown_submenu_item_padding_bottom){
                                            $dropdown_submenu_item_padding_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_padding_bottom]' value="<?php esc_attr_e( $dropdown_submenu_item_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_padding_left = get_wpmm_theme_option('dropdown_submenu_item_padding_left', $theme_id);
                                        if( ! $dropdown_submenu_item_padding_left){
                                            $dropdown_submenu_item_padding_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_padding_left]' value="<?php esc_attr_e( $dropdown_submenu_item_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_margin_top = get_wpmm_theme_option('dropdown_submenu_item_margin_top', $theme_id);
                                        if( ! $dropdown_submenu_item_margin_top){
                                            $dropdown_submenu_item_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_margin_top]' value="<?php esc_attr_e( $dropdown_submenu_item_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_margin_right = get_wpmm_theme_option('dropdown_submenu_item_margin_right', $theme_id);
                                        if( ! $dropdown_submenu_item_margin_right){
                                            $dropdown_submenu_item_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_margin_right]' value="<?php esc_attr_e( $dropdown_submenu_item_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_margin_bottom = get_wpmm_theme_option('dropdown_submenu_item_margin_bottom', $theme_id);
                                        if( ! $dropdown_submenu_item_margin_bottom){
                                            $dropdown_submenu_item_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_margin_bottom]' value="<?php esc_attr_e( $dropdown_submenu_item_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_margin_left = get_wpmm_theme_option('dropdown_submenu_item_margin_left', $theme_id);
                                        if( ! $dropdown_submenu_item_margin_left){
                                            $dropdown_submenu_item_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_margin_left]' value="<?php esc_attr_e( $dropdown_submenu_item_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>
                                        <?php
                                        $dropdown_submenu_item_border_width = get_wpmm_theme_option('dropdown_submenu_item_border_width', $theme_id);
                                        if( ! $dropdown_submenu_item_border_width){
                                            $dropdown_submenu_item_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_border_width]' value="<?php esc_attr_e( $dropdown_submenu_item_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[dropdown_submenu_item_border_type]">
                                            <?php $dropdown_submenu_item_border_type =  get_wpmm_theme_option('dropdown_submenu_item_border_type', $theme_id); ?>
                                            <option value="none" <?php selected($dropdown_submenu_item_border_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($dropdown_submenu_item_border_type, 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($dropdown_submenu_item_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($dropdown_submenu_item_border_type, 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($dropdown_submenu_item_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($dropdown_submenu_item_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($dropdown_submenu_item_border_type, 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($dropdown_submenu_item_border_type, 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($dropdown_submenu_item_border_type, 'outset') ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[dropdown_submenu_item_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('dropdown_submenu_item_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Border Hover', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $dropdown_submenu_item_hover_border_width = get_wpmm_theme_option('dropdown_submenu_item_hover_border_width',
                                            $theme_id);
                                        if( ! $dropdown_submenu_item_hover_border_width){
                                            $dropdown_submenu_item_hover_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[dropdown_submenu_item_hover_border_width]' value="<?php esc_attr_e( $dropdown_submenu_item_hover_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[dropdown_submenu_item_hover_border_type]">
                                            <?php $dropdown_submenu_item_hover_border_type =  get_wpmm_theme_option('dropdown_submenu_item_hover_border_type', $theme_id); ?>
                                            <option value="none" <?php selected($dropdown_submenu_item_hover_border_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($dropdown_submenu_item_hover_border_type, 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($dropdown_submenu_item_hover_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($dropdown_submenu_item_hover_border_type, 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($dropdown_submenu_item_hover_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($dropdown_submenu_item_hover_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($dropdown_submenu_item_hover_border_type, 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($dropdown_submenu_item_hover_border_type, 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($dropdown_submenu_item_hover_border_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[dropdown_submenu_item_hover_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('dropdown_submenu_item_hover_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set hover border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('First Item Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_first_item_margin_top = get_wpmm_theme_option('widgets_first_item_margin_top',
                                            $theme_id);
                                        if( ! $widgets_first_item_margin_top){
                                            $widgets_first_item_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_first_item_margin_top]' value="<?php esc_attr_e( $widgets_first_item_margin_top ); ?>" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_first_item_margin_right = get_wpmm_theme_option('widgets_first_item_margin_right',
                                            $theme_id);
                                        if( ! $widgets_first_item_margin_right){
                                            $widgets_first_item_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_first_item_margin_right]' value="<?php esc_attr_e( $widgets_first_item_margin_right ); ?>" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_first_item_margin_bottom = get_wpmm_theme_option('widgets_first_item_margin_bottom',
                                            $theme_id);
                                        if( ! $widgets_first_item_margin_bottom){
                                            $widgets_first_item_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_first_item_margin_bottom]' value="<?php esc_attr_e( $widgets_first_item_margin_bottom ); ?>" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_first_item_margin_left = get_wpmm_theme_option('widgets_first_item_margin_left',
                                            $theme_id);
                                        if( ! $widgets_first_item_margin_left){
                                            $widgets_first_item_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_first_item_margin_left]' value="<?php esc_attr_e( $widgets_first_item_margin_left ); ?>" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin to widgets.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('First Item Border Separator', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[submenu_first_item_border_separator_width]' value="<?php esc_attr_e( get_wpmm_theme_option('submenu_first_item_border_separator_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[submenu_first_item_border_separator_type]">
                                            <?php $submenu_first_item_border_separator_type =  get_wpmm_theme_option('submenu_first_item_border_separator_type', $theme_id); ?>
                                         <option value="none" <?php selected($submenu_first_item_border_separator_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($submenu_first_item_border_separator_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($submenu_first_item_border_separator_type, 'dashed'); ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($submenu_first_item_border_separator_type, 'dotted'); ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($submenu_first_item_border_separator_type, 'double'); ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($submenu_first_item_border_separator_type, 'groove'); ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($submenu_first_item_border_separator_type, 'ridge'); ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($submenu_first_item_border_separator_type, 'inset'); ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($submenu_first_item_border_separator_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[submenu_first_item_border_separator_color]" value="<?php esc_attr_e( get_wpmm_theme_option('submenu_first_item_border_separator_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border separator width and color, ex. <strong>1px solid #dddddd</strong>', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <!-- Icon Color -->
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Icon Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[submenu_first_item_icon_color]" value="<?php esc_attr_e( get_wpmm_theme_option('submenu_first_item_icon_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set Color, ex. <strong>#dddddd</strong>', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <!-- Icon Font Size -->
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Icon Font Size', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <label>
                                    <p><?php esc_html_e('Font Size', 'wp-megamenu'); ?></p>
                                    <input type='text' name='wpmm_theme_option[submenu_first_item_icon_fontsize]' value="<?php esc_attr_e( get_wpmm_theme_option('submenu_first_item_icon_fontsize', $theme_id) ); ?>" placeholder="16px" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set Font Size, ex. <strong>16px</strong>', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-7">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Mega Menu', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Background Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[megamenu_bg_color]" value="<?php esc_attr_e( get_wpmm_theme_option('megamenu_bg_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set Mega Menu background color.', 'wp-megamenu');
                                    ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Mega Menu Width', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <?php
                                $mega_menu_width = get_wpmm_theme_option('mega_menu_width',
                                    $theme_id);
                                if( ! $mega_menu_width){
                                    $mega_menu_width = '100%';
                                }
                                ?>
                                <input type="text" name="wpmm_theme_option[mega_menu_width]" value="<?php esc_attr_e( $mega_menu_width ); ?>" placeholder="0px" />
                                <p class="field-description"><?php esc_html_e('Set the width of Mega Menu, ex: 100% or 800px', 'wp-megamenu');
                                    ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Inner Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_padding_top = get_wpmm_theme_option('megamenu_padding_top',
                                            $theme_id);
                                        if( ! $megamenu_padding_top){
                                            $megamenu_padding_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_padding_top]'
                                               value="<?php esc_attr_e( $megamenu_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_padding_right = get_wpmm_theme_option('megamenu_padding_right',
                                            $theme_id);
                                        if( ! $megamenu_padding_right){
                                            $megamenu_padding_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_padding_right]'
                                               value="<?php esc_attr_e( $megamenu_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_padding_bottom = get_wpmm_theme_option('megamenu_padding_bottom',
                                            $theme_id);
                                        if( ! $megamenu_padding_bottom){
                                            $megamenu_padding_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_padding_bottom]'
                                               value="<?php esc_attr_e( $megamenu_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_padding_left = get_wpmm_theme_option('megamenu_padding_left',
                                            $theme_id);
                                        if( ! $megamenu_padding_left){
                                            $megamenu_padding_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_padding_left]'
                                               value="<?php esc_attr_e( $megamenu_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border Radius', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_border_radius_top_left = get_wpmm_theme_option('megamenu_border_radius_top_left',
                                            $theme_id);
                                        if( ! $megamenu_border_radius_top_left){
                                            $megamenu_border_radius_top_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_border_radius_top_left]' value="<?php esc_attr_e( $megamenu_border_radius_top_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_radius_top_right = get_wpmm_theme_option('megamenu_radius_top_right',
                                            $theme_id);
                                        if( ! $megamenu_radius_top_right){
                                            $megamenu_radius_top_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_radius_top_right]' value="<?php esc_attr_e( $megamenu_radius_top_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_radius_bottom_left = get_wpmm_theme_option('megamenu_radius_bottom_left',
                                            $theme_id);
                                        if( ! $megamenu_radius_bottom_left){
                                            $megamenu_radius_bottom_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_radius_bottom_left]' value="<?php esc_attr_e( $megamenu_radius_bottom_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_radius_bottom_right = get_wpmm_theme_option('megamenu_radius_bottom_right',
                                            $theme_id);
                                        if( ! $megamenu_radius_bottom_right){
                                            $megamenu_radius_bottom_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_radius_bottom_right]' value="<?php esc_attr_e( $megamenu_radius_bottom_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <p class="field-description"><?php esc_html_e('Set a border radius on the menu bar.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_menu_border_width = get_wpmm_theme_option('megamenu_menu_border_width',
                                            $theme_id);
                                        if( ! $megamenu_menu_border_width){
                                            $megamenu_menu_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_menu_border_width]' value="<?php esc_attr_e( $megamenu_menu_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[megamenu_menu_border_type]">
                                            <?php $megamenu_menu_border_type =  get_wpmm_theme_option('megamenu_menu_border_type', $theme_id); ?>
                                            <option value="none" <?php selected($megamenu_menu_border_type, 'none'); ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($megamenu_menu_border_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($megamenu_menu_border_type, 'dashed'); ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($megamenu_menu_border_type, 'dotted'); ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($megamenu_menu_border_type, 'double'); ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($megamenu_menu_border_type, 'groove'); ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($megamenu_menu_border_type, 'ridge'); ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($megamenu_menu_border_type, 'inset'); ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($megamenu_menu_border_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[megamenu_menu_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('megamenu_menu_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border Separator', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[megamenu_menu_border_separator_width]' value="<?php esc_attr_e( get_wpmm_theme_option('megamenu_menu_border_separator_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[megamenu_menu_border_separator_type]">
                                            <?php $megamenu_menu_border_separator_type =  get_wpmm_theme_option('megamenu_menu_border_separator_type', $theme_id);?>
                                            <option value="solid" <?php selected($megamenu_menu_border_separator_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($megamenu_menu_border_separator_type, 'dashed'); ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($megamenu_menu_border_separator_type, 'dotted'); ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($megamenu_menu_border_separator_type, 'double'); ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($megamenu_menu_border_separator_type, 'groove'); ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($megamenu_menu_border_separator_type, 'ridge'); ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($megamenu_menu_border_separator_type, 'inset'); ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($megamenu_menu_border_separator_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[megamenu_menu_border_separator_color]" value="<?php esc_attr_e( get_wpmm_theme_option('megamenu_menu_border_separator_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border separator width and color, ex. <strong>1px solid #dddddd</strong>', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Box Shadow', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('h-shadow', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_boxshadow_top = get_wpmm_theme_option('megamenu_boxshadow_top',
                                            $theme_id);
                                        if( ! $megamenu_boxshadow_top){
                                            $megamenu_boxshadow_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_boxshadow_top]' value="<?php esc_attr_e( $megamenu_boxshadow_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('v-shadow', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_boxshadow_right = get_wpmm_theme_option('megamenu_boxshadow_right',
                                            $theme_id);
                                        if( ! $megamenu_boxshadow_right){
                                            $megamenu_boxshadow_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_boxshadow_right]'
                                               value="<?php esc_attr_e( $megamenu_boxshadow_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('blur', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_boxshadow_bottom = get_wpmm_theme_option('megamenu_boxshadow_bottom',
                                            $theme_id);
                                        if( ! $megamenu_boxshadow_bottom){
                                            $megamenu_boxshadow_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_boxshadow_bottom]' value="<?php esc_attr_e( $megamenu_boxshadow_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('spread', 'wp-megamenu'); ?></p>

                                        <?php
                                        $megamenu_boxshadow_left = get_wpmm_theme_option('megamenu_boxshadow_left',
                                            $theme_id);
                                        if( ! $megamenu_boxshadow_left){
                                            $megamenu_boxshadow_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[megamenu_boxshadow_left]'
                                               value="<?php esc_attr_e( $megamenu_boxshadow_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>
                                <input type="text" name="wpmm_theme_option[megamenu_boxshadow_color]" value="<?php esc_attr_e( get_wpmm_theme_option('megamenu_boxshadow_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set box shadow around Mega Menu.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tabs-8">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Widgets', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Heading Typography', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[widgets_heading_text_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widgets_heading_text_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Hover Color', 'wp-megamenu'); ?> </span>
                                        <input type="text" name="wpmm_theme_option[widgets_heading_text_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widgets_heading_text_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $widgets_heading_text_font_size = get_wpmm_theme_option('widgets_heading_text_font_size',
                                            $theme_id);
                                        if( ! $widgets_heading_text_font_size){
                                            $widgets_heading_text_font_size = '14px';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[widgets_heading_text_font_size]" value="<?php esc_attr_e( $widgets_heading_text_font_size ); ?>" data-alpha="true" placeholder="14px"/>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Font Weight', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $widgets_heading_text_font_weight = get_wpmm_theme_option('widgets_heading_text_font_weight',
                                            $theme_id);
                                        if( ! $widgets_heading_text_font_weight){
                                            $widgets_heading_text_font_weight = 'normal';
                                        }
                                        ?>
                                        <select name="wpmm_theme_option[widgets_heading_text_font_weight]">
                                            <option value="normal" <?php selected($widgets_heading_text_font_weight, 'normal') ?> > <?php esc_html_e('Normal', 'wp-megamenu'); ?> </option>
                                            <option value="bold" <?php selected($widgets_heading_text_font_weight, 'bold') ?> > <?php esc_html_e('Bold', 'wp-megamenu'); ?> </option>
                                            <option value="bolder" <?php selected($widgets_heading_text_font_weight, 'bolder') ?> > <?php esc_html_e('Bolder', 'wp-megamenu'); ?> </option>
                                            <option value="lighter" <?php selected($widgets_heading_text_font_weight, 'lighter') ?> > <?php esc_html_e('Lighter', 'wp-megamenu'); ?> </option>
                                            <option value="100" <?php selected($widgets_heading_text_font_weight, '100') ?> > <?php esc_html_e('100', 'wp-megamenu'); ?> </option>
                                            <option value="200" <?php selected($widgets_heading_text_font_weight, '200') ?> > <?php esc_html_e('200', 'wp-megamenu'); ?> </option>
                                            <option value="300" <?php selected($widgets_heading_text_font_weight, '300') ?> > <?php esc_html_e('300', 'wp-megamenu'); ?> </option>
                                            <option value="400" <?php selected($widgets_heading_text_font_weight, '400') ?> > <?php esc_html_e('400', 'wp-megamenu'); ?> </option>
                                            <option value="500" <?php selected($widgets_heading_text_font_weight, '500') ?> > <?php esc_html_e('500', 'wp-megamenu'); ?> </option>
                                            <option value="600" <?php selected($widgets_heading_text_font_weight, '600') ?> > <?php esc_html_e('600', 'wp-megamenu'); ?> </option>
                                            <option value="700" <?php selected($widgets_heading_text_font_weight, '700') ?> > <?php esc_html_e('700', 'wp-megamenu'); ?> </option>
                                            <option value="800" <?php selected($widgets_heading_text_font_weight, '800') ?> > <?php esc_html_e('800', 'wp-megamenu'); ?> </option>
                                            <option value="900" <?php selected($widgets_heading_text_font_weight, '900') ?> > <?php esc_html_e('900', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Line Height', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $widgets_heading_text_line_height = get_wpmm_theme_option('widgets_heading_text_line_height',
                                            $theme_id);
                                        if( ! $widgets_heading_text_line_height){
                                            $widgets_heading_text_line_height = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[widgets_heading_text_line_height]" value="<?php esc_attr_e( $widgets_heading_text_line_height ); ?>" data-alpha="true" placeholder="24px"/>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Text Transform', 'wp-megamenu'); ?> </span>
                                        <select name="wpmm_theme_option[widgets_heading_text_transform]">
                                            <?php $widgets_heading_text_transform =  get_wpmm_theme_option('widgets_heading_text_transform', $theme_id);?>
                                            <option value="none" <?php selected($widgets_heading_text_transform, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="inherit" <?php selected($widgets_heading_text_transform, 'inherit') ?> > <?php esc_html_e('Inherit', 'wp-megamenu'); ?> </option>
                                            <option value="uppercase" <?php selected($widgets_heading_text_transform, 'uppercase') ?> > <?php esc_html_e('Uppercase', 'wp-megamenu'); ?> </option>
                                            <option value="lowercase" <?php selected($widgets_heading_text_transform, 'lowercase') ?> > <?php esc_html_e('Lowercase', 'wp-megamenu'); ?> </option>
                                            <option value="capitalize" <?php selected($widgets_heading_text_transform, 'capitalize') ?> > <?php esc_html_e('Capitalize', 'wp-megamenu'); ?> </option>
                                        </select>

                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Letter Spacing', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $widgets_heading_text_letter_spacing = get_wpmm_theme_option('widgets_heading_text_letter_spacing',
                                            $theme_id);
                                        if( ! $widgets_heading_text_letter_spacing){
                                            $widgets_heading_text_letter_spacing = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[widgets_heading_text_letter_spacing]" value="<?php esc_attr_e( $widgets_heading_text_letter_spacing ); ?>" data-alpha="true" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[widget_first_item_border_separator_width]' value="<?php esc_attr_e( get_wpmm_theme_option('widget_first_item_border_separator_width', $theme_id) ); ?>" placeholder="1px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[widget_first_item_border_separator_type]">
                                            <?php $widget_first_item_border_separator_type =  get_wpmm_theme_option('widget_first_item_border_separator_type', $theme_id);?>
                                         <option value="none" <?php selected($widget_first_item_border_separator_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($widget_first_item_border_separator_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($widget_first_item_border_separator_type, 'dashed'); ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($widget_first_item_border_separator_type, 'dotted'); ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($widget_first_item_border_separator_type, 'double'); ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($widget_first_item_border_separator_type, 'groove'); ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($widget_first_item_border_separator_type, 'ridge'); ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($widget_first_item_border_separator_type, 'inset'); ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($widget_first_item_border_separator_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[widget_first_item_border_separator_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widget_first_item_border_separator_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border separator width and color, ex. <strong>1px solid #dddddd</strong>', 'wp-megamenu'); ?></p>


                                  <div class="wpmm_theme_arrow_segment">
                                        <label>
                                            <p><?php esc_html_e('Margin Top', 'wp-megamenu'); ?></p>

                                            <?php
                                            $widgets_heading_margin_top = get_wpmm_theme_option('widgets_heading_margin_top',
                                                $theme_id);
                                            if( ! $widgets_heading_margin_top){
                                                $widgets_heading_margin_top = '';
                                            }
                                            ?>
                                            <input type='text' name='wpmm_theme_option[widgets_heading_margin_top]' value="<?php
                                            esc_attr_e( $widgets_heading_margin_top ); ?>" placeholder="0px" />
                                        </label>
                                    </div>

                                    <div class="wpmm_theme_arrow_segment">
                                        <label>
                                            <p><?php esc_html_e('Margin Right', 'wp-megamenu'); ?></p>

                                            <?php
                                            $widgets_heading_margin_right = get_wpmm_theme_option('widgets_heading_margin_right',
                                                $theme_id);
                                            if( ! $widgets_heading_margin_right){
                                                $widgets_heading_margin_right = '';
                                            }
                                            ?>
                                            <input type='text' name='wpmm_theme_option[widgets_heading_margin_right]'
                                                   value="<?php esc_attr_e( $widgets_heading_margin_right ); ?>" placeholder="0px" />
                                        </label>
                                    </div>

                                    <div class="wpmm_theme_arrow_segment">
                                        <label>
                                            <p><?php esc_html_e('Margin Bottom', 'wp-megamenu'); ?></p>
                                            <?php
                                            $widgets_heading_margin_bottom = get_wpmm_theme_option('widgets_heading_margin_bottom',
                                                $theme_id);
                                            if( ! $widgets_heading_margin_bottom){
                                                $widgets_heading_margin_bottom = '';
                                            }
                                            ?>
                                            <input type='text' name='wpmm_theme_option[widgets_heading_margin_bottom]'
                                                   value="<?php esc_attr_e( $widgets_heading_margin_bottom ); ?>" placeholder="0px" />
                                        </label>
                                    </div>

                                    <div class="wpmm_theme_arrow_segment">
                                        <label>
                                            <p><?php esc_html_e('Margin Left', 'wp-megamenu'); ?></p>


                                            <?php
                                            $widgets_heading_margin_left = get_wpmm_theme_option('widgets_heading_margin_left',
                                                $theme_id);
                                            if( ! $widgets_heading_margin_left){
                                                $widgets_heading_margin_left = '';
                                            }
                                            ?>
                                            <input type='text' name='wpmm_theme_option[widgets_heading_margin_left]' value="<?php esc_attr_e( $widgets_heading_margin_left ); ?>" placeholder="0px" />
                                        </label>
                                    </div>

                                    <p class="field-description"><?php esc_html_e('Set margin to widgets.', 'wp-megamenu'); ?></p>


                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_padding_top = get_wpmm_theme_option('widgets_padding_top',
                                            $theme_id);
                                        if( ! $widgets_padding_top){
                                            $widgets_padding_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_padding_top]' value="<?php
                                        esc_attr_e( $widgets_padding_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_padding_right = get_wpmm_theme_option('widgets_padding_right',
                                            $theme_id);
                                        if( ! $widgets_padding_right){
                                            $widgets_padding_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_padding_right]'
                                               value="<?php esc_attr_e( $widgets_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>
                                        <?php
                                        $widgets_padding_bottom = get_wpmm_theme_option('widgets_padding_bottom',
                                            $theme_id);
                                        if( ! $widgets_padding_bottom){
                                            $widgets_padding_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_padding_bottom]'
                                               value="<?php esc_attr_e( $widgets_padding_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>


                                        <?php
                                        $widgets_padding_left = get_wpmm_theme_option('widgets_padding_left',
                                            $theme_id);
                                        if( ! $widgets_padding_left){
                                            $widgets_padding_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_padding_left]' value="<?php esc_attr_e( $widgets_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding to widgets.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_margin_top = get_wpmm_theme_option('widgets_margin_top',
                                            $theme_id);
                                        if( ! $widgets_margin_top){
                                            $widgets_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_margin_top]' value="<?php
                                        esc_attr_e( $widgets_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_margin_right = get_wpmm_theme_option('widgets_margin_right',
                                            $theme_id);
                                        if( ! $widgets_margin_right){
                                            $widgets_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_margin_right]' value="<?php esc_attr_e( $widgets_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_margin_bottom = get_wpmm_theme_option('widgets_margin_bottom',
                                            $theme_id);
                                        if( ! $widgets_margin_bottom){
                                            $widgets_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_margin_bottom]' value="<?php esc_attr_e( $widgets_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_margin_left = get_wpmm_theme_option('widgets_margin_left',
                                            $theme_id);
                                        if( ! $widgets_margin_left){
                                            $widgets_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_margin_left]' value="<?php esc_attr_e( $widgets_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin to widgets.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Border', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_border_width = get_wpmm_theme_option('widgets_border_width',
                                            $theme_id);
                                        if( ! $widgets_border_width){
                                            $widgets_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_border_width]' value="<?php esc_attr_e( $widgets_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[widgets_border_type]">
                                            <?php $widgets_border_type = get_wpmm_theme_option('widgets_border_type', $theme_id); ?>
                                            <option value="none" <?php selected($widgets_border_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($widgets_border_type, 'solid') ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed"  <?php selected($widgets_border_type, 'dashed') ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($widgets_border_type, 'dotted') ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($widgets_border_type, 'double') ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($widgets_border_type, 'groove') ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($widgets_border_type, 'ridge') ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($widgets_border_type, 'inset') ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($widgets_border_type, 'outset') ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[widgets_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widgets_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Item Border Hover', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Width', 'wp-megamenu'); ?></p>

                                        <?php
                                        $widgets_hover_border_width = get_wpmm_theme_option('widgets_hover_border_width', $theme_id);
                                        if( ! $widgets_hover_border_width){
                                            $widgets_hover_border_width = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[widgets_hover_border_width]' value="<?php esc_attr_e( $widgets_hover_border_width ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Type', 'wp-megamenu'); ?></p>
                                        <select name="wpmm_theme_option[widgets_hover_border_type]">
                                            <?php
                                                $widgets_hover_border_type = get_wpmm_theme_option('widgets_hover_border_type', $theme_id);
                                            ?>
                                            <option value="none" <?php selected($widgets_hover_border_type, 'none'); ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option>
                                            <option value="solid" <?php selected($widgets_hover_border_type, 'solid'); ?> > <?php esc_html_e('Solid', 'wp-megamenu'); ?> </option>
                                            <option value="dashed" <?php selected($widgets_hover_border_type, 'dashed'); ?> > <?php esc_html_e('Dashed', 'wp-megamenu'); ?> </option>
                                            <option value="dotted" <?php selected($widgets_hover_border_type, 'dotted'); ?> > <?php esc_html_e('Dotted', 'wp-megamenu'); ?> </option>
                                            <option value="double" <?php selected($widgets_hover_border_type, 'double'); ?> > <?php esc_html_e('Double', 'wp-megamenu'); ?> </option>
                                            <option value="groove" <?php selected($widgets_hover_border_type, 'groove'); ?> > <?php esc_html_e('Groove', 'wp-megamenu'); ?> </option>
                                            <option value="ridge" <?php selected($widgets_hover_border_type, 'ridge'); ?> > <?php esc_html_e('Ridge', 'wp-megamenu'); ?> </option>
                                            <option value="inset" <?php selected($widgets_hover_border_type, 'inset'); ?> > <?php esc_html_e('Inset', 'wp-megamenu'); ?> </option>
                                            <option value="outset" <?php selected($widgets_hover_border_type, 'outset'); ?> > <?php esc_html_e('Outset', 'wp-megamenu'); ?> </option>
                                        </select>
                                    </label>
                                </div>
                                <label>
                                    <p><?php esc_html_e('Color', 'wp-megamenu'); ?></p>
                                    <input type="text" name="wpmm_theme_option[widgets_hover_border_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widgets_hover_border_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                </label>

                                <p class="field-description"> <?php esc_html_e('Set hover border width and color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Content Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[widgets_content_color]" value="<?php esc_attr_e( get_wpmm_theme_option('widgets_content_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"> <?php esc_html_e('Set widget content color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                    </table>


                </div>
                <div id="tabs-10">

                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Mobile Menu', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Toggle Button Settings', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <?php
                                $toggle_bar_alignment = get_wpmm_theme_option('toggle_bar_alignment', $theme_id);
                                if( ! $toggle_bar_alignment){
                                    $toggle_bar_alignment = 'right';
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Toggle Bar Align', 'wp-megamenu');
                                            ?></span>

                                        <select name="wpmm_theme_option[toggle_bar_alignment]">
                                            <option value="right" <?php selected($toggle_bar_alignment, 'right') ?> ><?php esc_html_e('Right') ?> </option>
                                            <option value="center" <?php selected($toggle_bar_alignment, 'center')
                                            ?>><?php esc_html_e('Center') ?> </option>
                                            <option value="left" <?php selected($toggle_bar_alignment, 'left') ?>><?php esc_html_e('Left') ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <?php $toggle_btn_open_text = get_wpmm_theme_option('toggle_btn_open_text', $theme_id); ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Menu Text', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_btn_open_text]" value="<?php esc_attr_e( $toggle_btn_open_text ); ?>" />
                                    </label>
                                </div>

                                <?php
                                $toggle_bar_close_icon = get_wpmm_theme_option('toggle_bar_close_icon', $theme_id);
                                if( ! $toggle_bar_close_icon){
                                    $toggle_bar_close_icon = 'true';
                                }
                                ?>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Show Close Icon', 'wp-megamenu'); ?></span>
                                        <select name="wpmm_theme_option[toggle_bar_close_icon]">
                                            <option value="true" <?php selected($toggle_bar_close_icon, 'true') ?> ><?php esc_html_e('Yes') ?> </option>
                                            <option value="false" <?php selected($toggle_bar_close_icon, 'false') ?>><?php esc_html_e('No') ?> </option>
                                        </select>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Text Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_btn_text_color]" value="<?php esc_attr_e( get_wpmm_theme_option('toggle_btn_text_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Text Hover Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_btn_text_hover_color]"
                                               value="<?php esc_attr_e( get_wpmm_theme_option('toggle_btn_text_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">

                                        <?php
                                        $toggle_bar_font_size = get_wpmm_theme_option('toggle_bar_font_size', $theme_id);
                                        if( ! $toggle_bar_font_size){
                                            $toggle_bar_font_size = '25px';
                                        }
                                        ?>
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_bar_font_size]" value="<?php
                                        esc_attr_e( $toggle_bar_font_size ); ?>"  placeholder="16px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Margin top', 'wp-megamenu'); ?></p>

                                        <?php
                                        $togglebar_margin_top = get_wpmm_theme_option('togglebar_margin_top',
                                            $theme_id);
                                        if( ! $togglebar_margin_top){
                                            $togglebar_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[togglebar_margin_top]'
                                               value="<?php esc_attr_e( $togglebar_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Margin Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $togglebar_margin_right = get_wpmm_theme_option('togglebar_margin_right',
                                            $theme_id);
                                        if( ! $togglebar_margin_right){
                                            $togglebar_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[togglebar_margin_right]'
                                               value="<?php esc_attr_e( $togglebar_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Margin Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $togglebar_margin_bottom = get_wpmm_theme_option('togglebar_margin_bottom',
                                            $theme_id);
                                        if( ! $togglebar_margin_bottom){
                                            $togglebar_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[togglebar_margin_bottom]'
                                               value="<?php esc_attr_e( $togglebar_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>                                
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Margin Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $togglebar_margin_left = get_wpmm_theme_option('togglebar_margin_left',
                                            $theme_id);
                                        if( ! $togglebar_margin_left){
                                            $togglebar_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[togglebar_margin_left]'
                                               value="<?php esc_attr_e( $togglebar_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Background Color', 'wp-megamenu'); ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_bar_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('toggle_bar_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                        <p class="field-description"><?php esc_html_e('Set the background color for toggle bar.','wp-megamenu'); ?></p>
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Background Hover Color', 'wp-megamenu');
                                            ?></span>
                                        <input type="text" name="wpmm_theme_option[toggle_bar_hover_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('toggle_bar_hover_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                        <p class="field-description"><?php esc_html_e('Set the hover background color for toggle bar.','wp-megamenu'); ?></p>
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('First Level Item', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"><?php esc_html_e('Font Size', 'wp-megamenu'); ?> </span>
                                        <?php
                                        $mobile_item_text_font_size = get_wpmm_theme_option('mobile_item_text_font_size', $theme_id);
                                        if( ! $mobile_item_text_font_size){
                                            $mobile_item_text_font_size = '14px';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[mobile_item_text_font_size]" value="<?php esc_attr_e( $mobile_item_text_font_size ); ?>" data-alpha="true" placeholder="14px" />
                                    </label>
                                </div>
                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Font Weight', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $mobile_item_text_font_weight = get_wpmm_theme_option('mobile_item_text_font_weight', $theme_id);
                                        if( ! $mobile_item_text_font_weight){
                                            $mobile_item_text_font_weight = '400';
                                        }
                                        ?>
                                        <select name="wpmm_theme_option[mobile_item_text_font_weight]">
                                            <option value="normal" <?php selected($mobile_item_text_font_weight, 'normal') ?> > <?php esc_html_e('Normal', 'wp-megamenu'); ?> </option>
                                            <option value="bold" <?php selected($mobile_item_text_font_weight, 'bold') ?> > <?php esc_html_e('Bold', 'wp-megamenu'); ?> </option>
                                            <option value="bolder" <?php selected($mobile_item_text_font_weight, 'bolder') ?> > <?php esc_html_e('Bolder', 'wp-megamenu'); ?> </option>
                                            <option value="lighter" <?php selected($mobile_item_text_font_weight, 'lighter') ?> > <?php esc_html_e('Lighter', 'wp-megamenu'); ?> </option>
                                            <option value="100" <?php selected($mobile_item_text_font_weight, '100') ?> > <?php esc_html_e('100', 'wp-megamenu'); ?> </option>
                                            <option value="200" <?php selected($mobile_item_text_font_weight, '200') ?> > <?php esc_html_e('200', 'wp-megamenu'); ?> </option>
                                            <option value="300" <?php selected($mobile_item_text_font_weight, '300') ?> > <?php esc_html_e('300', 'wp-megamenu'); ?> </option>
                                            <option value="400" <?php selected($mobile_item_text_font_weight, '400') ?> > <?php esc_html_e('400', 'wp-megamenu'); ?> </option>
                                            <option value="500" <?php selected($mobile_item_text_font_weight, '500') ?> > <?php esc_html_e('500', 'wp-megamenu'); ?> </option>
                                            <option value="600" <?php selected($mobile_item_text_font_weight, '600') ?> > <?php esc_html_e('600', 'wp-megamenu'); ?> </option>
                                            <option value="700" <?php selected($mobile_item_text_font_weight, '700') ?> > <?php esc_html_e('700', 'wp-megamenu'); ?> </option>
                                            <option value="800" <?php selected($mobile_item_text_font_weight, '800') ?> > <?php esc_html_e('800', 'wp-megamenu'); ?> </option>
                                            <option value="900" <?php selected($mobile_item_text_font_weight, '900') ?> > <?php esc_html_e('900', 'wp-megamenu'); ?> </option>
                                        </select>

                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Line Height', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $mobile_item_text_line_height = get_wpmm_theme_option('mobile_item_text_line_height', $theme_id);
                                        if( ! $mobile_item_text_line_height){
                                            $mobile_item_text_line_height = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[mobile_item_text_line_height]" value="<?php esc_attr_e( $mobile_item_text_line_height ); ?>" data-alpha="true" placeholder="24px" />
                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Text Transform', 'wp-megamenu'); ?> </span>

                                        <select name="wpmm_theme_option[mobile_item_text_transform]">
                                            <?php $mobile_item_text_transform = get_wpmm_theme_option('mobile_item_text_transform', $theme_id); ?>
                                            <option value="uppercase" <?php selected($mobile_item_text_transform, 'uppercase') ?> > <?php esc_html_e('Uppercase', 'wp-megamenu'); ?> </option>
                                            <option value="lowercase" <?php selected($mobile_item_text_transform, 'lowercase') ?> > <?php esc_html_e('Lowercase', 'wp-megamenu'); ?> </option>
                                            <option value="capitalize" <?php selected($mobile_item_text_transform, 'capitalize') ?> > <?php esc_html_e('Capitalize', 'wp-megamenu'); ?> </option>
                                        </select>

                                    </label>
                                </div>

                                <div>
                                    <label class="text_and_input">
                                        <span class="wpmm_labe_text"> <?php esc_html_e('Letter Spacing', 'wp-megamenu'); ?> </span>

                                        <?php
                                        $mobile_item_text_letter_spacing = get_wpmm_theme_option('mobile_item_text_letter_spacing', $theme_id);
                                        if( ! $mobile_item_text_letter_spacing){
                                            $mobile_item_text_letter_spacing = '';
                                        }
                                        ?>
                                        <input type="text" name="wpmm_theme_option[mobile_item_text_letter_spacing]" value="<?php esc_attr_e( $mobile_item_text_letter_spacing ); ?>" data-alpha="true" placeholder="0px"/>
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu item Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[mobile_menu_item_color]" value="<?php esc_attr_e( get_wpmm_theme_option('mobile_menu_item_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set menu item color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Item Hover Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[mobile_menu_item_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('mobile_menu_item_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set menu item hover color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Item Padding', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <?php
                                        $mobile_menu_item_padding_top = get_wpmm_theme_option('mobile_menu_item_padding_top', $theme_id);
                                        if( ! $mobile_menu_item_padding_top){
                                            $mobile_menu_item_padding_top = '';
                                        }
                                        ?>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>
                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_padding_top]' value="<?php esc_attr_e( $mobile_menu_item_padding_top ); ?>" placeholder="5px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_padding_right = get_wpmm_theme_option('mobile_menu_item_padding_right', $theme_id);
                                        if( ! $mobile_menu_item_padding_right){
                                            $mobile_menu_item_padding_right = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_padding_right]' value="<?php esc_attr_e( $mobile_menu_item_padding_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_padding_bottom = get_wpmm_theme_option('mobile_menu_item_padding_bottom', $theme_id);
                                        if( ! $mobile_menu_item_padding_bottom){
                                            $mobile_menu_item_padding_bottom = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_padding_bottom]' value="<?php esc_attr_e( $mobile_menu_item_padding_bottom ); ?>" placeholder="5px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_padding_left = get_wpmm_theme_option('mobile_menu_item_padding_left', $theme_id);
                                        if( ! $mobile_menu_item_padding_left){
                                            $mobile_menu_item_padding_left = '';
                                        }
                                        ?>

                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_padding_left]' value="<?php esc_attr_e( $mobile_menu_item_padding_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set padding for mobile menu item.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Item Margin', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Top', 'wp-megamenu'); ?></p>
                                        <?php
                                        $mobile_menu_item_margin_top = get_wpmm_theme_option('mobile_menu_item_margin_top', $theme_id);
                                        if( ! $mobile_menu_item_margin_top){
                                            $mobile_menu_item_margin_top = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_margin_top]' value="<?php esc_attr_e( $mobile_menu_item_margin_top ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Right', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_margin_right = get_wpmm_theme_option('mobile_menu_item_margin_right', $theme_id);
                                        if( ! $mobile_menu_item_margin_right){
                                            $mobile_menu_item_margin_right = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_margin_right]' value="<?php esc_attr_e( $mobile_menu_item_margin_right ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Bottom', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_margin_bottom = get_wpmm_theme_option('mobile_menu_item_margin_bottom', $theme_id);
                                        if( ! $mobile_menu_item_margin_bottom){
                                            $mobile_menu_item_margin_bottom = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_margin_bottom]' value="<?php esc_attr_e( $mobile_menu_item_margin_bottom ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <div class="wpmm_theme_arrow_segment">
                                    <label>
                                        <p><?php esc_html_e('Left', 'wp-megamenu'); ?></p>

                                        <?php
                                        $mobile_menu_item_margin_left = get_wpmm_theme_option('mobile_menu_item_margin_left', $theme_id);
                                        if( ! $mobile_menu_item_margin_left){
                                            $mobile_menu_item_margin_left = '';
                                        }
                                        ?>
                                        <input type='text' name='wpmm_theme_option[mobile_menu_item_margin_left]' value="<?php esc_attr_e( $mobile_menu_item_margin_left ); ?>" placeholder="0px" />
                                    </label>
                                </div>

                                <p class="field-description"><?php esc_html_e('Set margin for mobile menu.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Background', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <table>
                                    <tr>

                                        <td>
                                            <input type="text" name="wpmm_theme_option[mobile_menu_bg]" value="<?php esc_attr_e( get_wpmm_theme_option('mobile_menu_bg', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set the menu background color.', 'wp-megamenu'); ?></p>
                                        </td>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[mobile_menu_bg_2]" value="<?php esc_attr_e( get_wpmm_theme_option('mobile_menu_bg_2', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                            <p class="field-description"><?php esc_html_e('Set the second color for gradient.', 'wp-megamenu'); ?></p>
                                        </td>


                                        <?php
                                            $mobile_menu_bg_gradient_angle = !empty(get_wpmm_theme_option('mobile_menu_bg_gradient_angle', $theme_id)) ? intval(get_wpmm_theme_option('mobile_menu_bg_gradient_angle', $theme_id)) : -90;
                                            if($mobile_menu_bg_gradient_angle >= 360 || $mobile_menu_bg_gradient_angle  <= -360){
                                                $mobile_menu_bg_gradient_angle = -90;
                                            }
                                        ?>
                                        <td>
                                            <input type="text" name="wpmm_theme_option[mobile_menu_bg_gradient_angle]" value="<?php esc_attr_e( $mobile_menu_bg_gradient_angle ); ?>" placeholder="-90" />
                                            <p class="field-description"><?php esc_html_e('Set Gradient angle, value must be -360-360', 'wp-megamenu'); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>

                </div>
                <div id="tabs-11">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Custom CSS and JavaScript', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Add a Class', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[wpmm_class]" value="<?php esc_attr_e( get_wpmm_theme_option('wpmm_class', $theme_id) ); ?>" />
                                <p class="field-description"><?php esc_html_e('This class will be added to the menu nav bar. Example: ', 'wp-megamenu');
                                    echo '<strong>'.htmlentities(' <nav class="wp-megamenu"></nav>').'</strong>'; ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Custom CSS', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <textarea name="wpmm_theme_option[custom_css]" rows="10" cols="50"><?php esc_html_e( get_wpmm_theme_option('custom_css', $theme_id) ); ?></textarea>
                                <p class="field-description"><?php esc_html_e('Implement custom CSS in the Mega Menu.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Custom JS', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <textarea name="wpmm_theme_option[custom_js]" rows="10" cols="50"><?php esc_html_e( get_wpmm_theme_option('custom_js', $theme_id) ); ?></textarea>
                                <p class="field-description"><?php esc_html_e('Implement custom JS in the Mega Menu.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="tabs-12">

                    <?php 
                        $wpmm_db_version = get_option('WPMM_VER');
                        if (version_compare($wpmm_db_version, '1.2.0', '>')) : // soical links for new version
                    ?>
                    <table class="form-table wpmm-option-table wpmm-main-setting-table wpmm-repeatable-group">

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Enable Social Links', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $enable_social_links = get_wpmm_theme_option('enable_social_links', $theme_id); ?>
                                <label> <input type='checkbox' name='wpmm_theme_option[enable_social_links]' value='true' <?php checked($enable_social_links, 'true'); ?> /> <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                </label>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Target', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <select name="wpmm_theme_option[social_links_target]" >
                                    <?php $social_links_target = get_wpmm_theme_option('social_links_target', $theme_id); ?>
                                    <option value="_blank" <?php selected($social_links_target, '_blank'); ?> ><?php esc_html_e('Open New Tab', 'wp-megamenu') ?></option>
                                    <option value="_self" <?php selected($social_links_target, '_self'); ?> ><?php esc_html_e('Open Self Tab', 'wp-megamenu') ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Social Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[social_color]" value="<?php esc_attr_e( get_wpmm_theme_option('social_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set social color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Social Hover Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[social_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('social_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set social hover color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>
                        <tr class="wpmm-field-group wpmm-repeatable-item">
                            <th>
                                <?php esc_html_e('Social Icons', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <div class="form_parent">
                                    <div class="form_item_heading">
                                        <label>
                                            <input type="text" value="Icon class:" readonly>
                                        </label>
                                        <label>
                                            <input type="text" value="Icon URL:" readonly>
                                        </label>
                                    </div>



                                    <?php 

                                    $wpmm_social_icon = get_wpmm_theme_option('social_icon', $theme_id);
                                    $icon_index = 0;
                                    if ($wpmm_social_icon && array_key_exists('icon', $wpmm_social_icon)) {
                                        foreach ($wpmm_social_icon['icon'] as $icon) {
                                            ?>
                                            <div class="form_item">
                                                <label>
                                                    <input 
                                                        type="text" 
                                                        name="wpmm_theme_option[social_icon][icon][]" 
                                                        placeholder="fa fa-facebook"
                                                        value="<?php esc_attr_e( $icon ); ?>"
                                                    />
                                                </label>
                                                <label>
                                                    <input 
                                                        type="text" 
                                                        name="wpmm_theme_option[social_icon][url][]" 
                                                        placeholder="https://fb.com/username" 
                                                        value="<?php esc_attr_e( $wpmm_social_icon['url'][$icon_index] ); ?>"
                                                    />
                                                </label>
                                                <a class="remove_item" href="#"><?php esc_html_e('Remove', 'wp-megamenu'); ?></a>
                                            </div>

                                        <?php
                                        $icon_index += 1;
                                    }
                                } else {

                                    ?>

                                            <div class="form_item">
                                                <label>
                                                    <input 
                                                        type="text" 
                                                        name="wpmm_theme_option[social_icon][icon][]" 
                                                        placeholder="fa fa-facebook"
                                                        value=""
                                                    />
                                                </label>
                                                <label>
                                                    <input 
                                                        type="text" 
                                                        name="wpmm_theme_option[social_icon][url][]" 
                                                        placeholder="https://fb.com/username" 
                                                        value=""
                                                    />
                                                </label>
                                                <a class="remove_item" href="#"><?php esc_html_e('Remove', 'wp-megamenu'); ?></a>
                                            </div>
                                        <?php

                                    }

                                    ?>
                                    <a href="#" class="add_item"><?php esc_html_e('Add More', 'wp-megamenu'); ?></a>

                                </div>
                                
                            </td>
                        </tr>
                    </table>
                <?php
                    else: // soical links for older version
                ?>

                    <!-- for old version -->
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Social Links', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Enable Social Links', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $enable_social_links =  get_wpmm_theme_option('enable_social_links', $theme_id); ?>
                                <label> <input type='checkbox' name='wpmm_theme_option[enable_social_links]' value='true' <?php checked($enable_social_links, 'true'); ?> /> <?php esc_html_e('Enable/Disable', 'wp-megamenu'); ?>
                                </label>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Target', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <select name="wpmm_theme_option[social_links_target]" >
                                    <?php $social_links_target = get_wpmm_theme_option('social_links_target', $theme_id); ?>
                                    <option value="_blank" <?php selected($social_links_target, '_blank'); ?> ><?php esc_html_e('Open New Tab', 'wp-megamenu') ?></option>
                                    <option value="_self" <?php selected($social_links_target, '_self'); ?> ><?php esc_html_e('Open Self Tab', 'wp-megamenu') ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Social Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[social_color]" value="<?php esc_attr_e( get_wpmm_theme_option('social_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set social color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Social Hover Color', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <input type="text" name="wpmm_theme_option[social_hover_color]" value="<?php esc_attr_e( get_wpmm_theme_option('social_hover_color', $theme_id) ); ?>" class="color-picker" data-alpha="true" />
                                <p class="field-description"><?php esc_html_e('Set social hover color.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Facebook', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-facebook"></i> <input type="text" name="wpmm_theme_option[social_links_facebook]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_facebook', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Twitter', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-twitter"></i> <input type="text" name="wpmm_theme_option[social_links_twitter]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_twitter', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                <?php esc_html_e('Google +', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-google-plus"></i> <input type="text" name="wpmm_theme_option[social_links_gplus]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_gplus', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Instagram', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-instagram"></i> <input type="text" name="wpmm_theme_option[social_links_instagram]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_instagram', $theme_id) ); ?>" />
                            </td>
                        </tr>                        

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Linked In', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-linkedin"></i> <input type="text" name="wpmm_theme_option[social_links_linkedin]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_linkedin', $theme_id) ); ?>" />
                            </td>
                        </tr>                        

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Pinterest', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-pinterest"></i> <input type="text" name="wpmm_theme_option[social_links_pinterest]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_pinterest', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Youtube', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-youtube"></i> <input type="text" name="wpmm_theme_option[social_links_youtube]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_youtube', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Dribbble', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-dribbble"></i><input type="text" name="wpmm_theme_option[social_links_dribbble]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_dribbble', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Behance', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-behance"></i><input type="text" name="wpmm_theme_option[social_links_behance]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_behance', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Digg', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-digg"></i><input type="text" name="wpmm_theme_option[social_links_digg]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_digg', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Vimeo', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-vimeo"></i><input type="text" name="wpmm_theme_option[social_links_vimeo]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_vimeo', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('stumbleupon', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-stumbleupon"></i><input type="text" name="wpmm_theme_option[social_links_stumbleupon]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_stumbleupon', $theme_id) ); ?>" />
                            </td>
                        </tr>                        

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Reddit', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-reddit"></i><input type="text" name="wpmm_theme_option[social_links_reddit]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_reddit', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Delicious', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-delicious"></i><input type="text" name="wpmm_theme_option[social_links_delicious]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_delicious', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Skype', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-skype"></i><input type="text" name="wpmm_theme_option[social_links_skype]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_skype', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Github', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-github"></i><input type="text" name="wpmm_theme_option[social_links_github]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_github', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('amazon', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-amazon"></i><input type="text" name="wpmm_theme_option[social_links_amazon]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_amazon', $theme_id) ); ?>" />
                            </td>
                        </tr>

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('WhatsApp', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-whatsapp"></i><input type="text" name="wpmm_theme_option[social_links_whatsapp]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_whatsapp', $theme_id) ); ?>" />
                            </td>
                        </tr>                        

                        <tr class=" wpmm-field-group">
                            <th>
                                    <?php esc_html_e('Soundcloud', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <i class="fa fa-soundcloud"></i><input type="text" name="wpmm_theme_option[social_links_soundcloud]" value="<?php esc_attr_e( get_wpmm_theme_option('social_links_soundcloud', $theme_id) ); ?>" />
                            </td>
                        </tr>

                    </table>
                    <!-- end for old version -->
                <?php
                    endif;
                ?>

                </div>
                <div id="tabs-13">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table animation-list">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Select Animation', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <td>
                                    <select name="wpmm_theme_option[animation_type]">
                                        <?php $animation_type = get_wpmm_theme_option('animation_type', $theme_id); ?>
                                        <option value="none" <?php selected($animation_type, 'none') ?> > <?php esc_html_e('None', 'wp-megamenu'); ?> </option> <option value="pulse" <?php selected($animation_type, 'pulse') ?> > <?php esc_html_e('Pulse', 'wp-megamenu'); ?> </option>
                                        <option value="fadein" <?php selected($animation_type, 'fadein') ?> > <?php esc_html_e('FadeIn', 'wp-megamenu'); ?> </option>
                                        <option value="fadeindown" <?php selected($animation_type, 'fadeindown') ?> > <?php esc_html_e('FadeInDown', 'wp-megamenu'); ?> </option>
                                        <option value="fadeinup" <?php selected($animation_type, 'fadeinup') ?> > <?php esc_html_e('FadeInUp', 'wp-megamenu'); ?> </option>
                                        <option value="ZoomIn" <?php selected($animation_type, 'ZoomIn') ?> > <?php esc_html_e('ZoomIn', 'wp-megamenu'); ?> </option>
                                        <option value="slideindown" <?php selected($animation_type, 'slideindown') ?> > <?php esc_html_e('SlideInDown', 'wp-megamenu'); ?> </option>
                                        <option value="slideinup" <?php selected($animation_type, 'slideinup') ?> > <?php esc_html_e('SlideInUp', 'wp-megamenu'); ?> </option>
                                        <option value="flipinx" <?php selected($animation_type, 'flipinx') ?> > <?php esc_html_e('FlipInX', 'wp-megamenu'); ?> </option>
                                    </select>
                            </td>
                        </tr>

                    </table>
                </div>


                <?php
                if ($theme_id){
                ?>
                <div id="tabs-14">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <tr class="wpmm-fields-header wpmm-field-group wpmm-table-divider">
                            <th colspan="2"> <?php esc_html_e('Export Current Mega Menu Theme', 'wp-megamenu'); ?> </th>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <a class="wpmm-btn wpmm-btn-primary export-wpmm-theme" data-theme_id="<?php esc_attr_e( $theme_id ); ?>" data-theme_name="<?php esc_attr_e( $wpmm_theme_title ); ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'export_wpmm_theme' ) ) ); ?>"> <?php esc_html_e('Export', 'wp-megamenu'); ?> </a>
                            </th>
                        </tr>

                    </table>
                </div>
                <?php } ?>
            </div>

            <div class="clear"></div>
        </div>

        <div class="wpmm-form-submit-button submit-btn-wrap btn-theme-setting-wrap">
            <?php
            if ($theme_id){
                echo '<input type="hidden" name="wpmm_theme_type" value="edit_theme" />';
                echo '<input type="hidden" name="wpmm_theme_id" value="'. esc_attr( $theme_id ) .'" />';
            }else{
                echo '<input type="hidden" name="wpmm_theme_type" value="new_theme" />';
            }
            ?>
            <?php wp_nonce_field( 'wpmmm_save_new_theme_action', 'wpmmm_save_new_theme_nonce_field' ) ?>
            <?php submit_button(); ?>
        </div>

    </div>

</form>