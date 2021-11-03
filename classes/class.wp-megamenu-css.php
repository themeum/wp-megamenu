<?php
require_once WPMM_DIR.'libs/vendor/autoload.php';
use MatthiasMullie\Minify;

/**
 * Class wp_megamenu
 */
if ( ! class_exists('wp_megamenu_css')) {

    class wp_megamenu_css{

        public $wpmm_nav_class;
        public $wpmm_nav_id;

        /**
         * @return wp_megamenu_css
         */
        public static function init(){
            $return = new self();
            return $return;
        }

        /**
         * wp_megamenu_base constructor.
         */
        public function __construct(){
            $this->wpmm_nav_class = ' wp-megamenu-wrap ';
            $this->wpmm_nav_id = 'wp-megamenu';

            add_action('wp_head', array($this, 'render_css'));
            add_action('wpmm_after_save_theme', array($this, 'css_file_saving_action'));
            add_action('wpmm_regenerate_css', array($this, 'css_file_saving_action'));

            //After update nav menu
            //add_action('wp_update_nav_menu', array($this, 'wp_update_nav_menu'), 10, 1);

            add_action( 'wp_enqueue_scripts', array( &$this, 'before_header' ), 9999 );
        }

        public function css(){
            global $wpdb;
            $nav_class = $this->wpmm_nav_class;
            $navbar_id = $this->wpmm_nav_id;

            //Get all integrated Nav
            //Placing backword compability
            if (version_compare(WPMM_VER, '1.1.1', '>')) {
                $wpmm_nav_locations = get_nav_menu_locations();;
            }else{
                $wpmm_nav_locations = get_wpmm_option('auto_intergration_menu');
            }

            $available_menus = wp_get_nav_menus();
            //die(print_row($auto_intergration_menu));
            //die(print_row($available_menus));

            $style = "";

            /**
             * @since: 1.1.7
             */
            //Hide Mobile Menu Css
            $disable_wpmm_on_mobile = get_wpmm_option( 'disable_wpmm_on_mobile' );
            if ($disable_wpmm_on_mobile == 'true'){
                $style .= ".wpmm-hide-mobile-menu{display:none} ";
            }
            //End @Since



            if (is_array($wpmm_nav_locations) && count($wpmm_nav_locations)) {
                foreach ( $wpmm_nav_locations as $nav_key => $nav_value ) {
                    if ( ! $nav_value){
                        continue;
                    }
                    //Removing menu locations menu from available menu
                    if ( is_array( $available_menus ) && count( $available_menus ) ) {
                        foreach ( $available_menus as $menu_key => $available_menu ) {
                            if ( $available_menu->term_id == $nav_value ) {
                                unset( $available_menus[ $menu_key ] );
                            }
                        }
                    }

                    if ( version_compare( WPMM_VER, '1.1.1', '>' ) ) {
                        $wp_nav_menu_object = wp_get_nav_menu_object( $nav_value );
                        $selected_theme_id  = wpmm_theme_by_selected_nav_id( $wp_nav_menu_object->term_id );
                    } else {
                        $nav_id             = (int) $wpmm_nav_locations[ $nav_key ];
                        $selected_nav_theme = get_term_meta( $nav_id, 'wpmm_nav_options', true );
                        $selected_nav_theme = maybe_unserialize( $selected_nav_theme );
                        $selected_theme_id  = null;
                        if ( ! empty( $selected_nav_theme ) ) {
                            $selected_theme_id = (int) $selected_nav_theme['theme_id'];
                        }
                    }

                    if ( $selected_theme_id ) {
                        $style .= $this->generateCss( $nav_key, $selected_theme_id );
                    }
                }
            }

            if (is_array($available_menus) && count($available_menus)) {
                foreach ($available_menus as $available_menu){
                    $selected_theme_id  = wpmm_theme_by_selected_nav_id( $available_menu->term_id );
                    if ($selected_theme_id){
                        $style .= $this->generateCss($available_menu->slug, $selected_theme_id);
                    }
                }
            }

            //Minify Now
            $minifier = new Minify\CSS();
            $minifier->add( $style );
            $style = $minifier->minify();

            return $style;
        }


        /**
         * @param $nav_key
         * @param $selected_theme_id
         *
         * @return string
         *
         * Main Generate Method
         */
        public function generateCss($nav_key, $selected_theme_id){

            $navbar_id = $this->wpmm_nav_id;

            $style = '';

            $theme = get_post( $selected_theme_id );
            $theme = $theme ? maybe_unserialize( $theme->post_content ) : $theme;

            //print_row($theme);
            $navbar_id = $navbar_id . '-' . $nav_key;

            // general and menu bar settings
            $style .= "#{$navbar_id}:hover { z-index: ".( (int) $theme['zindex'] + 9)."; }";

            $style .= "#{$navbar_id}{ z-index: {$theme['zindex']}; ";

            if ( ! empty( $theme['shadow_enable'] ) && $theme['shadow_enable'] == 'false' ) {

                if ( ! empty( $theme['top_shadow'] ) ) {
                    $top_shadow = wpmm_unit_to_int( $theme['top_shadow'] ) . "px";
                } else {
                    $top_shadow = 0;
                }

                if ( ! empty( $theme['right_shadow'] ) ) {
                    $right_shadow = wpmm_unit_to_int( $theme['right_shadow'] ) . "px";
                } else {
                    $right_shadow = 0;
                }

                if ( ! empty( $theme['bottom_shadow'] ) ) {
                    $bottom_shadow = wpmm_unit_to_int( $theme['bottom_shadow'] ) . "px";
                } else {
                    $bottom_shadow = 0;
                }

                if ( ! empty( $theme['left_shadow'] ) ) {
                    $left_shadow = wpmm_unit_to_int( $theme['left_shadow'] ) . "px";
                } else {
                    $left_shadow = 0;
                }

                $style .= "box-shadow: $top_shadow $right_shadow $bottom_shadow $left_shadow {$theme['wpmm_theme_shadow']} ;";
            }
            if ( ! empty( $theme['line_height'] ) ) {
                $style .= "line-height: " . wpmm_unit_to_int( $theme['line_height'] ) . "px; ";
            }
            if ( ! empty( $theme['menu_align'] ) ) {
                $style .= "text-align: {$theme['menu_align']};";
            }
            if ( ! empty( $theme['menubar_height'] ) ) {
                $style .= "height: " . wpmm_unit_to_int( $theme['menubar_height'] ) . "px; ";
            }

            // menubar background
            $menubar_bg = isset($theme['menubar_bg']) ? $theme['menubar_bg'] : '';
            $menubar_bg_2 = isset($theme['menubar_bg_2']) ? $theme['menubar_bg_2'] : '';

            $menubar_bg_gradient_angle = isset($theme['menubar_bg_gradient_angle']) && !empty($theme['menubar_bg_gradient_angle']) ? intval($theme['menubar_bg_gradient_angle']) : -90;

            if($menubar_bg_gradient_angle >= 360 || $menubar_bg_gradient_angle  <= -360){
                $menubar_bg_gradient_angle = -90;
            }

            if ( ! empty( $menubar_bg ) ) {
                $style .= "background-color: {$menubar_bg}; ";

                if(!empty($menubar_bg_2)){
                    $style .= "
                    background-image: linear-gradient({$menubar_bg_gradient_angle}deg, $menubar_bg, $menubar_bg_2);
                ";
                }
            }


            if ( ! empty( $theme['menu_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['menu_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['menu_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['menu_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['menu_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['menu_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['menu_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['menu_padding_left'] ) . "px; ";
            }

            if ( ! empty( $theme['menu_border_radius_top_left'] ) || ! empty( $theme['menu_radius_top_right'] ) || ! empty( $theme['menu_radius_bottom_left'] ) || ! empty( $theme['menu_radius_bottom_right'] ) ) {

                if ( ! empty( $theme['menu_border_radius_top_left'] ) ) {
                    $menu_border_radius_top_left = wpmm_unit_to_int( $theme['menu_border_radius_top_left'] ) . "px";
                } else {
                    $menu_border_radius_top_left = 0;
                }

                if ( ! empty( $theme['menu_radius_top_right'] ) ) {
                    $menu_radius_top_right = wpmm_unit_to_int( $theme['menu_radius_top_right'] ) . "px";
                } else {
                    $menu_radius_top_right = 0;
                }

                if ( ! empty( $theme['menu_radius_bottom_left'] ) ) {
                    $menu_radius_bottom_left = wpmm_unit_to_int( $theme['menu_radius_bottom_left'] ) . "px";
                } else {
                    $menu_radius_bottom_left = 0;
                }

                if ( ! empty( $theme['menu_radius_bottom_right'] ) ) {
                    $menu_radius_bottom_right = wpmm_unit_to_int( $theme['menu_radius_bottom_right'] ) . "px";
                } else {
                    $menu_radius_bottom_right = 0;
                }

                $style .= " border-radius: $menu_border_radius_top_left $menu_radius_top_right $menu_radius_bottom_left $menu_radius_bottom_right ;";
            }

            if ( ! empty( $theme['menubar_top_border_width'] ) && $theme['menu_border_type'] ) {

                if ( ! empty( $theme['menubar_top_border_width'] ) ) {
                    $menubar_top_border_width = wpmm_unit_to_int( $theme['menubar_top_border_width'] ) . "px";
                }
                $style .= " border-top: $menubar_top_border_width {$theme['menu_border_type']} {$theme['menu_border_color']}; ";
            }
            if ( ! empty( $theme['menubar_right_border_width'] ) && $theme['menu_border_type'] ) {

                if ( ! empty( $theme['menubar_right_border_width'] ) ) {
                    $menubar_right_border_width = wpmm_unit_to_int( $theme['menubar_right_border_width'] ) . "px";
                }
                $style .= " border-right: $menubar_right_border_width {$theme['menu_border_type']} {$theme['menu_border_color']}; ";
            }
            if ( ! empty( $theme['menubar_bottom_border_width'] ) && $theme['menu_border_type'] ) {

                if ( ! empty( $theme['menubar_bottom_border_width'] ) ) {
                    $menubar_bottom_border_width = wpmm_unit_to_int( $theme['menubar_bottom_border_width'] ) . "px";
                }
                $style .= " border-bottom: $menubar_bottom_border_width {$theme['menu_border_type']} {$theme['menu_border_color']}; ";
            }
            if ( ! empty( $theme['menubar_left_border_width'] ) && $theme['menu_border_type'] ) {

                if ( ! empty( $theme['menubar_left_border_width'] ) ) {
                    $menubar_left_border_width = wpmm_unit_to_int( $theme['menubar_left_border_width'] ) . "px";
                }
                $style .= " border-left: $menubar_left_border_width {$theme['menu_border_type']} {$theme['menu_border_color']}; ";
            }
            $style .= "}";

            $style .= "#{$navbar_id}.wpmm-sticky-wrap.wpmm-sticky {";

            // sticky background
            $sticky_bg = isset($theme['sticky_bg']) ? $theme['sticky_bg'] : '';
            $sticky_bg_2 = isset($theme['sticky_bg_2']) ? $theme['sticky_bg_2'] : '';
            $sticky_bg_gradient_angle = isset($theme['sticky_bg_gradient_angle']) && !empty($theme['sticky_bg_gradient_angle']) ? intval($theme['sticky_bg_gradient_angle']) : -90;
            if($sticky_bg_gradient_angle >= 360 || $sticky_bg_gradient_angle  <= -360){
                $sticky_bg_gradient_angle = -90;
            }
            if ( ! empty( $sticky_bg ) ) {
                $style .= "background-color: {$sticky_bg}; ";

                if(!empty($sticky_bg_2)){
                    $style .= "
                    background-image: linear-gradient({$sticky_bg_gradient_angle}deg, $sticky_bg, $sticky_bg_2);
                ";
                }
            }
            $style .= "}";


            $style .= ".wp-megamenu-wrap .form .submit { ";

                $sticky_bg = isset($theme['sticky_bg']) ? $theme['sticky_bg'] : '';
                if ( ! empty( $sticky_bg ) ) {
                    $style .= "background-color: {$sticky_bg}; ";
                }
            $style .= "}";


            //logo
            $style .= "#{$navbar_id} > .wpmm-nav-wrap .wpmm_brand_logo_wrap img{ ";

            if ( ! empty( $theme['logo_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['logo_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['logo_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['logo_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['logo_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['logo_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['logo_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['logo_margin_left'] ) . "px; ";
            }

            $style .= "}";

            // Search Color
            $style .= "#{$navbar_id} .wpmm-search-form input{ ";
            if ( ! empty( $theme['top_level_item_text_color'] ) ) {
                $style .= "color: {$theme['top_level_item_text_color']};";
            }
            $style .= "}";

            //First Level Menu Items
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li > a, .wp-megamenu-wrap .wpmm-nav-wrap > ul > li.wpmm-social-link.wpmm-social-link-search a, .wpmm-menu-cart .cart-icon .fa-shopping-cart, .wp-megamenu-wrap .form .submit, .wpmm-menu-cart span.cart-icon i { ";

            if ( ! empty( $theme['top_level_item_text_font'] ) ) {
                $style .= "font-family: '{$theme['top_level_item_text_font']}';";
            }

            if ( ! empty( $theme['top_level_item_text_color'] ) ) {
                $style .= "color: {$theme['top_level_item_text_color']};";
            }

            if ( ! empty( $theme['top_level_item_text_font_size'] ) ) {
                $style .= "font-size: " . wpmm_unit_to_int( $theme['top_level_item_text_font_size'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_text_font_weight'] ) ) {
                $style .= "font-weight: {$theme['top_level_item_text_font_weight']};";
            }
            if ( ! empty( $theme['top_level_item_text_line_height'] ) ) {
                $style .= "line-height:  " . wpmm_unit_to_int( $theme['top_level_item_text_line_height'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_text_transform'] ) ) {
                $style .= "text-transform: {$theme['top_level_item_text_transform']};";
            }
            if ( ! empty( $theme['top_level_item_text_letter_spacing'] ) ) {
                $style .= "letter-spacing: " . wpmm_unit_to_int( $theme['top_level_item_text_letter_spacing'] ) . "px; ";
            }

            // top level item background
            $top_level_item_bg_color = isset($theme['top_level_item_bg_color']) ? $theme['top_level_item_bg_color'] : '';
            $top_level_item_bg_color_2 = isset($theme['top_level_item_bg_color_2']) ? $theme['top_level_item_bg_color_2'] : '';
            $top_level_item_bg_color_gradient_angle = isset($theme['top_level_item_bg_color_gradient_angle']) && !empty($theme['top_level_item_bg_color_gradient_angle']) ? intval($theme['top_level_item_bg_color_gradient_angle']) : -90;

            if($top_level_item_bg_color_gradient_angle >= 360 || $top_level_item_bg_color_gradient_angle  <= -360){
                $top_level_item_bg_color_gradient_angle = -90;
            }

            if ( ! empty( $top_level_item_bg_color ) ) {
                $style .= "background-color: {$top_level_item_bg_color}; ";

                if(!empty($top_level_item_bg_color_2)){
                    $style .= "
                    background-image: linear-gradient({$top_level_item_bg_color_gradient_angle}deg, $top_level_item_bg_color, $top_level_item_bg_color_2);
                ";
                }
            }

            if ( ! empty( $theme['top_level_item_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['top_level_item_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['top_level_item_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['top_level_item_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['top_level_item_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['top_level_item_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['top_level_item_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['top_level_item_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['top_level_item_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['top_level_item_margin_left'] ) . "px; ";
            }

//          old code
//            if ( ! empty( $theme['top_level_item_border_width'] ) && $theme['top_level_item_border_type'] ) {
//
//                if ( ! empty( $theme['top_level_item_border_width'] ) ) {
//                    $top_level_item_border_width = wpmm_unit_to_int( $theme['top_level_item_border_width'] ) . "px";
//                }
//
//                $style .= "border: $top_level_item_border_width {$theme['top_level_item_border_type']} {$theme['top_level_item_border_color']}; ";
//            }
//
            // new code v2.1.3
            $top_level_item_border_type = isset($theme['top_level_item_border_type']) ? $theme['top_level_item_border_type'] : '';

            if(!empty($top_level_item_border_type)){
                $top_level_item_border_color = isset($theme['top_level_item_border_color']) ? $theme['top_level_item_border_color'] : '';
                $top_level_item_border_width = isset($theme['top_level_item_border_width']) ? wpmm_unit_to_int($theme['top_level_item_border_width']).'px' : '';
                $top_level_item_border_width_right = isset($theme['top_level_item_border_width_right']) ? wpmm_unit_to_int($theme['top_level_item_border_width_right']).'px' : '';
                $top_level_item_border_width_bottom = isset($theme['top_level_item_border_width_bottom']) ? wpmm_unit_to_int($theme['top_level_item_border_width_bottom']).'px' : '';
                $top_level_item_border_width_left = isset($theme['top_level_item_border_width_left']) ? wpmm_unit_to_int($theme['top_level_item_border_width_left']).'px' : '';
                if($top_level_item_border_width){
                    $style .= "border-top: $top_level_item_border_width $top_level_item_border_type $top_level_item_border_color;";
                }
                if($top_level_item_border_width_right){
                    $style .= "border-right: $top_level_item_border_width_right $top_level_item_border_type $top_level_item_border_color;";
                }
                if($top_level_item_border_width_left){
                    $style .= "border-left: $top_level_item_border_width_left $top_level_item_border_type $top_level_item_border_color;";
                }
                if($top_level_item_border_width_bottom){
                    $style .= "border-bottom: $top_level_item_border_width_bottom $top_level_item_border_type $top_level_item_border_color;";
                }

            }

            // end new code v2.1.3

            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li:hover > a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.current-menu-ancestor > a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.active > a{ ";
            $style .= "color: {$theme['top_level_item_text_hover_color']};";


            $top_level_item_bg_hover_color = isset($theme['top_level_item_bg_hover_color']) ? $theme['top_level_item_bg_hover_color'] : '';
            $top_level_item_bg_hover_color_2 = isset($theme['top_level_item_bg_hover_color_2']) ? $theme['top_level_item_bg_hover_color_2'] : '';

            $top_level_item_bg_hover_color_gradient_angle = isset($theme['top_level_item_bg_hover_color_gradient_angle']) && !empty($theme['top_level_item_bg_hover_color_gradient_angle']) ? intval($theme['top_level_item_bg_hover_color_gradient_angle']) : -90;

            if($top_level_item_bg_hover_color_gradient_angle >= 360 || $top_level_item_bg_hover_color_gradient_angle  <= -360){
                $top_level_item_bg_hover_color_gradient_angle = -90;
            }

            if ( ! empty( $top_level_item_bg_hover_color ) ) {
                $style .= "background-color: {$top_level_item_bg_hover_color}; ";

                if(!empty($top_level_item_bg_color_2)){
                    $style .= "
                    background-image: linear-gradient({$top_level_item_bg_hover_color_gradient_angle}deg, $top_level_item_bg_hover_color, $top_level_item_bg_hover_color_2);
                ";
                }
            }

            // new code v2.1.3
            $top_level_item_hover_border_type = isset($theme['top_level_item_hover_border_type']) ? $theme['top_level_item_hover_border_type'] : '';

            if(!empty($top_level_item_hover_border_type)){
                $top_level_item_hover_border_color = isset($theme['top_level_item_hover_border_color']) ? $theme['top_level_item_hover_border_color'] : '';
                $top_level_item_hover_border_width = isset($theme['top_level_item_border_width']) ? wpmm_unit_to_int($theme['top_level_item_border_width']).'px' : '';
                $top_level_item_hover_border_width_right = isset($theme['top_level_item_border_width_right']) ? wpmm_unit_to_int($theme['top_level_item_border_width_right']).'px' : '';
                $top_level_item_hover_border_width_bottom = isset($theme['top_level_item_border_width_bottom']) ? wpmm_unit_to_int($theme['top_level_item_border_width_bottom']).'px' : '';
                $top_level_item_hover_border_width_left = isset($theme['top_level_item_border_width_left']) ? wpmm_unit_to_int($theme['top_level_item_border_width_left']).'px' : '';
                if($top_level_item_hover_border_width){
                    $style .= "border-top: $top_level_item_hover_border_width $top_level_item_hover_border_type $top_level_item_hover_border_color;";
                }
                if($top_level_item_hover_border_width_right){
                    $style .= "border-right: $top_level_item_hover_border_width_right $top_level_item_hover_border_type $top_level_item_hover_border_color;";
                }
                if($top_level_item_hover_border_width_left){
                    $style .= "border-left: $top_level_item_hover_border_width_left $top_level_item_hover_border_type $top_level_item_hover_border_color;";
                }
                if($top_level_item_hover_border_width_bottom){
                    $style .= "border-bottom: $top_level_item_hover_border_width_bottom $top_level_item_hover_border_type $top_level_item_hover_border_color;";
                }

            }
            // end new code v2.1.3
            $style .= "}";


            # Woo - product
            $style .= ".wpmm-woo-cart .site-header-cart.menu { ";
                
                if ( ! empty( $theme['top_level_item_padding_bottom'] ) ) {
                    $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['top_level_item_padding_bottom'] ) . "px; ";
                }
            $style .= "}";


            if($theme['menu_align']  && $theme['menu_align'] == 'center'){

                $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu{display: inline-block;}";
            }

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.current-menu-ancestor > a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.active > a{ ";
            if ( ! empty( $theme['top_level_item_text_hover_color'] ) ) {
                $style .= "color: {$theme['top_level_item_text_hover_color']};";
            }
            $style .= "}";

            if ( ! empty( $theme['top_level_item_highlight_current_item'] ) && $theme['top_level_item_highlight_current_item'] ) {
                if ( $theme['top_level_item_highlight_current_item'] == 'false' ) {
                    $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.current-menu-item > a{ ";
                    if ( ! empty( $theme['top_level_item_text_hover_color'] ) ) {
                        $style .= "color: {$theme['top_level_item_text_hover_color']};";
                    }
                    if(!empty($theme['top_level_item_bg_hover_color'])){
                        $style .= "background-color: {$theme['top_level_item_bg_hover_color']};";
                    }
                    $style .= "}";
                }
            }

            /* Dropdown Menu */
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.wpmm_dropdown_menu  ul.wp-megamenu-sub-menu,
                    #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu  li.wpmm-type-widget .wp-megamenu-sub-menu li .wp-megamenu-sub-menu { ";
            if ( ! empty( $theme['dropdown_menu_width'] ) ) {
                $style .= "width: " . wpmm_unit_to_int( $theme['dropdown_menu_width'] ) . "px; ";
            }

            $dropdown_menu_bg = isset($theme['dropdown_menu_bg']) ? $theme['dropdown_menu_bg'] : '';
            $dropdown_menu_bg_2 = isset($theme['dropdown_menu_bg_2']) ? $theme['dropdown_menu_bg_2'] : '';

            $dropdown_menu_bg_gradient_angle = isset($theme['dropdown_menu_bg_gradient_angle']) && !empty($theme['dropdown_menu_bg_gradient_angle']) ? intval($theme['dropdown_menu_bg_gradient_angle']) : -90;

            if($dropdown_menu_bg_gradient_angle >= 360 || $dropdown_menu_bg_gradient_angle  <= -360){
                $dropdown_menu_bg_gradient_angle = -90;
            }

            if ( ! empty( $dropdown_menu_bg ) ) {
                $style .= "background-color: {$dropdown_menu_bg};";
                if(!empty($dropdown_menu_bg_2)){
                    $style .= "
                        background-image: linear-gradient({$dropdown_menu_bg_gradient_angle}deg, $dropdown_menu_bg , $dropdown_menu_bg_2);
                    ";
                }
            }

            if ( ! empty( $theme['dropdown_menu_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['dropdown_menu_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_menu_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['dropdown_menu_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_menu_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['dropdown_menu_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_menu_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['dropdown_menu_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_menu_border_width'] ) && $theme['dropdown_menu_border_type'] ) {
                if ( ! empty( $theme['dropdown_menu_border_width'] ) ) {
                    $dropdown_menu_border_width = wpmm_unit_to_int( $theme['dropdown_menu_border_width'] ) . "px";
                }
                $style .= "border: $dropdown_menu_border_width {$theme['dropdown_menu_border_type']} {$theme['dropdown_menu_border_color']}; ";
            }
            $style .= "}";


            # Submenu Border radius
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu>li.wpmm_mega_menu>ul.wp-megamenu-sub-menu{ ";
                if ( ! empty( $theme['dropdown_menu_border_radius_top_left'] ) ) {
                    $dropdown_menu_border_radius_top_left = wpmm_unit_to_int( $theme['dropdown_menu_border_radius_top_left'] ) . "px";
                } else {
                    $dropdown_menu_border_radius_top_left = 0;
                }

                if ( ! empty( $theme['dropdown_menu_radius_top_right'] ) ) {
                    $dropdown_menu_radius_top_right = wpmm_unit_to_int( $theme['dropdown_menu_radius_top_right'] ) . "px";
                } else {
                    $dropdown_menu_radius_top_right = 0;
                }

                if ( ! empty( $theme['dropdown_menu_radius_bottom_left'] ) ) {
                    $dropdown_menu_radius_bottom_left = wpmm_unit_to_int( $theme['dropdown_menu_radius_bottom_left'] ) . "px";
                } else {
                    $dropdown_menu_radius_bottom_left = 0;
                }

                if ( ! empty( $theme['dropwodn_menu_radius_bottom_right'] ) ) {
                    $dropwodn_menu_radius_bottom_right = wpmm_unit_to_int( $theme['dropwodn_menu_radius_bottom_right'] ) . "px";
                } else {
                    $dropwodn_menu_radius_bottom_right = 0;
                }
                $style .= " border-radius: $dropdown_menu_border_radius_top_left $dropdown_menu_radius_top_right $dropdown_menu_radius_bottom_left $dropwodn_menu_radius_bottom_right ";
            $style .= "}";

            // Submenu items # Submenu Font
            if ( ! empty( $theme['dropdown_submenu_item_text_font'] ) ) {
                $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h1, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h2, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h3, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h4, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h5,  #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu h6{ ";
                $style .= "font-family: '{$theme['dropdown_submenu_item_text_font']}' !important;";
                $style .= "}";
            }

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li a{ ";
            if ( ! empty( $theme['dropdown_submenu_item_text_color'] ) ) {
                $style .= "color: {$theme['dropdown_submenu_item_text_color']};";
            }
            if ( ! empty( $theme['dropdown_submenu_item_text_font_size'] ) ) {
                $style .= "font-size: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_text_font_size'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_text_font_weight'] ) ) {
                $style .= "font-weight: {$theme['dropdown_submenu_item_text_font_weight']};";
            }
            if ( ! empty( $theme['dropdown_submenu_item_text_line_height'] ) ) {
                $style .= "line-height: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_text_line_height'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_text_transform'] ) ) {
                $style .= "text-transform: {$theme['dropdown_submenu_item_text_transform']};";
            }
            if ( ! empty( $theme['dropdown_submenu_item_text_letter_spacing'] ) ) {
                $style .= "letter-spacing: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_text_letter_spacing'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_bg_color'] ) ) {
                $style .= "background-color: {$theme['dropdown_submenu_item_bg_color']}; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['dropdown_submenu_item_margin_left'] ) . "px; ";
            }
            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li:hover > a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li.current-menu-ancestor > a, #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li.active > a, 
                .wpmm-woo-product-category ul li.cat-list a:hover, .wpmm-woo-product-category.layout_2 ul li a:hover { ";

            if ( ! empty( $theme['dropdown_submenu_item_text_hover_color'] ) ) {
                $style .= "color: {$theme['dropdown_submenu_item_text_hover_color']} !important;";
            }
            if ( ! empty( $theme['dropdown_submenu_item_bg_hover_color'] ) ) {
                $style .= "background-color: {$theme['dropdown_submenu_item_bg_hover_color']}; ";
            }
            if ( ! empty( $theme['dropdown_submenu_item_hover_border_width'] ) && ! empty( $theme['dropdown_submenu_item_hover_border_type'] ) ) {

                if ( ! empty( $theme['dropdown_submenu_item_hover_border_width'] ) ) {
                    $dropdown_submenu_item_hover_border_width = wpmm_unit_to_int( $theme['dropdown_submenu_item_hover_border_width'] ) . "px";
                }

                $style .= "border-bottom: $dropdown_submenu_item_hover_border_width {$theme['dropdown_submenu_item_hover_border_type']} {$theme['dropdown_submenu_item_hover_border_color']}; ";
            }
            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li.current-menu-item > a,
					ul.wp-megamenu li ul.wp-megamenu-sub-menu .wpmm-vertical-tabs-nav ul li.active > a, ul.wp-megamenu li ul.wp-megamenu-sub-menu .wpmm-vertical-tabs-nav ul li.active{ ";
            if ( ! empty( $theme['dropdown_submenu_item_text_hover_color'] ) ) {
                $style .= "color: {$theme['dropdown_submenu_item_text_hover_color']};";
            }
            if ( ! empty( $theme['dropdown_submenu_item_bg_hover_color'] ) ) {
                $style .= "background-color: {$theme['dropdown_submenu_item_bg_hover_color']};";
            }
            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li:last-child:hover > a,
                    #{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li:last-child > a{ ";
            $style .= "border-bottom: none; ";
            $style .= "}";


            $style .= "#{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a, #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a, #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a,
                    #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a, #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a, #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a{ ";
            $style .= "border-bottom: none;background:none;";
            $style .= "}";

// print_r($navbar_id);
            if ( ! empty( $theme['dropdown_submenu_item_border_width'] ) && ! empty( $theme['dropdown_submenu_item_border_type'] ) ) {
                
                $style .= "#{$navbar_id} > .wpmm-nav-wrap  ul.wp-megamenu > li ul.wp-megamenu-sub-menu >li >a{ ";
                
                if ( ! empty( $theme['dropdown_submenu_item_border_width'] ) ) {
                    $dropdown_submenu_item_border_width = wpmm_unit_to_int( $theme['dropdown_submenu_item_border_width'] ) . ";";
                }

                $style .= "border-bottom: {$dropdown_submenu_item_border_width} {$theme['dropdown_submenu_item_border_type']} {$theme['dropdown_submenu_item_border_color']}; ";
                $style .= "}";
            }


            //submenu first item
            $style .= "#{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a,
                    #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a,
                    #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a{ ";
            if ( ! empty( $theme['dropdown_submenu_first_item_text_color'] ) ) {
                // $style .= "color: {$theme['dropdown_submenu_first_item_text_color']};";
                // $style .= "color: pink";
            }
            if ( ! empty( $theme['dropdown_submenu_first_item_text_font_size'] ) ) {
                $style .= "font-size: " . wpmm_unit_to_int( $theme['dropdown_submenu_first_item_text_font_size'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_first_item_text_font_weight'] ) ) {
                $style .= "font-weight: {$theme['dropdown_submenu_first_item_text_font_weight']};";
            }
            if ( ! empty( $theme['dropdown_submenu_first_item_text_line_height'] ) ) {
                $style .= "line-height: " . wpmm_unit_to_int( $theme['dropdown_submenu_first_item_text_line_height'] ) . "px; ";
            }
            if ( ! empty( $theme['dropdown_submenu_first_item_text_transform'] ) ) {
                $style .= "text-transform: {$theme['dropdown_submenu_first_item_text_transform']};";
            }
            if ( ! empty( $theme['dropdown_submenu_first_item_text_letter_spacing'] ) ) {
                $style .= "letter-spacing: " . wpmm_unit_to_int( $theme['dropdown_submenu_first_item_text_letter_spacing'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_first_item_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['widgets_first_item_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_first_item_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['widgets_first_item_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_first_item_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['widgets_first_item_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_first_item_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['widgets_first_item_margin_left'] ) . "px; ";
            }
            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a,
                    #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a,
                    #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover > a{ ";
            if ( ! empty( $theme['dropdown_submenu_first_item_text_hover_color'] ) ) {
                $style .= "color: {$theme['dropdown_submenu_first_item_text_hover_color']};";
            }
            $style .= "}";

            if ( ! empty( $theme['submenu_first_item_border_separator_width'] ) && ! empty( $theme['submenu_first_item_border_separator_type'] ) ) {
                $style .= "#{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget >a,
                        #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li > ul.wp-megamenu-sub-menu li.wpmm-type-widget:hover >a,
                        #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a,
                         #{$navbar_id} > .wpmm-nav-wrap .wp-megamenu >li .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu li.wpmm-type-widget > a{ ";
                if ( ! empty( $theme['submenu_first_item_border_separator_width'] ) ) {
                    $submenu_first_item_border_separator_width = wpmm_unit_to_int( $theme['submenu_first_item_border_separator_width'] ) . "px";
                }
                $style .= "border-bottom: $submenu_first_item_border_separator_width {$theme['submenu_first_item_border_separator_type']} {$theme['submenu_first_item_border_separator_color']}; ";
                $style .= "}";
            }


            # Submenu Icon Font.
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li a .wpmm-selected-icon i { ";
                if ( ! empty( $theme['submenu_first_item_icon_color'] ) ) {
                    $style .= "color: {$theme['submenu_first_item_icon_color']};";
                }
                if ( ! empty( $theme['submenu_first_item_icon_fontsize'] ) ) {
                    $style .= "font-size: " . wpmm_unit_to_int( $theme['submenu_first_item_icon_fontsize'] ) . "px; ";
                }
            $style .= "}";

            # Submenu Upload Icon.
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li ul.wp-megamenu-sub-menu li a .wpmm-selected-icon img.custom-menu-image { ";
                if ( ! empty( $theme['submenu_first_item_icon_fontsize'] ) ) {
                    $style .= "width: " . wpmm_unit_to_int( $theme['submenu_first_item_icon_fontsize'] ) . "px; ";
                }
            $style .= "}";

            /*-----------------------------------------
            *                Mega Menu
            ------------------------------------------- */
            if ( ! empty( $theme['megamenu_bg_color'] ) ) {
                $style .= "
				#{$navbar_id} >.wpmm-nav-wrap > ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-container > ul.wp-megamenu-sub-menu,
				#{$navbar_id} >.wpmm-nav-wrap > ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-and-content-container-container > ul.wp-megamenu-sub-menu,
				#{$navbar_id} >.wpmm-nav-wrap > ul > li.wpmm-strees-row .wpmm-strees-row-container > .wp-megamenu-sub-menu:before, 
				#{$navbar_id} >.wpmm-nav-wrap > ul > li.wpmm-strees-row .wpmm-strees-row-container > .wp-megamenu-sub-menu:after,
				#{$navbar_id} >.wpmm-nav-wrap > ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu{  background: {$theme['megamenu_bg_color']};}";
            }

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.wpmm_mega_menu > ul.wp-megamenu-sub-menu{ ";

            if ( ! empty( $theme['mega_menu_width'] ) ) {
                $padding_left = ! empty($theme['menu_padding_left'] ) ? $theme['menu_padding_left'] : 0;
                $padding_right = ! empty($theme['menu_padding_right'] ) ? $theme['menu_padding_right'] : 0;
                $menu_ex_width = $padding_left + $padding_right;
                $menu_ex_width = $menu_ex_width ? $menu_ex_width : 0;
                $style .= "width: calc({$theme['mega_menu_width']} + {$menu_ex_width}px); margin-left: -{$padding_left}px;";
            }
            if ( ! empty( $theme['megamenu_bg_color'] ) ) {
                $style .= "background-color: {$theme['megamenu_bg_color']}; ";
            }
            if ( ! empty( $theme['megamenu_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['megamenu_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['megamenu_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['megamenu_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['megamenu_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['megamenu_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['megamenu_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['megamenu_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['megamenu_menu_border_width'] ) && ! empty( $theme['megamenu_menu_border_type'] ) ) {

                if ( ! empty( $theme['megamenu_menu_border_width'] ) ) {
                    $megamenu_menu_border_width = wpmm_unit_to_int( $theme['megamenu_menu_border_width'] ) . "px";
                }

                $style .= "border: $megamenu_menu_border_width {$theme['megamenu_menu_border_type']} {$theme['megamenu_menu_border_color']}; ";
            }

            if ( ! empty( $theme['megamenu_boxshadow_color'] ) ) {

                if ( ! empty( $theme['megamenu_boxshadow_top'] ) ) {
                    $megamenu_boxshadow_top = wpmm_unit_to_int( $theme['megamenu_boxshadow_top'] ) . "px";
                } else {
                    $megamenu_boxshadow_top = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_right'] ) ) {
                    $megamenu_boxshadow_right = wpmm_unit_to_int( $theme['megamenu_boxshadow_right'] ) . "px";
                } else {
                    $megamenu_boxshadow_right = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_bottom'] ) ) {
                    $megamenu_boxshadow_bottom = wpmm_unit_to_int( $theme['megamenu_boxshadow_bottom'] ) . "px";
                } else {
                    $megamenu_boxshadow_bottom = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_left'] ) ) {
                    $megamenu_boxshadow_left = wpmm_unit_to_int( $theme['megamenu_boxshadow_left'] ) . "px";
                } else {
                    $megamenu_boxshadow_left = 0;
                }

                $style .= "box-shadow: $megamenu_boxshadow_top $megamenu_boxshadow_right $megamenu_boxshadow_bottom $megamenu_boxshadow_left {$theme['megamenu_boxshadow_color']} ;";
            }
            if ( ! empty( $theme['megamenu_border_radius_top_left'] ) || ! empty( $theme['megamenu_radius_top_right'] ) || ! empty( $theme['megamenu_radius_bottom_left'] ) || ! empty( $theme['megamenu_radius_bottom_right'] ) ) {

                if ( ! empty( $theme['megamenu_border_radius_top_left'] ) ) {
                    $megamenu_border_radius_top_left = wpmm_unit_to_int( $theme['megamenu_border_radius_top_left'] ) . "px";
                } else {
                    $megamenu_border_radius_top_left = 0;
                }

                if ( ! empty( $theme['megamenu_radius_top_right'] ) ) {
                    $megamenu_radius_top_right = wpmm_unit_to_int( $theme['megamenu_radius_top_right'] ) . "px";
                } else {
                    $megamenu_radius_top_right = 0;
                }

                if ( ! empty( $theme['megamenu_radius_bottom_left'] ) ) {
                    $megamenu_radius_bottom_left = wpmm_unit_to_int( $theme['megamenu_radius_bottom_left'] ) . "px";
                } else {
                    $megamenu_radius_bottom_left = 0;
                }

                if ( ! empty( $theme['megamenu_radius_bottom_right'] ) ) {
                    $megamenu_radius_bottom_right = wpmm_unit_to_int( $theme['megamenu_radius_bottom_right'] ) . "px";
                } else {
                    $megamenu_radius_bottom_right = 0;
                }

                $style .= " border-radius: $megamenu_border_radius_top_left $megamenu_radius_top_right $megamenu_radius_bottom_left $megamenu_radius_bottom_right ";
            }
            $style .= "}";



            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-and-content-container > ul.wp-megamenu-sub-menu{";

            if ( ! empty( $theme['megamenu_boxshadow_color'] ) ) {

                if ( ! empty( $theme['megamenu_boxshadow_top'] ) ) {
                    $megamenu_boxshadow_top = wpmm_unit_to_int( $theme['megamenu_boxshadow_top'] ) . "px";
                } else {
                    $megamenu_boxshadow_top = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_right'] ) ) {
                    $megamenu_boxshadow_right = wpmm_unit_to_int( $theme['megamenu_boxshadow_right'] ) . "px";
                } else {
                    $megamenu_boxshadow_right = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_bottom'] ) ) {
                    $megamenu_boxshadow_bottom = wpmm_unit_to_int( $theme['megamenu_boxshadow_bottom'] ) . "px";
                } else {
                    $megamenu_boxshadow_bottom = 0;
                }

                if ( ! empty( $theme['megamenu_boxshadow_left'] ) ) {
                    $megamenu_boxshadow_left = wpmm_unit_to_int( $theme['megamenu_boxshadow_left'] ) . "px";
                } else {
                    $megamenu_boxshadow_left = 0;
                }

                $style .= "box-shadow: $megamenu_boxshadow_top $megamenu_boxshadow_right $megamenu_boxshadow_bottom $megamenu_boxshadow_left {$theme['megamenu_boxshadow_color']} ;";
            }

            $style .= "}";

            if ( ! empty( $theme['megamenu_menu_border_separator_width'] ) && ! empty( $theme['megamenu_menu_border_separator_type'] ) ) {
                $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu > li.wpmm_mega_menu > ul.wp-megamenu-sub-menu li.wpmm-col{ ";
                if ( ! empty( $theme['megamenu_menu_border_separator_width'] ) ) {
                    $megamenu_menu_border_separator_width = wpmm_unit_to_int( $theme['megamenu_menu_border_separator_width'] ) . "px";
                }
                $style .= "border-right: $megamenu_menu_border_separator_width {$theme['megamenu_menu_border_separator_type']} {$theme['megamenu_menu_border_separator_color']}; ";
                $style .= "}";

            }


            /* --------------------------------
            *           WooCart
            ----------------------------------- */ 
            if ( ! empty( $theme['enable_woocart'] ) && $theme['enable_woocart'] == true ) {
                if ( ! empty( $theme['cart_text_color'] ) ) {
                    $style .= ".wpmm-woo-cart .wpmm-menu-cart .cart-contents .count { ";
                        $style .= "color: {$theme['cart_text_color']};";
                    $style .= "}";
                }

                if ( ! empty( $theme['cart_btn_bg_color'] ) ) {
                    $style .= ".wpmm-woo-cart .wpmm-menu-cart .cart-contents { ";
                        $style .= "background: {$theme['cart_btn_bg_color']};";
                    $style .= "}";
                }

                $style .= ".wp-megamenu .wpmm-widget-cart p.woocommerce-mini-cart__buttons.buttons a { ";
                    $style .= "background-color: {$menubar_bg};";
                $style .= "}";

                $style .= ".wp-megamenu .wpmm-widget-cart p.woocommerce-mini-cart__buttons.buttons a{ ";
                if ( ! empty( $theme['top_level_item_text_color'] ) ) {
                    $style .= "color: {$theme['top_level_item_text_color']};";
                }
                $style .= "}";

                $style .= ".wpmm-woo-cart .wpmm-menu-cart span.cart-icon i, .wp-megamenu-wrap .wpmm-nav-wrap>ul>li.wpmm-social-link.wpmm-social-link-search a { ";
                if ( ! empty( $theme['cart_icon_color'] ) ) {
                    $style .= "color: {$theme['cart_icon_color']} !important;";
                }
                $style .= "}"; 
            }

            // print_r($menubar_bg);
            // die();
            


            /* Widget */
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu li.wpmm-type-widget .wpmm-item-title{ ";
            if ( ! empty( $theme['widgets_heading_text_color'] ) ) {
                $style .= "color: {$theme['widgets_heading_text_color']};";
            }
            if ( ! empty( $theme['widgets_heading_text_font_size'] ) ) {
                $style .= "font-size: " . wpmm_unit_to_int( $theme['widgets_heading_text_font_size'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_text_font_weight'] ) ) {
                $style .= "font-weight: {$theme['widgets_heading_text_font_weight']};";
            }
            if ( ! empty( $theme['widgets_heading_text_line_height'] ) ) {
                $style .= "line-height: " . wpmm_unit_to_int( $theme['widgets_heading_text_line_height'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_text_transform'] ) ) {
                $style .= "text-transform: {$theme['widgets_heading_text_transform']};";
            }
            if ( ! empty( $theme['widgets_heading_text_letter_spacing'] ) ) {
                $style .= "letter-spacing: " . wpmm_unit_to_int( $theme['widgets_heading_text_letter_spacing'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['widgets_heading_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['widgets_heading_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['widgets_heading_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_heading_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['widgets_heading_margin_left'] ) . "px; ";
            }

            $style .= "}";

            if ( ! empty( $theme['widget_first_item_border_separator_width'] ) && ! empty( $theme['widget_first_item_border_separator_type'] ) ) {
                $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu li.wpmm-type-widget .wpmm-item-title{ ";
                if ( ! empty( $theme['widget_first_item_border_separator_width'] ) ) {
                    $widget_first_item_border_separator_width = wpmm_unit_to_int( $theme['widget_first_item_border_separator_width'] ) . "px";
                }
                $style .= "border-bottom: $widget_first_item_border_separator_width {$theme['widget_first_item_border_separator_type']} {$theme['widget_first_item_border_separator_color']}; ";
                $style .= "}";

            }


            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu  li.wpmm-type-widget .wpmm-item-title:hover{ ";
            if ( ! empty( $theme['widgets_heading_text_hover_color'] ) ) {
                $style .= "color: {$theme['widgets_heading_text_hover_color']};";
            }
            $style .= "}";

            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu  li.wpmm-type-widget{ ";
            if ( ! empty( $theme['widgets_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['widgets_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['widgets_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['widgets_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['widgets_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['widgets_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['widgets_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['widgets_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['widgets_margin_left'] ) . "px; ";
            }
            if ( ! empty( $theme['widgets_border_width'] ) && $theme['widgets_border_type'] ) {

                if ( ! empty( $theme['widgets_border_width'] ) ) {
                    $widgets_border_width = wpmm_unit_to_int( $theme['widgets_border_width'] ) . "px";
                }
                $style .= "border: $widgets_border_width {$theme['widgets_border_type']} {$theme['widgets_border_color']};";
            }
            if ( ! empty( $theme['widgets_content_color'] ) ) {
                $style .= "color: {$theme['widgets_content_color']};";
            }
            $style .= "}";
            /* Widget Hover */
            $style .= "#{$navbar_id} > .wpmm-nav-wrap ul.wp-megamenu li.wpmm-type-widget:hover{ ";
            if ( ! empty( $theme['widgets_hover_border_width'] ) && $theme['widgets_hover_border_type'] ) {
                if ( ! empty( $theme['widgets_hover_border_width'] ) ) {
                    $widgets_hover_border_width = wpmm_unit_to_int( $theme['widgets_hover_border_width'] ) . "px";
                }
                $style .= "border: $widgets_hover_border_width {$theme['widgets_hover_border_type']} {$theme['widgets_hover_border_color']};";
            }
            $style .= "}";


            /* social color */
            if ( ! empty( $theme['social_color'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap > .wpmm-nav-wrap > ul > li.wpmm-social-link a { ";
                if ( ! empty( $theme['social_color'] ) ) {
                    $style .= "color: {$theme['social_color']};";
                }
                $style .= "}";
            }
            if ( ! empty( $theme['social_hover_color'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap > .wpmm-nav-wrap > ul > li.wpmm-social-link a:hover { ";
                if ( ! empty( $theme['social_hover_color'] ) ) {
                    $style .= "color: {$theme['social_hover_color']};";
                }
                $style .= "}";
            }


            $responsive_breakpoint        = '767px';
            $option_responsive_breakpoint = get_wpmm_option( 'responsive_breakpoint' );
            if ( ! empty( $option_responsive_breakpoint ) ) {
                $responsive_breakpoint = $option_responsive_breakpoint;
            }
            $style .= '@media (max-width: ' . $responsive_breakpoint . ') {
                        .wpmm_mobile_menu_btn{
                            display: block;
                        }
                        .wp-megamenu-wrap.wpmm-mobile-menu ul.wp-megamenu{
                            display: none;
                            position: absolute;
                            z-index: 9999;
                            background: #FFFFFF;
                            width: 100%;
                            left:0;
                            max-height: none;
                        }
                        .wp-megamenu-wrap.wpmm-mobile-menu ul.wp-megamenu li{
                            width: 100%;
                        }
                        .wp-megamenu-wrap.wpmm-mobile-menu ul.wp-megamenu li button{
                            padding: 0;
                            background: none;
                        }';

            $style .= 'a.wpmm_mobile_menu_btn{
                        display: inline-block !important;
                    }';

            //Toggle Css
            $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap{ ";
            if ( ! empty( $theme['toggle_bar_alignment'] ) ) {
                $style .= "text-align: {$theme['toggle_bar_alignment']};";
            }
            $style .= "height: 100%;";

            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap ul.wp-megamenu {";
            $style .= "text-align: left;";
            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap > ul > li.wpmm-social-link{";
            $style .= "float: none;";
            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap{";
            $style .= "vertical-align: baseline; display: block; width: 100%; text-align: right;";
            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap > ul > li > a{";
            $style .= "padding: 10px 12px;";
            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap .wp-megamenu{";
            $style .= "padding: 10px;";
            $style .= "}";

            $style .= ".wpmm-mobile-menu ul.wp-megamenu li > a b{";
            $style .= "float: right; padding: 0 5px; line-height: inherit";
            $style .= "}";
            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap{";
            $style .= "position: relative;";
            $style .= "}";
            $style .= ".wpmm-mobile-menu ul.wp-megamenu li > a{";
            $style .= "display: block;";
            $style .= "}";
            $style .= ".wpmm-mobile-menu ul.wp-megamenu li{";
            $style .= "border: none;";
            $style .= "}";
            $style .= ".admin-bar .wpmm-sticky.wpmm-sticky-wrap.wpmm-mobile-menu{";
            $style .= "top: auto;";
            $style .= "}";
            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap > ul.wp-megamenu > li.wpmm_dropdown_menu ul.wp-megamenu-sub-menu{";
            $style .= "box-shadow: none;";
            $style .= "}";
            $style .= ".wpmm-mobile-menu a.wpmm_mobile_menu_btn{";
            $style .= "box-shadow: none; border: none; padding: 6px 12px; font-weight: 400; margin: 12px 0; border-radius: 3px; transition: 400ms; -webkit-transition: 400ms;";
            $style .= "}";
            $style .= ".wpmm-mobile-menu .wpmm_mobile_menu_btn i{";
            $style .= "vertical-align: baseline;";
            $style .= "}";
            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap ul.wp-megamenu li .wp-megamenu-sub-menu{";
            $style .= "position: relative;opacity: 1;visibility: visible;padding: 0;margin: 0;";
            $style .= "}";
            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap ul.wp-megamenu > li.wpmm_mega_menu > ul.wp-megamenu-sub-menu{";
            $style .= "position: relative; visibility: visible; opacity: 1;";
            $style .= "}";
            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap ul.wp-megamenu li .wp-megamenu-sub-menu li ul{";
            $style .= "padding: 0; margin: 0;";
            $style .= "}";
            $style .= ".wpmm-mobile-menu .wpmm-social-link{";
            $style .= "display: inline-block; width: auto !important;";
            $style .= "}";

            $style .= "#{$navbar_id}.wpmm-mobile-menu a.wpmm_mobile_menu_btn{ ";
            if ( ! empty( $theme['togglebar_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['togglebar_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['togglebar_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['togglebar_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['togglebar_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['togglebar_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['togglebar_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['togglebar_margin_left'] ) . "px; ";
            }
            $style .= "}";

            $style .= ".wpmm_mobile_menu_btn{ ";

            if ( ! empty( $theme['toggle_btn_text_color'] ) ) {
                $style .= "color: {$theme['toggle_btn_text_color']} !important;";
            }
            if ( ! empty( $theme['toggle_bar_bg'] ) ) {
                $style .= "background-color: {$theme['toggle_bar_bg']};";
            }
            if ( ! empty( $theme['toggle_bar_font_size'] ) ) {
                $style .= "font-size: " . wpmm_unit_to_int( $theme['toggle_bar_font_size'] ) . "px; ";
            }

            $style .= "}";

            $style .= ".wpmm_mobile_menu_btn:hover{ ";

            if ( ! empty( $theme['toggle_btn_text_hover_color'] ) ) {
                $style .= "color: {$theme['toggle_btn_text_hover_color']} !important;";
            }
            if ( ! empty( $theme['toggle_bar_hover_bg'] ) ) {
                $style .= "background-color: {$theme['toggle_bar_hover_bg']};";
            }
            $style .= "}";

            if ( ! empty( $theme['toggle_bar_font_size'] ) ) {
                $style .= ".wpmm_mobile_menu_btn i{ ";
                $style .= "font-size: " . wpmm_unit_to_int( $theme['toggle_bar_font_size'] ) . "px; ";
                $style .= "}";
            }

            $mobile_menu_bg = isset($theme['mobile_menu_bg']) ? $theme['mobile_menu_bg'] : '';
            $mobile_menu_bg_2 = isset($theme['mobile_menu_bg_2']) ? $theme['mobile_menu_bg_2'] : '';

            $mobile_menu_bg_gradient_angle = isset($theme['mobile_menu_bg_gradient_angle']) && !empty($theme['mobile_menu_bg_gradient_angle']) ? intval($theme['mobile_menu_bg_gradient_angle']) : -90;

            if($mobile_menu_bg_gradient_angle >= 360 || $mobile_menu_bg_gradient_angle  <= -360){
                $mobile_menu_bg_gradient_angle = -90;
            }

            if ( ! empty( $mobile_menu_bg ) ) {
                $style .= '.wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap .wp-megamenu{';
                $style .= "background-color: {$mobile_menu_bg};";
                if(!empty($mobile_menu_bg_2)){
                    $style .= "
                        background-image: linear-gradient({$mobile_menu_bg_gradient_angle}deg, $mobile_menu_bg , $mobile_menu_bg_2);
                    ";
                }
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_menu_item_color'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "color: {$theme['mobile_menu_item_color']};";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_item_text_font_size'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "font-size: " . wpmm_unit_to_int( $theme['mobile_item_text_font_size'] ) . "px; ";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_item_text_font_weight'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "font-weight: {$theme['mobile_item_text_font_weight']};";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_item_text_line_height'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "line-height:  " . wpmm_unit_to_int( $theme['mobile_item_text_line_height'] ) . "px; ";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_item_text_transform'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "text-transform: {$theme['mobile_item_text_transform']};";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_item_text_letter_spacing'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a{ ";
                $style .= "letter-spacing: " . wpmm_unit_to_int( $theme['mobile_item_text_letter_spacing'] ) . "px; ";
                $style .= "}";
            }

            if ( ! empty( $theme['mobile_menu_item_hover_color'] ) ) {
                $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li:hover > a, #{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li.current-menu-ancestor > a, #{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li.active > a{ ";
                $style .= "color: {$theme['mobile_menu_item_hover_color']};";
                $style .= "}";
            }

            $style .= "#{$navbar_id}.wp-megamenu-wrap.wpmm-mobile-menu > .wpmm-nav-wrap ul.wp-megamenu > li > a { ";
            if ( ! empty( $theme['mobile_menu_item_padding_top'] ) ) {
                $style .= "padding-top: " . wpmm_unit_to_int( $theme['mobile_menu_item_padding_top'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_padding_right'] ) ) {
                $style .= "padding-right: " . wpmm_unit_to_int( $theme['mobile_menu_item_padding_right'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_padding_bottom'] ) ) {
                $style .= "padding-bottom: " . wpmm_unit_to_int( $theme['mobile_menu_item_padding_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_padding_left'] ) ) {
                $style .= "padding-left: " . wpmm_unit_to_int( $theme['mobile_menu_item_padding_left'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_margin_top'] ) ) {
                $style .= "margin-top: " . wpmm_unit_to_int( $theme['mobile_menu_item_margin_top'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_margin_right'] ) ) {
                $style .= "margin-right: " . wpmm_unit_to_int( $theme['mobile_menu_item_margin_right'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_margin_bottom'] ) ) {
                $style .= "margin-bottom: " . wpmm_unit_to_int( $theme['mobile_menu_item_margin_bottom'] ) . "px; ";
            }
            if ( ! empty( $theme['mobile_menu_item_margin_left'] ) ) {
                $style .= "margin-left: " . wpmm_unit_to_int( $theme['mobile_menu_item_margin_left'] ) . "px; ";
            }
            $style .= "}";

            $style .= ".wp-megamenu-wrap.wpmm-mobile-menu .wpmm-nav-wrap .wp-megamenu > li.wpmm-item-fixed-width  > ul.wp-megamenu-sub-menu {";

            $style .= "left: 0 !important; ";
            $style .= "width: 100% !important; ";

            $style .= "}";

            $style .= '}'; //Responsive End

            if ( ! empty( $theme['custom_css'] ) ) {
                $style .= $theme['custom_css'];
            }

            return apply_filters('wpmm_generated_css', $style, $navbar_id, $theme);
        }


        /**
         * Render css to wp head
         */
        public function render_css() {
            $css_output_location = get_wpmm_option( 'css_output_location' );
            if ( $css_output_location == 'filesystem' ) {
                return;
            }
            $style = '<style type="text/css">';
            $style .= $this->css();
            $style .= '</style>';

            echo wpmm_sanitize_inline_css_output( $style );
        }

        /**
         * @param $css
         *
         * Save css to File Systems
         */
        public function save_to_filesystem( $css ) {
            global $wp_filesystem;
            if ( ! $wp_filesystem ) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            $filename = 'wp-megamenu.css';

            $upload_dir = wp_upload_dir();
            $dir = trailingslashit($upload_dir['basedir']) . 'wp-megamenu/';

            WP_Filesystem( false, $upload_dir['basedir'], true );

            if( ! $wp_filesystem->is_dir( $dir ) ) {
                $wp_filesystem->mkdir( $dir );
            }

            //If Fail saving to uploads directory, then change css again to head
            if ( ! $wp_filesystem->put_contents( $dir . $filename, $css ) ) {
                $options = get_option( 'wpmm_options' );
                $options = maybe_unserialize($options);
                $options['css_output_location'] = 'head';
                update_option('wpmm_options', $options);
            }
        }

        public function css_file_saving_action(){
            //Upload file css
            $this->save_to_filesystem($this->css());
        }


        /**
         * Adding google fonts to head as style by enqueue
         * @since v.1.0
         */
        public function before_header(){
            global $wpdb;
            $wpmm_css_args = array('post_type' => 'wpmm_theme');
            $wpmm_css_query = new WP_Query( $wpmm_css_args );

            $loadable_fonts = array();
            if ($wpmm_css_query->have_posts()){
                while ($wpmm_css_query->have_posts()) {
                    $wpmm_css_query->the_post();
                    $theme = maybe_unserialize(get_the_content());
                    //dropdown_submenu_item_text_font
                    if ( ! empty($theme['top_level_item_text_font'])){
                        $loadable_fonts[strtolower( str_replace( ' ', '-', urldecode( $theme['top_level_item_text_font'] ) ) )] = $theme['top_level_item_text_font'];
                    }
                    if ( ! empty($theme['dropdown_submenu_item_text_font'])){
                        $loadable_fonts[strtolower( str_replace( ' ', '-', urldecode( $theme['dropdown_submenu_item_text_font'] ) ) )] = $theme['dropdown_submenu_item_text_font'];
                    }
                    if ( ! empty($theme['cta_btn_font'])){
                        $loadable_fonts[strtolower( str_replace( ' ', '-', urldecode( $theme['cta_btn_font'] ) ) )] = $theme['cta_btn_font'];
                    }
                }
                wp_reset_query();
            }

            //Check if there any Item Logo sets Google Font
            $menu_layout_query = $wpdb->get_results("select * from {$wpdb->postmeta} WHERE meta_key = 'wpmm_layout' ");
            if ( ! empty($menu_layout_query) ) {
                foreach ( $menu_layout_query as $layout ) {
                    $layout_unserialize = maybe_unserialize( $layout->meta_value );
                    $mo                 = ! empty( $layout_unserialize['options'] ) ? $layout_unserialize['options'] : array();



                    if (! empty($mo['item_logo_type']) && $mo['item_logo_type'] === 'text' && ! empty( $mo['text_logo_google_font']) ) {
                        if ( ! empty( $mo['text_logo_google_font'] ) ) {
                            $loadable_fonts[strtolower( str_replace( ' ', '-', urldecode( $mo['text_logo_google_font'] ) ) )] = $mo['text_logo_google_font'];
                        }
                    }







                }
            }

            if ( ! empty($loadable_fonts)){
                foreach ($loadable_fonts as $key => $family){
                    $uri = '//fonts.googleapis.com/css?family=';
                    $params = urldecode( $family );
                    $params = str_replace( ' ', '+', $params );
                    $params2 = ':';
                    foreach (wpmm_get_google_font_weights($family) as $font_style) {
                        $params2.= $font_style.',';
                    }
                    $params2 = rtrim($params2 , ',');
                    wp_enqueue_style( $key, $uri.$params.$params2, false, WPMM_VER );
                }
            }
        }



        public function wp_update_nav_menu($menu_id){

            $this->css_file_saving_action();
        }

    }

    wp_megamenu_css::init();
}