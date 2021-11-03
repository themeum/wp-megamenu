<?php

if ( ! function_exists('wpmm_get_theme_location')){
    function wpmm_get_theme_location(){
        $locations = get_registered_nav_menus();
        $menus = get_nav_menu_locations();
        
        foreach( $locations as $key => $location_name ){
            if( isset( $menus[$key] ) && $menus[$key] ){
                $locations[$key].= ' <span class="wpmm-assigned"><strong>( ' . wp_get_nav_menu_object( $menus[$key] )->name .' )</strong></span>';
            }
            else{
                $locations[$key].= ' <span class="wpmm-no-assigned">('.__('No menu assigned', 'wp-megamenu').')</span>';
            }
        }
        return $locations;
    }
}

if ( ! function_exists('get_wpmm_option')){
    function get_wpmm_option( $id ) {
        $options = get_option( 'wpmm_options' );
        if ( isset( $options[$id] ) ) {
            return $options[$id];
        }
        return false;
    }
}

if ( ! function_exists('wpmm_unit_to_int')){
    function wpmm_unit_to_int( $unit_value ) {
        $unit = (int) str_replace('px', '', $unit_value);
        return $unit;
    }
}

if ( ! function_exists('print_row')){
    function print_row( $data ) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if ( ! function_exists('wpmm_verify_nonce')){
    function wpmm_verify_nonce( $data ) {
        if ( ! isset( $_POST['wpmm_nonce_field'] ) || ! wp_verify_nonce( $_POST['wpmm_nonce_field'], 'wpmm_nonce_action' )) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }
    }
}
if ( ! function_exists('wpmm_item_settings_input')){
    function wpmm_item_settings_input( $field_name ) {
        if ( ! empty($_POST['options'][$field_name] )){
            return sanitize_text_field($_POST['options'][$field_name]);
        }
        return false;
    }
}

if ( ! function_exists('wpmm_get_item_settings')){
    function wpmm_get_item_settings($menu_item_id = 0, $field_name = null, $default = false ) {
        if ($menu_item_id && $field_name){
            $get_menu_settings = maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
            if ( isset($get_menu_settings['options'][$field_name])){
                return $get_menu_settings['options'][$field_name];
            }
        }
        return $default;
    }
}


/**
 * @param $key
 * @param $theme_id
 * @return null
 *
 * @function get_wpmm_theme_option($key, $theme_id);
 */
if ( ! function_exists('get_wpmm_theme_option')){
    function get_wpmm_theme_option($key, $theme_id){
        if (empty($key)){
            return null;
        }
        if ($theme_id){
            $post = get_post($theme_id);
            if (empty($post)){
                return null;
            }
            $options = unserialize($post->post_content);
            if (array_key_exists($key, $options)){
                return $options[$key];
            }else{
                if ($key === 'wpmm_theme_title'){
                    return $post->post_title;
                }
                if ($key === 'wpmm_theme_name'){
                    return $post->post_name;
                }
                return null;
            }
        }else{
            return null;
        }
    }
}

/**
 * @param $theme_id
 * @return mixed|null
 *
 * Return theme options as array
 */
if ( ! function_exists('get_wpmm_theme_full_options_as_array')){
    function get_wpmm_theme_full_options_as_array($theme_id){
        if ($theme_id){
            $post = get_post($theme_id);
            if ( ! empty($post)){
                $options = unserialize($post->post_content);
                return $options;
            }
        }
        return null;
    }
}

/**
 * @param $key
 * @param $options_array
 * @return null
 *
 * for efficiency of query just once full options instead of every time load real query from database
 */
if ( ! function_exists('get_wpmm_theme_option_from_array')){

    function get_wpmm_theme_option_from_array($key, $options_array){
        if (empty($key)){
            return null;
        }
        if ( ! empty($options_array) && count($options_array) ){
            $options = $options_array;
            if (array_key_exists($key, $options)){
                return $options[$key];
            }
        }
        return null;
    }
}

/**
 * @return string
 * @columns for panel settings
 */

if ( ! function_exists('wpmm_item_panel_columns')){
    function wpmm_item_panel_columns($menu_item_id = 0){
        $saved_column = '';
        if ($menu_item_id){
            $get_menu_settings = get_post_meta($menu_item_id, 'wpmm_layout', true);

            if ( ! empty($get_menu_settings['options']['panel_column'])){
                $saved_column = $get_menu_settings['options']['panel_column'];
            }
        }

        $output = '';
        $output .= '<select id="wpmm_panel_column_selector" name="options[wpmm_panel_column]">';
        $output .= '<option value="">'.__('Select Column', 'wp-megamenu') . '</option>';
        for ($i=1; $i<10; $i++){
            $output .= '<option value="'.$i.'" '.selected($i, $saved_column, false).' >'.$i.' '.__('Columns',
                    'wp-megamenu') . '</option>';
        }
        $output .= '</select>';
        return $output;
    }
}
if ( ! function_exists('wpmm_default_theme_id')){
	function wpmm_default_theme_id(){
		global $wpdb;

		$wpmm_first_theme = $wpdb->get_row( "SELECT * FROM {$wpdb->posts} WHERE post_title = 'classic-themes' && post_type = 'wpmm_theme' ORDER BY ID ASC LIMIT 1 " );

		return (int) $wpmm_first_theme->ID;
	}
}


/**
 * @param $nav_or_term_id
 * @return null || wpmm_theme_id, (int)
 */
if ( ! function_exists('wpmm_theme_by_selected_nav_id')){
    function wpmm_theme_by_selected_nav_id($nav_or_term_id){
        $selected_nav_theme = get_term_meta($nav_or_term_id, 'wpmm_nav_options', true);
	    //var_dump($selected_nav_theme);

        $selected_theme_id = null;
        if ( ! empty($selected_nav_theme)){
            $selected_nav_theme = maybe_unserialize($selected_nav_theme);
            $selected_theme_id = (int) $selected_nav_theme['theme_id'];
        }
/*
        if ($selected_theme_id === null){
	        $selected_theme_id = wpmm_default_theme_id();
        }*/

        return $selected_theme_id;
    }
}

if ( ! function_exists('wpmm_get_post_meta_by_keys')){
    function wpmm_get_post_meta_by_keys($key){
        if ( ! $key){
            return false;
        }
        global $wpdb;
        $get_keys = $wpdb->get_results("select * from {$wpdb->postmeta} WHERE meta_key = '{$key}' ");
        return $get_keys;
    }
}


