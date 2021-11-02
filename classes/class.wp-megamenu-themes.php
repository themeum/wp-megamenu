<?php

/**
 * Class wp_megamenu
 */
if ( ! class_exists('wp_megamenu_themes')) {

    class wp_megamenu_themes{
        /**
         * @return wp_megamenu_themes
         */
        public static function init(){
            $return = new self();
            return $return;
        }

        /**
         * wp_megamenu_themes constructor.
         */
        public function __construct(){
            add_action('admin_init', array($this, 'save_new_themes'));
            add_action('wp_ajax_export_wpmm_theme', array($this, 'export_wpmm_theme'));
            add_action('admin_init', array($this, 'import_theme'));

            //add_filter( 'nav_menu_meta_box_object', array($this, 'add_metabox_to_nav_menu_settings'), 10, 1);
            add_action( 'load-nav-menus.php', array($this, 'add_metabox_to_nav_menu_settings'));
            add_action('wp_ajax_wpmm_theme_delete', array($this, 'wpmm_theme_delete'));
            add_action('wp_ajax_wpmm_nav_menu_save', array($this, 'wpmm_nav_menu_save'));
            
            add_action('update_option_wpmm_options', array($this, 'update_theme_option_after_save_settins'),10,0);

        }

        public function save_new_themes(){
            if ( current_user_can( 'administrator' ) && isset( $_POST['wpmmm_save_new_theme_nonce_field'] ) && wp_verify_nonce( $_POST['wpmmm_save_new_theme_nonce_field'], 'wpmmm_save_new_theme_action' ) ) {
                if (! empty( $_POST['wpmm_theme_type'] ) ) {
                    $user_id = get_current_user_id();

                    $wpmm_theme_type = sanitize_text_field( $_POST['wpmm_theme_type'] );
                    if ( $wpmm_theme_type === 'new_theme' ) {

                        $options = ! is_serialized( $_POST['wpmm_theme_option'] ) ? wp_kses_post( maybe_serialize( ( $_POST['wpmm_theme_option'] ) ) ) : array();
                        // Create post object
                        $my_post = array(
                            'post_title'    => wp_strip_all_tags(sanitize_title( $_POST['wpmm_theme_title'] )),
                            'post_type'     => 'wpmm_theme',
                            'post_content'  => $options,
                            'post_status'   => 'publish',
                            'post_author'   => $user_id,
                        );

                        // Insert the post into the database
                        $post_id = wp_insert_post( $my_post );

                        if ( $post_id) {
                            do_action( 'wpmm_after_save_theme' );
                            wp_redirect( admin_url( 'admin.php?page=wp_megamenu_themes&section=add_theme&theme_id=' . $post_id ) );
                        }
                    } elseif ( $wpmm_theme_type === 'edit_theme' ) {
                        $theme_id = (int) sanitize_text_field( $_POST['wpmm_theme_id'] );

                        $options = ! is_serialized( $_POST['wpmm_theme_option'] ) ? wp_kses_post( maybe_serialize( ( $_POST['wpmm_theme_option'] ) ) ) : array();
                        // Create post object
                        $my_post = array(
                            'ID'            => $theme_id,
                            'post_title'    => wp_strip_all_tags( sanitize_title( $_POST['wpmm_theme_title'] ) ),
                            'post_content'  => $options,
                        );
                        wp_update_post( $my_post );

                        do_action( 'wpmm_after_save_theme' );

                        add_action( 'admin_notices', array( $this, 'wpmm_theme_updated_notice__success' ) );
                    }
                }
            }
        }

        public function export_wpmm_theme(){
            
            $wpmmm_save_new_theme_nonce_field = isset($_POST['wpmmm_save_new_theme_nonce_field']) ? $_POST['wpmmm_save_new_theme_nonce_field'] : false;

            if ( ! current_user_can( 'administrator' ) || ! isset( $_POST['wpmmm_save_new_theme_nonce_field'] ) || ! wp_verify_nonce( $wpmmm_save_new_theme_nonce_field, 'wpmm_check_security' ) ) {
                return;
			}

            if ( ! empty($_POST['action']) && $_POST['action'] === 'export_wpmm_theme' && ! empty($_POST['theme_id'])){
                
                $theme_id =  (int) $_POST['theme_id'];
                $theme = get_post( $theme_id );
                if ( $theme ) {
                    $export_content = array(
                        'post_title'    => $theme->post_title,
                        'post_content'  => $theme->post_content,
                        'post_type'     => $theme->post_type,
                        'post_status'   => $theme->post_status,
                    );

                    $handle = fopen($theme->post_name.'.txt', "w");
                    fwrite($handle, serialize($export_content));
                    fclose($handle);

                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename='.basename($theme->post_name.'.txt'));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($theme->post_name.'.txt'));
                    readfile($theme->post_name.'.txt');
                    exit();
                }
            }
        }

        public function import_theme(){
            if ( isset( $_POST['wpmmm_import_theme_nonce_field'] ) && wp_verify_nonce( $_POST['wpmmm_import_theme_nonce_field'], 'wpmmm_import_theme_action' )) {
                $uploaded_file = $_FILES['wpmm_theme_import_file'];
                if ( $uploaded_file['error'] == 0 ) {
                    $wp_check_filetype = wp_check_filetype( $uploaded_file['name']);
	                $serilized_data = file_get_contents($uploaded_file['tmp_name']);
	                if (wpmm_is_serialized($serilized_data)) {
		                $post_data = unserialize($serilized_data);
		                $required_keys = array('post_title', 'post_content', 'post_type', 'post_status');

		                if (array_keys_exist($required_keys, $post_data)){
			                $imported_post_id = wp_insert_post($post_data);
			                add_action('admin_notices', array($this, 'theme_upload_success'));
		                }else{
			                die('Array keys not exists properly, <br />'. implode(', ', $required_keys));
		                }
	                } else {
		                die('not serialized data properly');
	                }
                }
            }
        }

        public function theme_upload_error(){
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e( 'Woops! Something went wrong, there a issue with your uploaded file', 'wp-megamenu' );
                    ?></p>
            </div>
            <?php
        }
        public function theme_upload_success(){
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Done! Theme imported', 'wp-megamenu' );
                    ?></p>
            </div>
            <?php
        }

        function wpmm_theme_added_notice__success() {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'WP Megamenu theme has been added', 'wp-megamenu' ); ?></p>
            </div>
            <?php
        }
        function wpmm_theme_updated_notice__success() {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'WP Megamenu theme has been updated', 'wp-megamenu' ); ?></p>
            </div>
            <?php
        }

        function add_metabox_to_nav_menu_settings( ) {
            add_meta_box( 'wpmm-nav-themes-metabox', __( 'Mega Menu Settings', 'wp-megamenu'), array($this, 'wp_megamenu_themes_meta_box'), 'nav-menus', 'side', 'high' );
        }


        public function wp_megamenu_themes_meta_box(){
            include WPMM_DIR.'views/admin/themes_metabox_nav_menu.php';
        }

        /**
         * Delete wpmm theme
         */
        public function wpmm_theme_delete(){
            if(! current_user_can('administrator')) {
                return;
            }
            check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );
            $theme_id = (int) sanitize_text_field($_POST['theme_id']);
            wp_delete_post($theme_id, true);
            wp_send_json_success(array('msg'=> __('Successfully deleted theme') ));
        }

        public function wpmm_nav_menu_save(){
            if(! current_user_can('administrator')) {
                return;
            }
            check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );
            $menu_id = (int) sanitize_text_field($_POST['menu_id']);
            $selected_theme = (int) sanitize_text_field($_POST['selected_theme']);


            $wpmm_settings_json_string = $_POST['wpmm_settings'];
	        $wpmm_settings_array = json_decode( stripslashes( $wpmm_settings_json_string ), true );

	        $settings = array();

	        foreach ( $wpmm_settings_array as $index => $value ) {
		        $name = $value['name'];

		        // find values between square brackets
		        preg_match_all( "/\[(.*?)\]/", $name, $matches );

		        if ( isset( $matches[1][0] ) && isset( $matches[1][1] ) ) {
			        $location = $matches[1][0];
			        $setting = $matches[1][1];

			        $settings[$location][$setting] = $value['value'];
		        }
	        }

	        $wpmm_option = get_option('wpmm_options');
	        $new_settings = array_merge($wpmm_option, $settings);
	        update_option('wpmm_options', $new_settings);

	        update_term_meta($menu_id, 'wpmm_nav_options', array('theme_id' => $selected_theme));

            //Change theme css also
            do_action('wpmm_after_save_theme');

            wp_send_json_success(array('msg'=> __('Theme has been selected') ));
        }


        public function update_theme_option_after_save_settins(){
            //Change theme css also
            do_action('wpmm_after_save_theme');
        }


    }
}

wp_megamenu_themes::init();