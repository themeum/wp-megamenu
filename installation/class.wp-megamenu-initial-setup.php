<?php
/**
 * Class wp_megamenu_initial_setup
 */
if ( ! class_exists('wp_megamenu_initial_setup')) {
    class wp_megamenu_initial_setup{

        /**
         * @return wp_megamenu_initial_setup
         */
        public static function init(){
            $return = new self();
            return $return;
        }

        /**
         * wp_megamenu_initial_setup constructor.
         */
        public function __construct() {
            register_activation_hook( WPMM_FILE, array($this, 'initial_setup') );
        }

        /**
         * Save all required values
         */
        public function initial_setup(){
            $WPMM_VER = get_option('WPMM_VER');
            if (! $WPMM_VER){
                //Add Theme
                $this->add_initial_theme();
                //Adding default settings
                $settings_option = unserialize($this->settings_option());
                update_option('wpmm_options', $settings_option);
            }
            //Add plugin version
            update_option('WPMM_VER', WPMM_VER);

	        //Showing default menu theme in nav page
	        $user_id = get_current_user_id();
	        update_user_meta($user_id, 'metaboxhidden_nav-menus', array());
	        //$this->set_wpmm_initial_menu();
        }

        public function add_initial_theme(){
            $serilized_data = $this->classic_theme();
            $post_data = unserialize($serilized_data);
            $required_keys = array('post_title', 'post_content', 'post_type', 'post_status');
            if (array_keys_exist($required_keys, $post_data)){
                $imported_post_id = wp_insert_post($post_data);
            }
        }

        public function settings_option(){
            $option = 'a:4:{s:19:"css_output_location";s:10:"filesystem";s:13:"container_tag";s:3:"nav";s:19:"enable_font_awesome";s:6:"enable";s:21:"responsive_breakpoint";s:5:"767px";}';
            return $option;
        }

        public function classic_theme(){
            $string = 'a:4:{s:10:"post_title";s:14:"classic-themes";s:12:"post_content";s:8298:"a:192:{s:18:"enable_sticky_menu";s:4:"true";s:6:"zindex";s:4:"9999";s:19:"dropdown_arrow_down";s:13:"fa-angle-down";s:20:"dropdown_arrow_right";s:14:"fa-angle-right";s:17:"enable_search_bar";s:4:"true";s:10:"brand_logo";s:87:"http://demo.themeum.com/wordpress/wp-megamenu/wp-content/uploads/2017/07/brand-logo.png";s:16:"brand_logo_width";s:2:"32";s:17:"brand_logo_height";s:2:"31";s:15:"logo_margin_top";s:2:"11";s:17:"logo_margin_right";s:2:"15";s:18:"logo_margin_bottom";s:0:"";s:16:"logo_margin_left";s:0:"";s:10:"menu_align";s:4:"left";s:14:"menubar_height";s:0:"";s:10:"menubar_bg";s:7:"#fbfbfb";s:16:"menu_padding_top";s:0:"";s:18:"menu_padding_right";s:2:"20";s:19:"menu_padding_bottom";s:0:"";s:17:"menu_padding_left";s:2:"20";s:27:"menu_border_radius_top_left";s:0:"";s:21:"menu_radius_top_right";s:0:"";s:23:"menu_radius_bottom_left";s:0:"";s:24:"menu_radius_bottom_right";s:0:"";s:10:"top_shadow";s:0:"";s:12:"right_shadow";s:0:"";s:13:"bottom_shadow";s:0:"";s:11:"left_shadow";s:0:"";s:17:"wpmm_theme_shadow";s:0:"";s:24:"menubar_top_border_width";s:0:"";s:26:"menubar_right_border_width";s:0:"";s:27:"menubar_bottom_border_width";s:0:"";s:25:"menubar_left_border_width";s:0:"";s:16:"menu_border_type";s:4:"none";s:17:"menu_border_color";s:0:"";s:24:"top_level_item_text_font";s:7:"Poppins";s:25:"top_level_item_text_color";s:7:"#000000";s:31:"top_level_item_text_hover_color";s:7:"#2964d8";s:29:"top_level_item_text_font_size";s:4:"14px";s:31:"top_level_item_text_font_weight";s:3:"500";s:31:"top_level_item_text_line_height";s:2:"24";s:29:"top_level_item_text_transform";s:9:"uppercase";s:34:"top_level_item_text_letter_spacing";s:2:".5";s:23:"top_level_item_bg_color";s:0:"";s:29:"top_level_item_bg_hover_color";s:0:"";s:26:"top_level_item_padding_top";s:0:"";s:28:"top_level_item_padding_right";s:0:"";s:29:"top_level_item_padding_bottom";s:0:"";s:27:"top_level_item_padding_left";s:0:"";s:25:"top_level_item_margin_top";s:0:"";s:27:"top_level_item_margin_right";s:0:"";s:28:"top_level_item_margin_bottom";s:0:"";s:26:"top_level_item_margin_left";s:0:"";s:27:"top_level_item_border_width";s:0:"";s:26:"top_level_item_border_type";s:0:"";s:27:"top_level_item_border_color";s:0:"";s:33:"top_level_item_hover_border_width";s:0:"";s:32:"top_level_item_hover_border_type";s:4:"none";s:33:"top_level_item_hover_border_color";s:0:"";s:19:"dropdown_menu_width";s:3:"220";s:16:"dropdown_menu_bg";s:7:"#ffffff";s:25:"dropdown_menu_padding_top";s:0:"";s:27:"dropdown_menu_padding_right";s:0:"";s:28:"dropdown_menu_padding_bottom";s:0:"";s:26:"dropdown_menu_padding_left";s:0:"";s:36:"dropdown_menu_border_radius_top_left";s:0:"";s:30:"dropdown_menu_radius_top_right";s:0:"";s:32:"dropdown_menu_radius_bottom_left";s:0:"";s:33:"dropwodn_menu_radius_bottom_right";s:0:"";s:26:"dropdown_menu_border_width";s:0:"";s:25:"dropdown_menu_border_type";s:4:"none";s:26:"dropdown_menu_border_color";s:0:"";s:31:"dropdown_submenu_item_text_font";s:7:"Poppins";s:32:"dropdown_submenu_item_text_color";s:7:"#282828";s:38:"dropdown_submenu_item_text_hover_color";s:7:"#2964d8";s:36:"dropdown_submenu_item_text_font_size";s:4:"13px";s:38:"dropdown_submenu_item_text_font_weight";s:3:"300";s:38:"dropdown_submenu_item_text_line_height";s:2:"24";s:36:"dropdown_submenu_item_text_transform";s:10:"capitalize";s:41:"dropdown_submenu_item_text_letter_spacing";s:0:"";s:30:"dropdown_submenu_item_bg_color";s:7:"inherit";s:36:"dropdown_submenu_item_bg_hover_color";s:7:"inherit";s:33:"dropdown_submenu_item_padding_top";s:1:"3";s:35:"dropdown_submenu_item_padding_right";s:0:"";s:36:"dropdown_submenu_item_padding_bottom";s:1:"3";s:34:"dropdown_submenu_item_padding_left";s:0:"";s:32:"dropdown_submenu_item_margin_top";s:0:"";s:34:"dropdown_submenu_item_margin_right";s:0:"";s:35:"dropdown_submenu_item_margin_bottom";s:0:"";s:33:"dropdown_submenu_item_margin_left";s:0:"";s:34:"dropdown_submenu_item_border_width";s:0:"";s:33:"dropdown_submenu_item_border_type";s:4:"none";s:34:"dropdown_submenu_item_border_color";s:0:"";s:40:"dropdown_submenu_item_hover_border_width";s:0:"";s:39:"dropdown_submenu_item_hover_border_type";s:4:"none";s:40:"dropdown_submenu_item_hover_border_color";s:0:"";s:38:"dropdown_submenu_first_item_text_color";s:7:"inherit";s:44:"dropdown_submenu_first_item_text_hover_color";s:7:"#2964d8";s:42:"dropdown_submenu_first_item_text_font_size";s:4:"13px";s:44:"dropdown_submenu_first_item_text_font_weight";s:3:"600";s:44:"dropdown_submenu_first_item_text_line_height";s:2:"25";s:42:"dropdown_submenu_first_item_text_transform";s:9:"uppercase";s:47:"dropdown_submenu_first_item_text_letter_spacing";s:2:".5";s:29:"widgets_first_item_margin_top";s:0:"";s:31:"widgets_first_item_margin_right";s:0:"";s:32:"widgets_first_item_margin_bottom";s:1:"5";s:30:"widgets_first_item_margin_left";s:0:"";s:41:"submenu_first_item_border_separator_width";s:0:"";s:40:"submenu_first_item_border_separator_type";s:4:"none";s:41:"submenu_first_item_border_separator_color";s:0:"";s:17:"megamenu_bg_color";s:7:"#ffffff";s:15:"mega_menu_width";s:4:"100%";s:20:"megamenu_padding_top";s:0:"";s:22:"megamenu_padding_right";s:0:"";s:23:"megamenu_padding_bottom";s:0:"";s:21:"megamenu_padding_left";s:0:"";s:31:"megamenu_border_radius_top_left";s:0:"";s:25:"megamenu_radius_top_right";s:0:"";s:27:"megamenu_radius_bottom_left";s:0:"";s:28:"megamenu_radius_bottom_right";s:0:"";s:26:"megamenu_menu_border_width";s:0:"";s:25:"megamenu_menu_border_type";s:4:"none";s:26:"megamenu_menu_border_color";s:0:"";s:36:"megamenu_menu_border_separator_width";s:0:"";s:35:"megamenu_menu_border_separator_type";s:5:"solid";s:36:"megamenu_menu_border_separator_color";s:0:"";s:22:"megamenu_boxshadow_top";s:0:"";s:24:"megamenu_boxshadow_right";s:0:"";s:25:"megamenu_boxshadow_bottom";s:0:"";s:23:"megamenu_boxshadow_left";s:0:"";s:24:"megamenu_boxshadow_color";s:0:"";s:26:"widgets_heading_text_color";s:7:"#000000";s:32:"widgets_heading_text_hover_color";s:0:"";s:30:"widgets_heading_text_font_size";s:4:"13px";s:32:"widgets_heading_text_font_weight";s:3:"600";s:32:"widgets_heading_text_line_height";s:2:"25";s:30:"widgets_heading_text_transform";s:9:"uppercase";s:35:"widgets_heading_text_letter_spacing";s:2:".5";s:40:"widget_first_item_border_separator_width";s:0:"";s:39:"widget_first_item_border_separator_type";s:4:"none";s:40:"widget_first_item_border_separator_color";s:0:"";s:19:"widgets_padding_top";s:0:"";s:21:"widgets_padding_right";s:0:"";s:22:"widgets_padding_bottom";s:0:"";s:20:"widgets_padding_left";s:0:"";s:18:"widgets_margin_top";s:0:"";s:20:"widgets_margin_right";s:0:"";s:21:"widgets_margin_bottom";s:0:"";s:19:"widgets_margin_left";s:0:"";s:20:"widgets_border_width";s:0:"";s:19:"widgets_border_type";s:4:"none";s:20:"widgets_border_color";s:0:"";s:26:"widgets_hover_border_width";s:0:"";s:25:"widgets_hover_border_type";s:4:"none";s:26:"widgets_hover_border_color";s:0:"";s:21:"widgets_content_color";s:7:"#333333";s:20:"toggle_bar_alignment";s:5:"right";s:20:"toggle_btn_open_text";s:4:"Menu";s:21:"toggle_btn_text_color";s:7:"#ffffff";s:27:"toggle_btn_text_hover_color";s:7:"#ffffff";s:20:"toggle_bar_font_size";s:4:"14px";s:20:"togglebar_margin_top";s:2:"12";s:22:"togglebar_margin_right";s:0:"";s:23:"togglebar_margin_bottom";s:2:"12";s:21:"togglebar_margin_left";s:0:"";s:13:"toggle_bar_bg";s:7:"#2964d8";s:19:"toggle_bar_hover_bg";s:7:"#2844d3";s:14:"mobile_menu_bg";s:0:"";s:10:"wpmm_class";s:0:"";s:10:"custom_css";s:0:"";s:9:"custom_js";s:0:"";s:19:"enable_social_links";s:4:"true";s:19:"social_links_target";s:6:"_blank";s:21:"social_links_facebook";s:33:"https://www.facebook.com/themeum/";s:20:"social_links_twitter";s:28:"https://twitter.com/themeum/";s:18:"social_links_gplus";s:0:"";s:22:"social_links_instagram";s:0:"";s:21:"social_links_linkedin";s:0:"";s:22:"social_links_pinterest";s:0:"";s:20:"social_links_youtube";s:0:"";s:21:"social_links_dribbble";s:0:"";s:20:"social_links_behance";s:0:"";s:17:"social_links_digg";s:0:"";s:18:"social_links_vimeo";s:0:"";s:24:"social_links_stumbleupon";s:0:"";s:19:"social_links_reddit";s:0:"";s:22:"social_links_delicious";s:0:"";s:18:"social_links_skype";s:0:"";s:19:"social_links_github";s:0:"";s:19:"social_links_amazon";s:0:"";s:21:"social_links_whatsapp";s:0:"";s:23:"social_links_soundcloud";s:0:"";s:14:"animation_type";s:10:"fadeindown";}";s:9:"post_type";s:10:"wpmm_theme";s:11:"post_status";s:7:"publish";}';

            return $string;
        }

	    /**
	     * Set initial menu
	     */
        public function set_wpmm_initial_menu(){
        	$wpmm_options = get_option('wpmm_options');

	        $get_menus = wpmm_get_theme_location();
	        foreach ($get_menus as $key => $value){
		        $wpmm_options['auto_intergration_menu'][] = $key;
		        update_option('wpmm_options', $wpmm_options);
		        break;
	        }
        }
    }

    //Init
    wp_megamenu_initial_setup::init();
}