if ( ! function_exists('wpmm_dashicons')) {
    function wpmm_dashicons(){
        $icons = array(
            'dashicons-menu' => __('Menu', 'wp-megamenu'),
            'dashicons-dashboard' => __('Dashboard', 'wp-megamenu'),
            'dashicons-admin-site' => __('Admin Site', 'wp-megamenu'),
            'dashicons-admin-media' => __('Admin Media', 'wp-megamenu'),
            'dashicons-admin-page' => __('Admin Page', 'wp-megamenu'),
            'dashicons-admin-comments' => __('Admin Comments', 'wp-megamenu'),
            'dashicons-admin-appearance' => __('Admin Appearance', 'wp-megamenu'),
            'dashicons-admin-plugins' => __('Admin Plugins', 'wp-megamenu'),
            'dashicons-admin-users' => __('Admin Users', 'wp-megamenu'),
            'dashicons-admin-tools' => __('Admin Tools', 'wp-megamenu'),
            'dashicons-admin-settings' => __('Admin Settings', 'wp-megamenu'),
            'dashicons-admin-network' => __('Admin Network', 'wp-megamenu'),
            'dashicons-admin-generic' => __('Admin Generic', 'wp-megamenu'),
            'dashicons-admin-home' => __('Admin Home', 'wp-megamenu'),
            'dashicons-admin-collapse' => __('Admin Collapse', 'wp-megamenu'),
            'dashicons-admin-links' => __('Admin Links', 'wp-megamenu'),
            'dashicons-admin-post' => __('Admin Post', 'wp-megamenu'),
            'dashicons-format-standard' => __('Admin Plugins', 'wp-megamenu'),
            'dashicons-format-image' => __('Image Post Format', 'wp-megamenu'),
            'dashicons-format-gallery' => __('Gallery Post Format', 'wp-megamenu'),
            'dashicons-format-audio' => __('Audio Post Format', 'wp-megamenu'),
            'dashicons-format-video' => __('Video Post Format', 'wp-megamenu'),
            'dashicons-format-links' => __('Link Post Format', 'wp-megamenu'),
            'dashicons-format-chat' => __('Chat Post Format', 'wp-megamenu'),
            'dashicons-format-status' => __('Status Post Format', 'wp-megamenu'),
            'dashicons-format-aside' => __('Aside Post Format', 'wp-megamenu'),
            'dashicons-format-quote' => __('Quote Post Format', 'wp-megamenu'),
            'dashicons-welcome-write-blog' => __('Welcome Write Blog', 'wp-megamenu'),
            'dashicons-welcome-edit-page' => __('Welcome Edit Page', 'wp-megamenu'),
            'dashicons-welcome-add-page' => __('Welcome Add Page', 'wp-megamenu'),
            'dashicons-welcome-view-site' => __('Welcome View Site', 'wp-megamenu'),
            'dashicons-welcome-widgets-menus' => __('Welcome Widget Menus', 'wp-megamenu'),
            'dashicons-welcome-comments' => __('Welcome Comments', 'wp-megamenu'),
            'dashicons-welcome-learn-more' => __('Welcome Learn More', 'wp-megamenu'),
            'dashicons-image-crop' => __('Image Crop', 'wp-megamenu'),
            'dashicons-image-rotate-left' => __('Image Rotate Left', 'wp-megamenu'),
            'dashicons-image-rotate-right' => __('Image Rotate Right', 'wp-megamenu'),
            'dashicons-image-flip-vertical' => __('Image Flip Vertical', 'wp-megamenu'),
            'dashicons-image-flip-horizontal' => __('Image Flip Horizontal', 'wp-megamenu'),
            'dashicons-undo' => __('Undo', 'wp-megamenu'),
            'dashicons-redo' => __('Redo', 'wp-megamenu'),
            'dashicons-editor-bold' => __('Editor Bold', 'wp-megamenu'),
            'dashicons-editor-italic' => __('Editor Italic', 'wp-megamenu'),
            'dashicons-editor-ul' => __('Editor UL', 'wp-megamenu'),
            'dashicons-editor-ol' => __('Editor OL', 'wp-megamenu'),
            'dashicons-editor-quote' => __('Editor Quote', 'wp-megamenu'),
            'dashicons-editor-alignleft' => __('Editor Align Left', 'wp-megamenu'),
            'dashicons-editor-aligncenter' => __('Editor Align Center', 'wp-megamenu'),
            'dashicons-editor-alignright' => __('Editor Align Right', 'wp-megamenu'),
            'dashicons-editor-insertmore' => __('Editor Insert More', 'wp-megamenu'),
            'dashicons-editor-spellcheck' => __('Editor Spell Check', 'wp-megamenu'),
            'dashicons-editor-distractionfree' => __('Editor Distraction Free', 'wp-megamenu'),
            'dashicons-editor-expand' => __('Editor Expand', 'wp-megamenu'),
            'dashicons-editor-contract' => __('Editor Contract', 'wp-megamenu'),
            'dashicons-editor-kitchensink' => __('Editor Kitchen Sink', 'wp-megamenu'),
            'dashicons-editor-underline' => __('Editor Underline', 'wp-megamenu'),
            'dashicons-editor-justify' => __('Editor Justify', 'wp-megamenu'),
            'dashicons-editor-textcolor' => __('Editor Text Colour', 'wp-megamenu'),
            'dashicons-editor-paste-word' => __('Editor Paste Word', 'wp-megamenu'),
            'dashicons-editor-paste-text' => __('Editor Paste Text', 'wp-megamenu'),
            'dashicons-editor-removeformatting' => __('Editor Remove Formatting', 'wp-megamenu'),
            'dashicons-editor-video' => __('Editor Video', 'wp-megamenu'),
            'dashicons-editor-customchar' => __('Editor Custom Character', 'wp-megamenu'),
            'dashicons-editor-outdent' => __('Editor Outdent', 'wp-megamenu'),
            'dashicons-editor-indent' => __('Editor Indent', 'wp-megamenu'),
            'dashicons-editor-help' => __('Editor Help', 'wp-megamenu'),
            'dashicons-editor-strikethrough' => __('Editor Strikethrough', 'wp-megamenu'),
            'dashicons-editor-unlink' => __('Editor Unlink', 'wp-megamenu'),
            'dashicons-editor-rtl' => __('Editor RTL', 'wp-megamenu'),
            'dashicons-editor-break' => __('Editor Break', 'wp-megamenu'),
            'dashicons-editor-code' => __('Editor Code', 'wp-megamenu'),
            'dashicons-editor-paragraph' => __('Editor Paragraph', 'wp-megamenu'),
            'dashicons-align-left' => __('Align Left', 'wp-megamenu'),
            'dashicons-align-right' => __('Align Right', 'wp-megamenu'),
            'dashicons-align-center' => __('Align Center', 'wp-megamenu'),
            'dashicons-align-none' => __('Align None', 'wp-megamenu'),
            'dashicons-lock' => __('Lock', 'wp-megamenu'),
            'dashicons-calendar' => __('Calendar', 'wp-megamenu'),
            'dashicons-visibility' => __('Visibility', 'wp-megamenu'),
            'dashicons-post-status' => __('Post Status', 'wp-megamenu'),
            'dashicons-edit' => __('Edit', 'wp-megamenu'),
            'dashicons-post-trash' => __('Post Trash', 'wp-megamenu'),
            'dashicons-trash' => __('Trash', 'wp-megamenu'),
            'dashicons-external' => __('External', 'wp-megamenu'),
            'dashicons-arrow-up' => __('Arrow Up', 'wp-megamenu'),
            'dashicons-arrow-down' => __('Arrow Down', 'wp-megamenu'),
            'dashicons-arrow-left' => __('Arrow Left', 'wp-megamenu'),
            'dashicons-arrow-right' => __('Arrow Right', 'wp-megamenu'),
            'dashicons-arrow-up-alt' => __('Arrow Up (alt)', 'wp-megamenu'),
            'dashicons-arrow-down-alt' => __('Arrow Down (alt)', 'wp-megamenu'),
            'dashicons-arrow-left-alt' => __('Arrow Left (alt)', 'wp-megamenu'),
            'dashicons-arrow-right-alt' => __('Arrow Right (alt)', 'wp-megamenu'),
            'dashicons-arrow-up-alt2' => __('Arrow Up (alt 2)', 'wp-megamenu'),
            'dashicons-arrow-down-alt2' => __('Arrow Down (alt 2)', 'wp-megamenu'),
            'dashicons-arrow-left-alt2' => __('Arrow Left (alt 2)', 'wp-megamenu'),
            'dashicons-arrow-right-alt2' => __('Arrow Right (alt 2)', 'wp-megamenu'),
            'dashicons-leftright' => __('Arrow Left-Right', 'wp-megamenu'),
            'dashicons-sort' => __('Sort', 'wp-megamenu'),
            'dashicons-randomize' => __('Randomise', 'wp-megamenu'),
            'dashicons-list-view' => __('List View', 'wp-megamenu'),
            'dashicons-exerpt-view' => __('Excerpt View', 'wp-megamenu'),
            'dashicons-hammer' => __('Hammer', 'wp-megamenu'),
            'dashicons-art' => __('Art', 'wp-megamenu'),
            'dashicons-migrate' => __('Migrate', 'wp-megamenu'),
            'dashicons-performance' => __('Performance', 'wp-megamenu'),
            'dashicons-universal-access' => __('Universal Access', 'wp-megamenu'),
            'dashicons-universal-access-alt' => __('Universal Access (alt)', 'wp-megamenu'),
            'dashicons-tickets' => __('Tickets', 'wp-megamenu'),
            'dashicons-nametag' => __('Name Tag', 'wp-megamenu'),
            'dashicons-clipboard' => __('Clipboard', 'wp-megamenu'),
            'dashicons-heart' => __('Heart', 'wp-megamenu'),
            'dashicons-megaphone' => __('Megaphone', 'wp-megamenu'),
            'dashicons-schedule' => __('Schedule', 'wp-megamenu'),
            'dashicons-wordpress' => __('WordPress', 'wp-megamenu'),
            'dashicons-wordpress-alt' => __('WordPress (alt)', 'wp-megamenu'),
            'dashicons-pressthis' => __('Press This', 'wp-megamenu'),
            'dashicons-update' => __('Update', 'wp-megamenu'),
            'dashicons-screenoptions' => __('Screen Options', 'wp-megamenu'),
            'dashicons-info' => __('Info', 'wp-megamenu'),
            'dashicons-cart' => __('Cart', 'wp-megamenu'),
            'dashicons-feedback' => __('Feedback', 'wp-megamenu'),
            'dashicons-cloud' => __('Cloud', 'wp-megamenu'),
            'dashicons-translation' => __('Translation', 'wp-megamenu'),
            'dashicons-tag' => __('Tag', 'wp-megamenu'),
            'dashicons-category' => __('Category', 'wp-megamenu'),
            'dashicons-archive' => __('Archive', 'wp-megamenu'),
            'dashicons-tagcloud' => __('Tag Cloud', 'wp-megamenu'),
            'dashicons-text' => __('Text', 'wp-megamenu'),
            'dashicons-media-archive' => __('Media Archive', 'wp-megamenu'),
            'dashicons-media-audio' => __('Media Audio', 'wp-megamenu'),
            'dashicons-media-code' => __('Media Code)', 'wp-megamenu'),
            'dashicons-media-default' => __('Media Default', 'wp-megamenu'),
            'dashicons-media-document' => __('Media Document', 'wp-megamenu'),
            'dashicons-media-interactive' => __('Media Interactive', 'wp-megamenu'),
            'dashicons-media-spreadsheet' => __('Media Spreadsheet', 'wp-megamenu'),
            'dashicons-media-text' => __('Media Text', 'wp-megamenu'),
            'dashicons-media-video' => __('Media Video', 'wp-megamenu'),
            'dashicons-playlist-audio' => __('Audio Playlist', 'wp-megamenu'),
            'dashicons-playlist-video' => __('Video Playlist', 'wp-megamenu'),
            'dashicons-yes' => __('Yes', 'wp-megamenu'),
            'dashicons-no' => __('No', 'wp-megamenu'),
            'dashicons-no-alt' => __('No (alt)', 'wp-megamenu'),
            'dashicons-plus' => __('Plus', 'wp-megamenu'),
            'dashicons-plus-alt' => __('Plus (alt)', 'wp-megamenu'),
            'dashicons-minus' => __('Minus', 'wp-megamenu'),
            'dashicons-dismiss' => __('Dismiss', 'wp-megamenu'),
            'dashicons-marker' => __('Marker', 'wp-megamenu'),
            'dashicons-star-filled' => __('Star Filled', 'wp-megamenu'),
            'dashicons-star-half' => __('Star Half', 'wp-megamenu'),
            'dashicons-star-empty' => __('Star Empty', 'wp-megamenu'),
            'dashicons-flag' => __('Flag', 'wp-megamenu'),
            'dashicons-share' => __('Share', 'wp-megamenu'),
            'dashicons-share1' => __('Share 1', 'wp-megamenu'),
            'dashicons-share-alt' => __('Share (alt)', 'wp-megamenu'),
            'dashicons-share-alt2' => __('Share (alt 2)', 'wp-megamenu'),
            'dashicons-twitter' => __('twitter', 'wp-megamenu'),
            'dashicons-rss' => __('RSS', 'wp-megamenu'),
            'dashicons-email' => __('Email', 'wp-megamenu'),
            'dashicons-email-alt' => __('Email (alt)', 'wp-megamenu'),
            'dashicons-facebook' => __('Facebook', 'wp-megamenu'),
            'dashicons-facebook-alt' => __('Facebook (alt)', 'wp-megamenu'),
            'dashicons-networking' => __('Networking', 'wp-megamenu'),
            'dashicons-googleplus' => __('Google+', 'wp-megamenu'),
            'dashicons-location' => __('Location', 'wp-megamenu'),
            'dashicons-location-alt' => __('Location (alt)', 'wp-megamenu'),
            'dashicons-camera' => __('Camera', 'wp-megamenu'),
            'dashicons-images-alt' => __('Images', 'wp-megamenu'),
            'dashicons-images-alt2' => __('Images Alt', 'wp-megamenu'),
            'dashicons-video-alt' => __('Video (alt)', 'wp-megamenu'),
            'dashicons-video-alt2' => __('Video (alt 2)', 'wp-megamenu'),
            'dashicons-video-alt3' => __('Video (alt 3)', 'wp-megamenu'),
            'dashicons-vault' => __('Vault', 'wp-megamenu'),
            'dashicons-shield' => __('Shield', 'wp-megamenu'),
            'dashicons-shield-alt' => __('Shield (alt)', 'wp-megamenu'),
            'dashicons-sos' => __('SOS', 'wp-megamenu'),
            'dashicons-search' => __('Search', 'wp-megamenu'),
            'dashicons-slides' => __('Slides', 'wp-megamenu'),
            'dashicons-analytics' => __('Analytics', 'wp-megamenu'),
            'dashicons-chart-pie' => __('Pie Chart', 'wp-megamenu'),
            'dashicons-chart-bar' => __('Bar Chart', 'wp-megamenu'),
            'dashicons-chart-line' => __('Line Chart', 'wp-megamenu'),
            'dashicons-chart-area' => __('Area Chart', 'wp-megamenu'),
            'dashicons-groups' => __('Groups', 'wp-megamenu'),
            'dashicons-businessman' => __('Businessman', 'wp-megamenu'),
            'dashicons-id' => __('ID', 'wp-megamenu'),
            'dashicons-id-alt' => __('ID (alt)', 'wp-megamenu'),
            'dashicons-products' => __('Products', 'wp-megamenu'),
            'dashicons-awards' => __('Awards', 'wp-megamenu'),
            'dashicons-forms' => __('Forms', 'wp-megamenu'),
            'dashicons-testimonial' => __('Testimonial', 'wp-megamenu'),
            'dashicons-portfolio' => __('Portfolio', 'wp-megamenu'),
            'dashicons-book' => __('Book', 'wp-megamenu'),
            'dashicons-book-alt' => __('Book (alt)', 'wp-megamenu'),
            'dashicons-download' => __('Download', 'wp-megamenu'),
            'dashicons-upload' => __('Upload', 'wp-megamenu'),
            'dashicons-backup' => __('Backup', 'wp-megamenu'),
            'dashicons-clock' => __('Clock', 'wp-megamenu'),
            'dashicons-lightbulb' => __('Lightbulb', 'wp-megamenu'),
            'dashicons-microphone' => __('Microphone', 'wp-megamenu'),
            'dashicons-desktop' => __('Desktop', 'wp-megamenu'),
            'dashicons-tablet' => __('Tablet', 'wp-megamenu'),
            'dashicons-smartphone' => __('Smartphone', 'wp-megamenu'),
            'dashicons-smiley' => __('Smiley', 'wp-megamenu')
        );

        return $icons;
    }
}


if ( ! function_exists('wpmm_font_awesome')) {
    function wpmm_font_awesome(){
        /**
         * Font Awesome 4.7 icons array
         * http://fontawesome.io/cheatsheet/
         *
         * @version 4.7.0
         * @date 14.11.2016.
         */

        $icons = array (
            0 => 'fa-500px',
            1 => 'fa-address-book',
            2 => 'fa-address-book-o',
            3 => 'fa-address-card',
            4 => 'fa-address-card-o',
            5 => 'fa-adjust',
            6 => 'fa-adn',
            7 => 'fa-align-center',
            8 => 'fa-align-justify',
            9 => 'fa-align-left',
            10 => 'fa-align-right',
            11 => 'fa-amazon',
            12 => 'fa-ambulance',
            13 => 'fa-american-sign-language-interpreting',
            14 => 'fa-anchor',
            15 => 'fa-android',
            16 => 'fa-angellist',
            17 => 'fa-angle-double-down',
            18 => 'fa-angle-double-left',
            19 => 'fa-angle-double-right',
            20 => 'fa-angle-double-up',
            21 => 'fa-angle-down',
            22 => 'fa-angle-left',
            23 => 'fa-angle-right',
            24 => 'fa-angle-up',
            25 => 'fa-apple',
            26 => 'fa-archive',
            27 => 'fa-area-chart',
            28 => 'fa-arrow-circle-down',
            29 => 'fa-arrow-circle-left',
            30 => 'fa-arrow-circle-o-down',
            31 => 'fa-arrow-circle-o-left',
            32 => 'fa-arrow-circle-o-right',
            33 => 'fa-arrow-circle-o-up',
            34 => 'fa-arrow-circle-right',
            35 => 'fa-arrow-circle-up',
            36 => 'fa-arrow-down',
            37 => 'fa-arrow-left',
            38 => 'fa-arrow-right',
            39 => 'fa-arrow-up',
            40 => 'fa-arrows',
            41 => 'fa-arrows-alt',
            42 => 'fa-arrows-h',
            43 => 'fa-arrows-v',
            44 => 'fa-asl-interpreting',
            45 => 'fa-assistive-listening-systems',
            46 => 'fa-asterisk',
            47 => 'fa-at',
            48 => 'fa-audio-description',
            49 => 'fa-automobile',
            50 => 'fa-backward',
            51 => 'fa-balance-scale',
            52 => 'fa-ban',
            53 => 'fa-bandcamp',
            54 => 'fa-bank',
            55 => 'fa-bar-chart',
            56 => 'fa-bar-chart-o',
            57 => 'fa-barcode',
            58 => 'fa-bars',
            59 => 'fa-bath',
            60 => 'fa-bathtub',
            61 => 'fa-battery',
            62 => 'fa-battery-0',
            63 => 'fa-battery-1',
            64 => 'fa-battery-2',
            65 => 'fa-battery-3',
            66 => 'fa-battery-4',
            67 => 'fa-battery-empty',
            68 => 'fa-battery-full',
            69 => 'fa-battery-half',
            70 => 'fa-battery-quarter',
            71 => 'fa-battery-three-quarters',
            72 => 'fa-bed',
            73 => 'fa-beer',
            74 => 'fa-behance',
            75 => 'fa-behance-square',
            76 => 'fa-bell',
            77 => 'fa-bell-o',
            78 => 'fa-bell-slash',
            79 => 'fa-bell-slash-o',
            80 => 'fa-bicycle',
            81 => 'fa-binoculars',
            82 => 'fa-birthday-cake',
            83 => 'fa-bitbucket',
            84 => 'fa-bitbucket-square',
            85 => 'fa-bitcoin',
            86 => 'fa-black-tie',
            87 => 'fa-blind',
            88 => 'fa-bluetooth',
            89 => 'fa-bluetooth-b',
            90 => 'fa-bold',
            91 => 'fa-bolt',
            92 => 'fa-bomb',
            93 => 'fa-book',
            94 => 'fa-bookmark',
            95 => 'fa-bookmark-o',
            96 => 'fa-braille',
            97 => 'fa-briefcase',
            98 => 'fa-btc',
            99 => 'fa-bug',
            100 => 'fa-building',
            101 => 'fa-building-o',
            102 => 'fa-bullhorn',
            103 => 'fa-bullseye',
            104 => 'fa-bus',
            105 => 'fa-buysellads',
            106 => 'fa-cab',
            107 => 'fa-calculator',
            108 => 'fa-calendar',
            109 => 'fa-calendar-check-o',
            110 => 'fa-calendar-minus-o',
            111 => 'fa-calendar-o',
            112 => 'fa-calendar-plus-o',
            113 => 'fa-calendar-times-o',
            114 => 'fa-camera',
            115 => 'fa-camera-retro',
            116 => 'fa-car',
            117 => 'fa-caret-down',
            118 => 'fa-caret-left',
            119 => 'fa-caret-right',
            120 => 'fa-caret-square-o-down',
            121 => 'fa-caret-square-o-left',
            122 => 'fa-caret-square-o-right',
            123 => 'fa-caret-square-o-up',
            124 => 'fa-caret-up',
            125 => 'fa-cart-arrow-down',
            126 => 'fa-cart-plus',
            127 => 'fa-cc',
            128 => 'fa-cc-amex',
            129 => 'fa-cc-diners-club',
            130 => 'fa-cc-discover',
            131 => 'fa-cc-jcb',
            132 => 'fa-cc-mastercard',
            133 => 'fa-cc-paypal',
            134 => 'fa-cc-stripe',
            135 => 'fa-cc-visa',
            136 => 'fa-certificate',
            137 => 'fa-chain',
            138 => 'fa-chain-broken',
            139 => 'fa-check',
            140 => 'fa-check-circle',
            141 => 'fa-check-circle-o',
            142 => 'fa-check-square',
            143 => 'fa-check-square-o',
            144 => 'fa-chevron-circle-down',
            145 => 'fa-chevron-circle-left',
            146 => 'fa-chevron-circle-right',
            147 => 'fa-chevron-circle-up',
            148 => 'fa-chevron-down',
            149 => 'fa-chevron-left',
            150 => 'fa-chevron-right',
            151 => 'fa-chevron-up',
            152 => 'fa-child',
            153 => 'fa-chrome',
            154 => 'fa-circle',
            155 => 'fa-circle-o',
            156 => 'fa-circle-o-notch',
            157 => 'fa-circle-thin',
            158 => 'fa-clipboard',
            159 => 'fa-clock-o',
            160 => 'fa-clone',
            161 => 'fa-close',
            162 => 'fa-cloud',
            163 => 'fa-cloud-download',
            164 => 'fa-cloud-upload',
            165 => 'fa-cny',
            166 => 'fa-code',
            167 => 'fa-code-fork',
            168 => 'fa-codepen',
            169 => 'fa-codiepie',
            170 => 'fa-coffee',
            171 => 'fa-cog',
            172 => 'fa-cogs',
            173 => 'fa-columns',
            174 => 'fa-comment',
            175 => 'fa-comment-o',
            176 => 'fa-commenting',
            177 => 'fa-commenting-o',
            178 => 'fa-comments',
            179 => 'fa-comments-o',
            180 => 'fa-compass',
            181 => 'fa-compress',
            182 => 'fa-connectdevelop',
            183 => 'fa-contao',
            184 => 'fa-copy',
            185 => 'fa-copyright',
            186 => 'fa-creative-commons',
            187 => 'fa-credit-card',
            188 => 'fa-credit-card-alt',
            189 => 'fa-crop',
            190 => 'fa-crosshairs',
            191 => 'fa-css3',
            192 => 'fa-cube',
            193 => 'fa-cubes',
            194 => 'fa-cut',
            195 => 'fa-cutlery',
            196 => 'fa-dashboard',
            197 => 'fa-dashcube',
            198 => 'fa-database',
            199 => 'fa-deaf',
            200 => 'fa-deafness',
            201 => 'fa-dedent',
            202 => 'fa-delicious',
            203 => 'fa-desktop',
            204 => 'fa-deviantart',
            205 => 'fa-diamond',
            206 => 'fa-digg',
            207 => 'fa-dollar',
            208 => 'fa-dot-circle-o',
            209 => 'fa-download',
            210 => 'fa-dribbble',
            211 => 'fa-drivers-license',
            212 => 'fa-drivers-license-o',
            213 => 'fa-dropbox',
            214 => 'fa-drupal',
            215 => 'fa-edge',
            216 => 'fa-edit',
            217 => 'fa-eercast',
            218 => 'fa-eject',
            219 => 'fa-ellipsis-h',
            220 => 'fa-ellipsis-v',
            221 => 'fa-empire',
            222 => 'fa-envelope',
            223 => 'fa-envelope-o',
            224 => 'fa-envelope-open',
            225 => 'fa-envelope-open-o',
            226 => 'fa-envelope-square',
            227 => 'fa-envira',
            228 => 'fa-eraser',
            229 => 'fa-etsy',
            230 => 'fa-eur',
            231 => 'fa-euro',
            232 => 'fa-exchange',
            233 => 'fa-exclamation',
            234 => 'fa-exclamation-circle',
            235 => 'fa-exclamation-triangle',
            236 => 'fa-expand',
            237 => 'fa-expeditedssl',
            238 => 'fa-external-link',
            239 => 'fa-external-link-square',
            240 => 'fa-eye',
            241 => 'fa-eye-slash',
            242 => 'fa-eyedropper',
            243 => 'fa-fa',
            244 => 'fa-facebook',
            245 => 'fa-facebook-f',
            246 => 'fa-facebook-official',
            247 => 'fa-facebook-square',
            248 => 'fa-fast-backward',
            249 => 'fa-fast-forward',
            250 => 'fa-fax',
            251 => 'fa-feed',
            252 => 'fa-female',
            253 => 'fa-fighter-jet',
            254 => 'fa-file',
            255 => 'fa-file-archive-o',
            256 => 'fa-file-audio-o',
            257 => 'fa-file-code-o',
            258 => 'fa-file-excel-o',
            259 => 'fa-file-image-o',
            260 => 'fa-file-movie-o',
            261 => 'fa-file-o',
            262 => 'fa-file-pdf-o',
            263 => 'fa-file-photo-o',
            264 => 'fa-file-picture-o',
            265 => 'fa-file-powerpoint-o',
            266 => 'fa-file-sound-o',
            267 => 'fa-file-text',
            268 => 'fa-file-text-o',
            269 => 'fa-file-video-o',
            270 => 'fa-file-word-o',
            271 => 'fa-file-zip-o',
            272 => 'fa-files-o',
            273 => 'fa-film',
            274 => 'fa-filter',
            275 => 'fa-fire',
            276 => 'fa-fire-extinguisher',
            277 => 'fa-firefox',
            278 => 'fa-first-order',
            279 => 'fa-flag',
            280 => 'fa-flag-checkered',
            281 => 'fa-flag-o',
            282 => 'fa-flash',
            283 => 'fa-flask',
            284 => 'fa-flickr',
            285 => 'fa-floppy-o',
            286 => 'fa-folder',
            287 => 'fa-folder-o',
            288 => 'fa-folder-open',
            289 => 'fa-folder-open-o',
            290 => 'fa-font',
            291 => 'fa-font-awesome',
            292 => 'fa-fonticons',
            293 => 'fa-fort-awesome',
            294 => 'fa-forumbee',
            295 => 'fa-forward',
            296 => 'fa-foursquare',
            297 => 'fa-free-code-camp',
            298 => 'fa-frown-o',
            299 => 'fa-futbol-o',
            300 => 'fa-gamepad',
            301 => 'fa-gavel',
            302 => 'fa-gbp',
            303 => 'fa-ge',
            304 => 'fa-gear',
            305 => 'fa-gears',
            306 => 'fa-genderless',
            307 => 'fa-get-pocket',
            308 => 'fa-gg',
            309 => 'fa-gg-circle',
            310 => 'fa-gift',
            311 => 'fa-git',
            312 => 'fa-git-square',
            313 => 'fa-github',
            314 => 'fa-github-alt',
            315 => 'fa-github-square',
            316 => 'fa-gitlab',
            317 => 'fa-gittip',
            318 => 'fa-glass',
            319 => 'fa-glide',
            320 => 'fa-glide-g',
            321 => 'fa-globe',
            322 => 'fa-google',
            323 => 'fa-google-plus',
            324 => 'fa-google-plus-circle',
            325 => 'fa-google-plus-official',
            326 => 'fa-google-plus-square',
            327 => 'fa-google-wallet',
            328 => 'fa-graduation-cap',
            329 => 'fa-gratipay',
            330 => 'fa-grav',
            331 => 'fa-group',
            332 => 'fa-h-square',
            333 => 'fa-hacker-news',
            334 => 'fa-hand-grab-o',
            335 => 'fa-hand-lizard-o',
            336 => 'fa-hand-o-down',
            337 => 'fa-hand-o-left',
            338 => 'fa-hand-o-right',
            339 => 'fa-hand-o-up',
            340 => 'fa-hand-paper-o',
            341 => 'fa-hand-peace-o',
            342 => 'fa-hand-pointer-o',
            343 => 'fa-hand-rock-o',
            344 => 'fa-hand-scissors-o',
            345 => 'fa-hand-spock-o',
            346 => 'fa-hand-stop-o',
            347 => 'fa-handshake-o',
            348 => 'fa-hard-of-hearing',
            349 => 'fa-hashtag',
            350 => 'fa-hdd-o',
            351 => 'fa-header',
            352 => 'fa-headphones',
            353 => 'fa-heart',
            354 => 'fa-heart-o',
            355 => 'fa-heartbeat',
            356 => 'fa-history',
            357 => 'fa-home',
            358 => 'fa-hospital-o',
            359 => 'fa-hotel',
            360 => 'fa-hourglass',
            361 => 'fa-hourglass-1',
            362 => 'fa-hourglass-2',
            363 => 'fa-hourglass-3',
            364 => 'fa-hourglass-end',
            365 => 'fa-hourglass-half',
            366 => 'fa-hourglass-o',
            367 => 'fa-hourglass-start',
            368 => 'fa-houzz',
            369 => 'fa-html5',
            370 => 'fa-i-cursor',
            371 => 'fa-id-badge',
            372 => 'fa-id-card',
            373 => 'fa-id-card-o',
            374 => 'fa-ils',
            375 => 'fa-image',
            376 => 'fa-imdb',
            377 => 'fa-inbox',
            378 => 'fa-indent',
            379 => 'fa-industry',
            380 => 'fa-info',
            381 => 'fa-info-circle',
            382 => 'fa-inr',
            383 => 'fa-instagram',
            384 => 'fa-institution',
            385 => 'fa-internet-explorer',
            386 => 'fa-intersex',
            387 => 'fa-ioxhost',
            388 => 'fa-italic',
            389 => 'fa-joomla',
            390 => 'fa-jpy',
            391 => 'fa-jsfiddle',
            392 => 'fa-key',
            393 => 'fa-keyboard-o',
            394 => 'fa-krw',
            395 => 'fa-language',
            396 => 'fa-laptop',
            397 => 'fa-lastfm',
            398 => 'fa-lastfm-square',
            399 => 'fa-leaf',
            400 => 'fa-leanpub',
            401 => 'fa-legal',
            402 => 'fa-lemon-o',
            403 => 'fa-level-down',
            404 => 'fa-level-up',
            405 => 'fa-life-bouy',
            406 => 'fa-life-buoy',
            407 => 'fa-life-ring',
            408 => 'fa-life-saver',
            409 => 'fa-lightbulb-o',
            410 => 'fa-line-chart',
            411 => 'fa-link',
            412 => 'fa-linkedin',
            413 => 'fa-linkedin-square',
            414 => 'fa-linode',
            415 => 'fa-linux',
            416 => 'fa-list',
            417 => 'fa-list-alt',
            418 => 'fa-list-ol',
            419 => 'fa-list-ul',
            420 => 'fa-location-arrow',
            421 => 'fa-lock',
            422 => 'fa-long-arrow-down',
            423 => 'fa-long-arrow-left',
            424 => 'fa-long-arrow-right',
            425 => 'fa-long-arrow-up',
            426 => 'fa-low-vision',
            427 => 'fa-magic',
            428 => 'fa-magnet',
            429 => 'fa-mail-forward',
            430 => 'fa-mail-reply',
            431 => 'fa-mail-reply-all',
            432 => 'fa-male',
            433 => 'fa-map',
            434 => 'fa-map-marker',
            435 => 'fa-map-o',
            436 => 'fa-map-pin',
            437 => 'fa-map-signs',
            438 => 'fa-mars',
            439 => 'fa-mars-double',
            440 => 'fa-mars-stroke',
            441 => 'fa-mars-stroke-h',
            442 => 'fa-mars-stroke-v',
            443 => 'fa-maxcdn',
            444 => 'fa-meanpath',
            445 => 'fa-medium',
            446 => 'fa-medkit',
            447 => 'fa-meetup',
            448 => 'fa-meh-o',
            449 => 'fa-mercury',
            450 => 'fa-microchip',
            451 => 'fa-microphone',
            452 => 'fa-microphone-slash',
            453 => 'fa-minus',
            454 => 'fa-minus-circle',
            455 => 'fa-minus-square',
            456 => 'fa-minus-square-o',
            457 => 'fa-mixcloud',
            458 => 'fa-mobile',
            459 => 'fa-mobile-phone',
            460 => 'fa-modx',
            461 => 'fa-money',
            462 => 'fa-moon-o',
            463 => 'fa-mortar-board',
            464 => 'fa-motorcycle',
            465 => 'fa-mouse-pointer',
            466 => 'fa-music',
            467 => 'fa-navicon',
            468 => 'fa-neuter',
            469 => 'fa-newspaper-o',
            470 => 'fa-object-group',
            471 => 'fa-object-ungroup',
            472 => 'fa-odnoklassniki',
            473 => 'fa-odnoklassniki-square',
            474 => 'fa-opencart',
            475 => 'fa-openid',
            476 => 'fa-opera',
            477 => 'fa-optin-monster',
            478 => 'fa-outdent',
            479 => 'fa-pagelines',
            480 => 'fa-paint-brush',
            481 => 'fa-paper-plane',
            482 => 'fa-paper-plane-o',
            483 => 'fa-paperclip',
            484 => 'fa-paragraph',
            485 => 'fa-paste',
            486 => 'fa-pause',
            487 => 'fa-pause-circle',
            488 => 'fa-pause-circle-o',
            489 => 'fa-paw',
            490 => 'fa-paypal',
            491 => 'fa-pencil',
            492 => 'fa-pencil-square',
            493 => 'fa-pencil-square-o',
            494 => 'fa-percent',
            495 => 'fa-phone',
            496 => 'fa-phone-square',
            497 => 'fa-photo',
            498 => 'fa-picture-o',
            499 => 'fa-pie-chart',
            500 => 'fa-pied-piper',
            501 => 'fa-pied-piper-alt',
            502 => 'fa-pied-piper-pp',
            503 => 'fa-pinterest',
            504 => 'fa-pinterest-p',
            505 => 'fa-pinterest-square',
            506 => 'fa-plane',
            507 => 'fa-play',
            508 => 'fa-play-circle',
            509 => 'fa-play-circle-o',
            510 => 'fa-plug',
            511 => 'fa-plus',
            512 => 'fa-plus-circle',
            513 => 'fa-plus-square',
            514 => 'fa-plus-square-o',
            515 => 'fa-podcast',
            516 => 'fa-power-off',
            517 => 'fa-print',
            518 => 'fa-product-hunt',
            519 => 'fa-puzzle-piece',
            520 => 'fa-qq',
            521 => 'fa-qrcode',
            522 => 'fa-question',
            523 => 'fa-question-circle',
            524 => 'fa-question-circle-o',
            525 => 'fa-quora',
            526 => 'fa-quote-left',
            527 => 'fa-quote-right',
            528 => 'fa-ra',
            529 => 'fa-random',
            530 => 'fa-ravelry',
            531 => 'fa-rebel',
            532 => 'fa-recycle',
            533 => 'fa-reddit',
            534 => 'fa-reddit-alien',
            535 => 'fa-reddit-square',
            536 => 'fa-refresh',
            537 => 'fa-registered',
            538 => 'fa-remove',
            539 => 'fa-renren',
            540 => 'fa-reorder',
            541 => 'fa-repeat',
            542 => 'fa-reply',
            543 => 'fa-reply-all',
            544 => 'fa-resistance',
            545 => 'fa-retweet',
            546 => 'fa-rmb',
            547 => 'fa-road',
            548 => 'fa-rocket',
            549 => 'fa-rotate-left',
            550 => 'fa-rotate-right',
            551 => 'fa-rouble',
            552 => 'fa-rss',
            553 => 'fa-rss-square',
            554 => 'fa-rub',
            555 => 'fa-ruble',
            556 => 'fa-rupee',
            557 => 'fa-s15',
            558 => 'fa-safari',
            559 => 'fa-save',
            560 => 'fa-scissors',
            561 => 'fa-scribd',
            562 => 'fa-search',
            563 => 'fa-search-minus',
            564 => 'fa-search-plus',
            565 => 'fa-sellsy',
            566 => 'fa-send',
            567 => 'fa-send-o',
            568 => 'fa-server',
            569 => 'fa-share',
            570 => 'fa-share-alt',
            571 => 'fa-share-alt-square',
            572 => 'fa-share-square',
            573 => 'fa-share-square-o',
            574 => 'fa-shekel',
            575 => 'fa-sheqel',
            576 => 'fa-shield',
            577 => 'fa-ship',
            578 => 'fa-shirtsinbulk',
            579 => 'fa-shopping-bag',
            580 => 'fa-shopping-basket',
            581 => 'fa-shopping-cart',
            582 => 'fa-shower',
            583 => 'fa-sign-in',
            584 => 'fa-sign-language',
            585 => 'fa-sign-out',
            586 => 'fa-signal',
            587 => 'fa-signing',
            588 => 'fa-simplybuilt',
            589 => 'fa-sitemap',
            590 => 'fa-skyatlas',
            591 => 'fa-skype',
            592 => 'fa-slack',
            593 => 'fa-sliders',
            594 => 'fa-slideshare',
            595 => 'fa-smile-o',
            596 => 'fa-snapchat',
            597 => 'fa-snapchat-ghost',
            598 => 'fa-snapchat-square',
            599 => 'fa-snowflake-o',
            600 => 'fa-soccer-ball-o',
            601 => 'fa-sort',
            602 => 'fa-sort-alpha-asc',
            603 => 'fa-sort-alpha-desc',
            604 => 'fa-sort-amount-asc',
            605 => 'fa-sort-amount-desc',
            606 => 'fa-sort-asc',
            607 => 'fa-sort-desc',
            608 => 'fa-sort-down',
            609 => 'fa-sort-numeric-asc',
            610 => 'fa-sort-numeric-desc',
            611 => 'fa-sort-up',
            612 => 'fa-soundcloud',
            613 => 'fa-space-shuttle',
            614 => 'fa-spinner',
            615 => 'fa-spoon',
            616 => 'fa-spotify',
            617 => 'fa-square',
            618 => 'fa-square-o',
            619 => 'fa-stack-exchange',
            620 => 'fa-stack-overflow',
            621 => 'fa-star',
            622 => 'fa-star-half',
            623 => 'fa-star-half-empty',
            624 => 'fa-star-half-full',
            625 => 'fa-star-half-o',
            626 => 'fa-star-o',
            627 => 'fa-steam',
            628 => 'fa-steam-square',
            629 => 'fa-step-backward',
            630 => 'fa-step-forward',
            631 => 'fa-stethoscope',
            632 => 'fa-sticky-note',
            633 => 'fa-sticky-note-o',
            634 => 'fa-stop',
            635 => 'fa-stop-circle',
            636 => 'fa-stop-circle-o',
            637 => 'fa-street-view',
            638 => 'fa-strikethrough',
            639 => 'fa-stumbleupon',
            640 => 'fa-stumbleupon-circle',
            641 => 'fa-subscript',
            642 => 'fa-subway',
            643 => 'fa-suitcase',
            644 => 'fa-sun-o',
            645 => 'fa-superpowers',
            646 => 'fa-superscript',
            647 => 'fa-support',
            648 => 'fa-table',
            649 => 'fa-tablet',
            650 => 'fa-tachometer',
            651 => 'fa-tag',
            652 => 'fa-tags',
            653 => 'fa-tasks',
            654 => 'fa-taxi',
            655 => 'fa-telegram',
            656 => 'fa-television',
            657 => 'fa-tencent-weibo',
            658 => 'fa-terminal',
            659 => 'fa-text-height',
            660 => 'fa-text-width',
            661 => 'fa-th',
            662 => 'fa-th-large',
            663 => 'fa-th-list',
            664 => 'fa-themeisle',
            665 => 'fa-thermometer',
            666 => 'fa-thermometer-0',
            667 => 'fa-thermometer-1',
            668 => 'fa-thermometer-2',
            669 => 'fa-thermometer-3',
            670 => 'fa-thermometer-4',
            671 => 'fa-thermometer-empty',
            672 => 'fa-thermometer-full',
            673 => 'fa-thermometer-half',
            674 => 'fa-thermometer-quarter',
            675 => 'fa-thermometer-three-quarters',
            676 => 'fa-thumb-tack',
            677 => 'fa-thumbs-down',
            678 => 'fa-thumbs-o-down',
            679 => 'fa-thumbs-o-up',
            680 => 'fa-thumbs-up',
            681 => 'fa-ticket',
            682 => 'fa-times',
            683 => 'fa-times-circle',
            684 => 'fa-times-circle-o',
            685 => 'fa-times-rectangle',
            686 => 'fa-times-rectangle-o',
            687 => 'fa-tint',
            688 => 'fa-toggle-down',
            689 => 'fa-toggle-left',
            690 => 'fa-toggle-off',
            691 => 'fa-toggle-on',
            692 => 'fa-toggle-right',
            693 => 'fa-toggle-up',
            694 => 'fa-trademark',
            695 => 'fa-train',
            696 => 'fa-transgender',
            697 => 'fa-transgender-alt',
            698 => 'fa-trash',
            699 => 'fa-trash-o',
            700 => 'fa-tree',
            701 => 'fa-trello',
            702 => 'fa-tripadvisor',
            703 => 'fa-trophy',
            704 => 'fa-truck',
            705 => 'fa-try',
            706 => 'fa-tty',
            707 => 'fa-tumblr',
            708 => 'fa-tumblr-square',
            709 => 'fa-turkish-lira',
            710 => 'fa-tv',
            711 => 'fa-twitch',
            712 => 'fa-twitter',
            713 => 'fa-twitter-square',
            714 => 'fa-umbrella',
            715 => 'fa-underline',
            716 => 'fa-undo',
            717 => 'fa-universal-access',
            718 => 'fa-university',
            719 => 'fa-unlink',
            720 => 'fa-unlock',
            721 => 'fa-unlock-alt',
            722 => 'fa-unsorted',
            723 => 'fa-upload',
            724 => 'fa-usb',
            725 => 'fa-usd',
            726 => 'fa-user',
            727 => 'fa-user-circle',
            728 => 'fa-user-circle-o',
            729 => 'fa-user-md',
            730 => 'fa-user-o',
            731 => 'fa-user-plus',
            732 => 'fa-user-secret',
            733 => 'fa-user-times',
            734 => 'fa-users',
            735 => 'fa-vcard',
            736 => 'fa-vcard-o',
            737 => 'fa-venus',
            738 => 'fa-venus-double',
            739 => 'fa-venus-mars',
            740 => 'fa-viacoin',
            741 => 'fa-viadeo',
            742 => 'fa-viadeo-square',
            743 => 'fa-video-camera',
            744 => 'fa-vimeo',
            745 => 'fa-vimeo-square',
            746 => 'fa-vine',
            747 => 'fa-vk',
            748 => 'fa-volume-control-phone',
            749 => 'fa-volume-down',
            750 => 'fa-volume-off',
            751 => 'fa-volume-up',
            752 => 'fa-warning',
            753 => 'fa-wechat',
            754 => 'fa-weibo',
            755 => 'fa-weixin',
            756 => 'fa-whatsapp',
            757 => 'fa-wheelchair',
            758 => 'fa-wheelchair-alt',
            759 => 'fa-wifi',
            760 => 'fa-wikipedia-w',
            761 => 'fa-window-close',
            762 => 'fa-window-close-o',
            763 => 'fa-window-maximize',
            764 => 'fa-window-minimize',
            765 => 'fa-window-restore',
            766 => 'fa-windows',
            767 => 'fa-won',
            768 => 'fa-wordpress',
            769 => 'fa-wpbeginner',
            770 => 'fa-wpexplorer',
            771 => 'fa-wpforms',
            772 => 'fa-wrench',
            773 => 'fa-xing',
            774 => 'fa-xing-square',
            775 => 'fa-y-combinator',
            776 => 'fa-y-combinator-square',
            777 => 'fa-yahoo',
            778 => 'fa-yc',
            779 => 'fa-yc-square',
            780 => 'fa-yelp',
            781 => 'fa-yen',
            782 => 'fa-yoast',
            783 => 'fa-youtube',
            784 => 'fa-youtube-play',
            785 => 'fa-youtube-square',
        );
        return $icons;
    }
}

if(!function_exists('wpmm_icofont')){
    function wpmm_icofont() {
        return array("angry-monster","bathtub","bird-wings","bow","castle","circuit","crown-king","crown-queen","dart","disability-race","diving-goggle","eye-open","flora-flower","flora","gift-box","halloween-pumpkin","hand-power","hand-thunder","king-monster","love","magician-hat","native-american","owl-look","phoenix","blogger","bootstrap","brightkite","cloudapp","concrete5","delicious","designbump","designfloat","deviantart","digg","dotcms","dribbble","dribble","dropbox","ebuddy","ello","ember","envato","evernote","facebook-messenger","facebook","feedburner","flikr","folkd","foursquare","friendfeed","ghost","github","gnome","google-buzz","google-hangouts","google-map","google-plus","google-talk","hype-machine","instagram","kakaotalk","kickstarter","kik","kiwibox","line-messenger","line","linkedin","linux-mint","live-messenger","livejournal","magento","meetme","meetup","mixx","newsvine","nimbuss","odnoklassniki","opencart","oscommerce","pandora","photobucket","picasa","pinterest","prestashop","qik","qq","readernaut","reddit","renren","rss","shopify","silverstripe","skype","slack","slashdot","slidshare","smugmug","snapchat","soundcloud","spotify","stack-exchange","stack-overflow","steam","stumbleupon","tagged","technorati","telegram","tinder","trello","tumblr","twitch","twitter","typo3","ubercart","viber","viddler","vimeo","vine","virb","virtuemart","vk","wechat","weibo","whatsapp","xing","yahoo","yelp","youku","youtube","zencart","badminton-birdie","baseball","baseballer","basketball-hoop","basketball","billiard-ball","boot-alt-1","boot-alt-2","boot","bowling-alt","bowling","canoe","cheer-leader","climbing","corner","field-alt","field","football-alt","football-american","football","foul","goal-keeper","goal","golf-alt","golf-bag","golf-cart","golf-field","golf","golfer","helmet","hockey-alt","hockey","ice-skate","jersey-alt","jersey","jumping","kick","leg","match-review","medal-sport","offside","olympic-logo","olympic","padding","penalty-card","racer","racing-car","racing-flag-alt","racing-flag","racings-wheel","referee","refree-jersey","result-sport","rugby-ball","rugby-player","rugby","runner-alt-1","runner-alt-2","runner","score-board","skiing-man","skydiving-goggles","snow-mobile","steering","stopwatch","substitute","swimmer","table-tennis","team-alt","team","tennis-player","tennis","tracking","trophy-alt","trophy","volleyball-alt","volleyball-fire","volleyball","water-bottle","whistle-alt","whistle","win-trophy","align-center","align-left","align-right","all-caps","bold","brush","clip-board","code-alt","color-bucket","color-picker","copy-invert","copy","cut","delete-alt","edit-alt","eraser-alt","font","heading","indent","italic-alt","italic","justify-all","justify-center","justify-left","justify-right","link-broken","outdent","paper-clip","paragraph","pin","printer","redo","rotation","save","small-cap","strike-through","sub-listing","subscript","superscript","table","text-height","text-width","trash","underline","undo","air-balloon","airplane-alt","airplane","articulated-truck","auto-mobile","auto-rickshaw","bicycle-alt-1","bicycle-alt-2","bicycle","bus-alt-1","bus-alt-2","bus-alt-3","bus","cab","cable-car","car-alt-1","car-alt-2","car-alt-3","car-alt-4","car","delivery-time","fast-delivery","fire-truck-alt","fire-truck","free-delivery","helicopter","motor-bike-alt","motor-bike","motor-biker","oil-truck","rickshaw","rocket-alt-1","rocket-alt-2","rocket","sail-boat-alt-1","sail-boat-alt-2","sail-boat","scooter","sea-plane","ship-alt","ship","speed-boat","taxi","tractor","train-line","train-steam","tram","truck-alt","truck-loaded","truck","van-alt","van","yacht","5-star-hotel","air-ticket","beach-bed","beach","camping-vest","direction-sign","hill-side","hill","hotel","island-alt","island","sandals-female","sandals-male","travelling","breakdown","celsius","clouds","cloudy","dust","eclipse","fahrenheit","forest-fire","full-night","full-sunny","hail-night","hail-rainy-night","hail-rainy-sunny","hail-rainy","hail-sunny","hail-thunder-night","hail-thunder-sunny","hail-thunder","hail","hill-night","hill-sunny","hurricane","meteor","night","rainy-night","rainy-sunny","rainy-thunder","rainy","snow-alt","snow-flake","snow-temp","snow","snowy-hail","snowy-night-hail","snowy-night-rainy","snowy-night","snowy-rainy","snowy-sunny-hail","snowy-sunny-rainy","snowy-sunny","snowy-thunder-night","snowy-thunder-sunny","snowy-thunder","snowy-windy-night","snowy-windy-sunny","snowy-windy","snowy","sun-alt","sun-rise","sun-set","sun","sunny-day-temp","sunny","thunder-light","tornado","umbrella-alt","umbrella","volcano","wave","wind-scale-0","wind-scale-1","wind-scale-10","wind-scale-11","wind-scale-12","wind-scale-2","wind-scale-3","wind-scale-4","wind-scale-5","wind-scale-6","wind-scale-7","wind-scale-8","wind-scale-9","wind-waves","wind","windy-hail","windy-night","windy-raining","windy-sunny","windy-thunder-raining","windy-thunder","windy","addons","address-book","adjust","alarm","anchor","archive","at","attachment","audio","automation","badge","bag-alt","bag","ban","bar-code","bars","basket","battery-empty","battery-full","battery-half","battery-low","beaker","beard","bed","bell","beverage","bill","bin","binary","binoculars","bluetooth","bomb","book-mark","box","briefcase","broken","bucket","bucket1","bucket2","bug","building","bulb-alt","bullet","bullhorn","bullseye","calendar","camera-alt","camera","card","cart-alt","cart","cc","charging","chat","check-alt","check-circled","check","checked","children-care","clip","clock-time","close-circled","close-line-circled","close-line-squared-alt","close-line-squared","close-line","close-squared-alt","close-squared","close","cloud-download","cloud-refresh","cloud-upload","cloud","code-not-allowed","code","comment","compass-alt","compass","computer","connection","console","contacts","contrast","copyright","credit-card","crop","crown","cube","cubes","dashboard-web","dashboard","data","database-add","database-locked","database-remove","database","delete","diamond","dice-multiple","dice","disc","diskette","document-folder","download-alt","download","downloaded","drag","drag1","drag2","drag3","earth","ebook","edit","eject","email","envelope-open","envelope","eraser","error","excavator","exchange","exclamation-circle","exclamation-square","exclamation-tringle","exclamation","exit","expand","external-link","external","eye-alt","eye-blocked","eye-dropper","eye","favourite","fax","file-fill","film","filter","fire-alt","fire-burn","fire","flag-alt-1","flag-alt-2","flag","flame-torch","flash-light","flash","flask","focus","folder-open","folder","foot-print","garbage","gear-alt","gear","gears","gift","glass","globe","graffiti","grocery","hand","hanger","hard-disk","heart-alt","heart","history","home","horn","hour-glass","id","image","inbox","infinite","info-circle","info-square","info","institution","interface","invisible","jacket","jar","jewlery","karate","key-hole","key","label","lamp","layers","layout","leaf","leaflet","learn","lego","lens","letter","letterbox","library","license","life-bouy","life-buoy","life-jacket","life-ring","light-bulb","lighter","lightning-ray","like","line-height","link-alt","link","list","listening","listine-dots","listing-box","listing-number","live-support","location-arrow","location-pin","lock","login","logout","lollipop","long-drive","look","loop","luggage","lunch","lungs","magic-alt","magic","magnet","mail-box","mail","male","map-pins","map","maximize","measure","medicine","mega-phone","megaphone-alt","megaphone","memorial","memory-card","mic-mute","mic","military","mill","minus-circle","minus-square","minus","mobile-phone","molecule","money","moon","mop","muffin","mustache","navigation-menu","navigation","network-tower","network","news","newspaper","no-smoking","not-allowed","notebook","notepad","notification","numbered","opposite","optic","options","package","page","paint","paper-plane","paperclip","papers","pay","penguin-linux","pestle","phone-circle","phone","picture","pine","pixels","plugin","plus-circle","plus-square","plus","polygonal","power","price","print","puzzle","qr-code","queen","question-circle","question-square","question","quote-left","quote-right","random","recycle","refresh","repair","reply-all","reply","resize","responsive","retweet","road","robot","royal","rss-feed","safety","sale-discount","satellite","send-mail","server","settings-alt","settings","share-alt","share-boxed","share","shield","shopping-cart","sign-in","sign-out","signal","site-map","smart-phone","soccer","sort-alt","sort","space","spanner","speech-comments","speed-meter","spinner-alt-1","spinner-alt-2","spinner-alt-3","spinner-alt-4","spinner-alt-5","spinner-alt-6","spinner","spreadsheet","square","ssl-security","star-alt-1","star-alt-2","star","street-view","support-faq","tack-pin","tag","tags","tasks-alt","tasks","telephone","telescope","terminal","thumbs-down","thumbs-up","tick-boxed","tick-mark","ticket","tie","toggle-off","toggle-on","tools-alt-2","tools","touch","traffic-light","transparent","tree","unique-idea","unlock","unlocked","upload-alt","upload","usb-drive","usb","vector-path","verification-check","wall-clock","wall","wallet","warning-alt","warning","water-drop","web","wheelchair","wifi-alt","wifi","world","zigzag","zipped");
    }
}

if ( ! function_exists('wpmm_dropdown_indicator_icon')) {
    function wpmm_dropdown_indicator_icon()
    {
        $icon = array(
            'fa-caret-up',
            'fa-caret-down',
            'fa-caret-right',
            'fa-caret-left',

            'fa-arrow-up',
            'fa-arrow-down',
            'fa-arrow-left',
            'fa-arrow-right',

            'fa-angle-up',
            'fa-angle-down',
            'fa-angle-left',
            'fa-angle-right',
        );

        return $icon;
    }
}


/**
 * @param $value
 * @param null $result
 * @return bool
 */
if ( ! function_exists('wpmm_is_serialized')) {
    function wpmm_is_serialized($value, &$result = null){
        // Bit of a give away this one
        if (!is_string($value)) {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;') {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end = '';
        switch ($value[0]) {
            case 's':
                if ($value[$length - 2] !== '"') {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':') {
                    return false;
                }
                switch ($value[2]) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0]) {
                    return false;
                }
                break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false) {
            $result = null;
            return false;
        }
        return true;
    }
}

/**
 * @param $keys
 * @param $array
 * @return bool
 */
if ( ! function_exists('array_keys_exist')){
    function array_keys_exist($keys,$array) {
        if (count (array_intersect($keys,array_keys($array))) == count($keys)) {
            return true;
        }
    }
}

if ( ! function_exists('wpmm_nav_social_links_item')){
    function wpmm_nav_social_links_item(){
        $socials_item = array(
            'facebook',
            'twitter',
            'gplus',
            'instagram',
            'linkedin',
            'pinterest',
            'youtube',
            'dribbble',
            'behance',
            'digg',
            'vimeo',
            'stumbleupon',
            'reddit',
            'delicious',
            'skype',
            'github',
            'amazon',
            'whatsapp',
            'soundcloud',
        );
        return $socials_item;
    }
}

/**
 * @param array $array
 * @return int|null|string
 */
if ( ! function_exists('wpmm_get_array_first_key')){
    function wpmm_get_array_first_key($array = array()){
        if (! empty($array)){
            foreach ($array as $key => $value){
                return $key;
            }
        }
        return null;
    }
}


function get_attached_location_with_menu( $menu_id = 0 ) {
	if ( ! $menu_id){
		return;
	}

	$locations = array();
	$nav_menu_locations = get_nav_menu_locations();
	$nav_menus = get_registered_nav_menus();

	foreach ($nav_menus  as $id => $name ) {
		if ( isset( $nav_menu_locations[ $id ] ) && $nav_menu_locations[$id] == $menu_id ){
			$locations[$id] = $name;
		}
	}

	return $locations;
}

if ( ! function_exists('get_wpmm_option_input_checkmark')){
	function get_wpmm_option_input_checkmark($input = null){

		if ( ! empty($_POST['wpmm_options'][$input])){
			return sanitize_text_field( $_POST['wpmm_options'][$input] );
		}
		return 0;
	}
}

/**
 * Sanitize settings options
 */
function wpmm_sanitize_settings_options( $input ) {
    if ( ! empty( $_POST['wpmm_options'][$input] ) ) {
        return sanitize_text_field( $_POST['wpmm_options'][$input] );
    }

    return '';
}

/**
 * Sanitize inline css
 * 
 * @param string|mixed
 * 
 * @return string|mixed
 */
function wpmm_sanitize_inline_css_output( $style = null ) {
    // Set the allowed tags.
    $allowed_tags = array(
        'style' => array(),
    );
    // Run through wp_kses to validate the tag(s) and then return it.
    $sanitized_css = wp_kses( $style, $allowed_tags );
    return $sanitized_css;
}


/**
 * @param string $menu_id
 * @param array $args
 *
 * @return false|string|void
 */

if ( ! function_exists('wp_megamenu')) {
	function wp_megamenu( $args = array() ) {
		$args = wp_megamenu_nav_args( $args );
		if ( ! empty($args)){
			return wp_nav_menu( $args );
		}
		return '';
	}
}

/**
 * @param $atts
 *
 * @return string
 *
 *
 */
function wp_megamenu_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'menu' => '',
	), $atts, 'wp_megamenu' );
	ob_start();

	$args = array();
	if ( ! empty($atts['menu'])){
		$args['menu'] = $atts['menu'];
	}

	if ( ! empty($atts['theme_location'])){
		$args['theme_location'] = $atts['theme_location'];
	}

	if ( ! empty($atts['menu'])){
		wp_megamenu($args);
	}
	$return = ob_get_clean();
	return $return;
}
add_shortcode( 'wp_megamenu', 'wp_megamenu_shortcode' );



add_filter( 'plugin_row_meta', 'custom_plugin_row_meta', 10, 2 );

function custom_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, 'wp-megamenu.php' ) !== false ) {
        $new_links = array(
            'wppb_docs' =>  '<a href="https://www.themeum.com/docs/wp-mega-menu-introduction/" target="_blank">'.__('Docs', 'wp-megamenu').'</a>',
            'wppb_support' =>  '<a href="https://www.themeum.com/support-forums/" target="_blank">'.__('Free Support', 'wp-megamenu').'</a>'
        );

        $links = array_merge( $links, $new_links );
    }

    return $links;
}
add_filter( 'plugin_action_links_' . WPMM_BASENAME, 'plugin_action_links_callback');

function plugin_action_links_callback ( $links ) {
    $wpmm_upgrade_link = array();
    if(!defined('WPMM_PRO_VERSION')){
        $wpmm_upgrade_link = array(
            'wpmm_pro' => '<a href="https://www.themeum.com/product/wp-megamenu/#pricing?utm_source=wp_mm&utm_medium=wordpress_dashboard&utm_campaign=go_premium" target="_blank"><span style="color: #39a700eb; font-weight: bold;">'.__('Upgrade to Pro', 'wp-megamenu').'</span></a>'
        );
    }
    $wpmm_upgrade_link['wpmm_settings'] = '<a href="'. menu_page_url('wp_megamenu', false) .'">'. __('Settings', 'wp-megamenu') .'</a>';
    return array_merge( $wpmm_upgrade_link, $links);
}
add_action( 'admin_menu', 'wpmm_add_admin_menu', 502 );

 function wpmm_add_admin_menu(){
    $is_pro_activated = is_plugin_active('wp-megamenu-pro/wp-megamenu-pro.php');
    if ( ! $is_pro_activated ){
        add_submenu_page( 'wp_megamenu', __( 'Go Premium', 'wp-megamenu' ), __( '<span class="dashicons dashicons-awards wppb-go-premium-star"></span> Go Premium', 'wp-megamenu' ), 'manage_options', 'wpmm-go-premium', 'wppb_go_premium_page' );
    }
}

add_action( 'admin_init', 'wpmm_go_premium_page' );
 function wpmm_go_premium_page(){
    if ( empty( $_GET['page'] ) ) {
        return;
    }
    if ( 'wpmm-go-premium' === $_GET['page'] ) {
        wp_redirect( 'https://www.themeum.com/product/wp-megamenu/#pricing?utm_source=wp_mm&utm_medium=wordpress_sidebar&utm_campaign=go_premium' );
        die();
    }
}

add_action("wp_ajax_wpmm_rating_notice", "wpmm_rating_notice");
function wpmm_rating_notice(){
    if ( !wp_verify_nonce( $_REQUEST['wpmm_nonce'], "wpmm_check_security")) {
        exit("Unauthorized");
    }
    $type = isset($_POST['wpmm_notice_action']) ? sanitize_text_field( $_POST['wpmm_notice_action'] ) : '';
    $time = strtotime(date('Y-m-d') . '+ 10 days');
    if($type == 'dismiss'){
        $time = strtotime(date('Y-m-d') . '+ 1900 days');
    }
    update_option( 'wpmm_rating_notice_remove', $time );
    return;
}

// add_action("wp_ajax_export_wpmm_theme", "export_wpmm_theme");
// function export_wpmm_theme(){
//     print_r($_REQUEST);
// }