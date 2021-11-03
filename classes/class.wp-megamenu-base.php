<?php

/**
 * Class wp_megamenu
 */
if ( ! class_exists('wp_megamenu_base')) {

	class wp_megamenu_base{

		/**
		 * @return wp_megamenu_base
		 */
		public static function init(){
			$return = new self();
			return $return;
		}

		/**
		 * wp_megamenu_base constructor.
		 */
		public function __construct(){
			add_action('admin_enqueue_scripts',    array($this,'wpneo_enqueue_admin_script')); //Add Additional backend js and css
			add_action('wp_enqueue_scripts',    array($this,'wpneo_enqueue_frontend_script'));

			add_action( 'admin_print_footer_scripts-nav-menus.php', array( $this, 'nav_menu_footer_scripts' ) );
			add_action( 'admin_print_scripts-nav-menus.php', array( $this, 'nav_menu_scripts' ) );
			add_action( 'admin_print_styles-nav-menus.php', array( $this, 'nav_menu_styles' ) );

			add_action('admin_menu', array($this, 'wp_megamenu_admin_menus'));
			add_action('admin_init', array( $this, 'register_settings' ) );
			add_action('wp_ajax_wpmm_item_settings_load', array($this, 'wpmm_item_settings_load'));
			add_filter('wp_nav_menu_objects', array( $this, 'add_widgets_to_menu' ), 10, 2 );
			add_filter('body_class', array($this, 'add_body_classes'), 10, 1);

			add_action('wp_ajax_wpmm_menu_item_option_save', array($this, 'wpmm_menu_item_option_save'));
			add_action('wp_ajax_wpmm_icon_update', array($this, 'wpmm_icon_update'));

			add_action('wp_ajax_save_item_panel_column', array($this, 'save_item_panel_column'));
			add_action('wp_ajax_wpmm_change_menu_type', array($this, 'wpmm_change_menu_type'));
			add_action('wp_ajax_wpmm_change_strees_row', array($this, 'wpmm_change_strees_row'));
			add_action('wp_ajax_wpmm_set_menu_width', array($this, 'wpmm_set_menu_width'));
			add_action('wp_ajax_wpmm_set_strees_row_width', array($this, 'wpmm_set_strees_row_width'));

			# Layout
			add_action('wp_ajax_wpmm_save_layout', array($this, 'wpmm_save_layout'));
			add_action('wp_head', array($this,'wpmm_generate_css'));
			add_action('admin_notices', array($this,'admin_rating_notice'));
		}

        /**
		* Request for rating.
		*/
        public function admin_rating_notice(){
            $notice_time = get_option('wpmm_rating_notice_remove') ? (int) get_option('wpmm_rating_notice_remove') : 1;
            $current_time = time();
            if($current_time > $notice_time){ ?>
                    <div class="notice notice-success wpmm-review-notice">
                        <h3><?php esc_html_e('Thank you for using "WP Mega Menu!!"', 'wp-megamenu'); ?></h3>
                        <p><?php esc_html_e( 'Kindly show us some support by giving WP Mega Menu a 5 star rating. ⭐⭐⭐⭐⭐', 'wp-megamenu' ); ?></p>
                        <div class="wpmm-review-notice-button-group">
                            <a target="_blank" href="https://wordpress.org/support/plugin/wp-megamenu/reviews/?filter=5"><span>🤩</span><?php esc_html_e(' Sure Thing', 'wp-megamenu') ?></a>
                            <a class="wpmm-remove-rating-notice" data-type="remind" href="#"><span>👏</span><?php esc_html_e(' Remind Later', 'wp-megamenu'); ?></a>
                            <a class="wpmm-remove-rating-notice" data-type="dismiss" href="#"><span>😞</span><?php esc_html_e(' Please don\'t show this again', 'wp-megamenu'); ?></a>
                        </div>
                    </div>
                <?php
            }
        }


		/**
		 * admin enqueue script
		 */
		public function wpneo_enqueue_admin_script($hook){
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-slider');

			if ($hook === 'nav-menus.php'){
				do_action( 'sidebar_admin_setup' );
				do_action( 'admin_enqueue_scripts', 'widgets.php' );
				do_action( 'admin_print_styles-widgets.php' );
			}

			if ($hook === 'wp-mega-menu_page_wp_megamenu_themes' || $hook === 'wp-mega-menu-pro_page_wp_megamenu_themes'){
				wp_enqueue_media();
			}

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker-alpha', WPMM_URL .'assets/js/wpcolorpicker-alpha.js', array('wp-color-picker', 'jquery'), '1.2.2', true);

			wp_enqueue_style('wpmm-select2', WPMM_URL .'assets/select2/css/select2.min.css', false, '4.0.3');
			wp_enqueue_script( 'wpmm-select2', WPMM_URL .'assets/select2/js/select2.min.js', array('jquery'), '4.0.3',true);

			//wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script( 'wpmm_scripts_admin', WPMM_URL .'assets/js/wpmm-admin.js', array('jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-droppable', 'wp-color-picker', 'jquery-ui-slider'), WPMM_VER, true);

			wp_localize_script('wpmm_scripts_admin', 'wpmm',
				array(
					'wpmm_nonce'    => wp_create_nonce('wpmm_check_security'),
					'disable_theme' => __('Disable Theme', 'wp-megamenu'),
					'select_theme'  => __('Please select a theme', 'wp-megamenu'
					)
				));

			wp_enqueue_style('wpmm_fontawesome_css_admin', WPMM_URL .'assets/font-awesome-4.7.0/css/font-awesome.min.css', false, '4.7.0');
			wp_enqueue_style('wpmm_icofont_css_admin', WPMM_URL .'assets/icofont/icofont.min.css', false, '1.0.1');
			wp_enqueue_style('wpmm_css_admin', WPMM_URL .'assets/css/wpmm-admin.css', false, WPMM_VER);
		}

		public function wpneo_enqueue_frontend_script(){
			wp_enqueue_style('dashicons');

			//fontawesome 4
			$enable_font_awesome = get_wpmm_option('enable_font_awesome');
			if ( ! empty($enable_font_awesome) && $enable_font_awesome !== 'disable'){
				wp_enqueue_style('wpmm_fontawesome_css', WPMM_URL .'assets/font-awesome-4.7.0/css/font-awesome.min.css', false, '4.7.0');
			}

			//icofont
			$enable_icofont = get_wpmm_option('enable_icofont');
			if ( ! empty($enable_icofont) && $enable_icofont !== 'disable'){
				wp_enqueue_style('wpmm_icofont_css', WPMM_URL .'assets/icofont/icofont.min.css', false, '1.0.1');
			}
			
			wp_enqueue_style( 'wpmm_css', WPMM_URL .'assets/css/wpmm.css', array('dashicons'), WPMM_VER );
			wp_enqueue_script( 'wpmm_js', WPMM_URL .'assets/js/wpmm.js', array('jquery'), WPMM_VER, true);

	        // For Ajax URL
	        wp_enqueue_script('wpmm_js');
	        wp_localize_script( 'wpmm_js', 'ajax_objects', array( 
	            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
	            'redirecturl'       => home_url('/'),
	            'loadingmessage'    => __('Sending user info, please wait...','patrios')
	        ));

	        

			$responsive_breakpoint = get_wpmm_option('responsive_breakpoint');
			if (empty($responsive_breakpoint)){
				$responsive_breakpoint = '767px';
			}

			$wpmm_on_mobile = get_wpmm_option('disable_wpmm_on_mobile');
			if ( empty($wpmm_on_mobile) ) {
				$wpmm_on_mobile = 'false';
			}
			//Localize
			wp_localize_script('wpmm_js', 'wpmm_object', array( 'ajax_url' => admin_url('admin-ajax.php'), 'wpmm_responsive_breakpoint' => $responsive_breakpoint,'wpmm_disable_mobile' => $wpmm_on_mobile )  );

			$css_output_location = get_wpmm_option('css_output_location');
			if ($css_output_location == 'filesystem'){
				$wp_upload_dir = wp_upload_dir();
				$generated_css_path = $wp_upload_dir['basedir'].'/wp-megamenu/wp-megamenu.css';
				$generated_css_url = $wp_upload_dir['baseurl'].'/wp-megamenu/wp-megamenu.css';
				if (file_exists($generated_css_path)){
					wp_enqueue_style( 'wp_megamenu_generated_css', set_url_scheme($generated_css_url), array('wpmm_css'), WPMM_VER );
				}
			}
		}

		public function nav_menu_footer_scripts( $hook ) {
			do_action( 'admin_footer-widgets.php' );
		}
		/**
		 * Added admin scripts, to support media script. Supporting form wp-4.8
		 */
		public function nav_menu_scripts( $hook ) {
			do_action( 'admin_print_scripts-widgets.php' );
		}
		/**
		 * Added admin style, to support media style. Supporting form wp-4.8
		 */
		public function nav_menu_styles( $hook ) {
			do_action( 'admin_print_styles-widgets.php' );
		}

		public function wp_megamenu_admin_menus(){

            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            $wpmm_page_title = is_plugin_active('wp-megamenu-pro/wp-megamenu-pro.php') ? __('WP Mega Menu Pro', 'wp-megamenu') : __('WP Mega Menu', 'wp-megamenu');

			add_menu_page($wpmm_page_title,  $wpmm_page_title, 'manage_options', 'wp_megamenu', array($this, 'wp_megamenu_callback'), 'dashicons-welcome-widgets-menus' );
			add_submenu_page('wp_megamenu', __('Themes', 'wp-megamenu'),  __('Themes', 'wp-megamenu'), 'manage_options', 'wp_megamenu_themes', array($this, 'wp_megamenu_themes') );
		}

		/**
		 * calback for settings load
		 */
		public function wp_megamenu_callback(){
			include WPMM_DIR.'views/admin/settings_panel.php';
		}

		public function wp_megamenu_themes(){
			include WPMM_DIR.'views/admin/wp_megamenu_themes.php';
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * @since 1.0.0
		 */
		public function register_settings() {
			register_setting( 'wpmm_options', 'wpmm_options', array( $this, 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {
			// If we have options lets sanitize them
			if ( $options ) {
				// Checkbox
				/* if ( ! empty( $options['auto_intergration_menu'] ) ) {
					 $options['auto_intergration_menu'] = 'on';
				 } else {
					 unset( $options['auto_intergration_menu'] ); // Remove from options if not checked
				 }*/
			}

			// Return sanitized options
			return $options;
		}

		/**
		 * Show settings menu
		 */
		public function wpmm_item_settings_load(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$menu_id = (int) sanitize_text_field($_POST['menu_id']);
			$menu_item_depth = (int) sanitize_text_field($_POST['menu_item_depth']);

			//We are working with top level menu
			if ($menu_item_depth == 0) {
				//Setting item settings in the menu
				//Getting and setting sub menu in the wp MegaMenu

				$get_layout = (array) get_post_meta($menu_item_id, 'wpmm_layout', true);
				$array_menu = wp_get_nav_menu_items($menu_id);

				if (empty($get_menu_settings['menu_type'])){
					$get_menu_settings['menu_type'] = 'wpmm_dropdown_menu';
				}

				$new_menu_item_id = array();
				$unique_items = array();
				foreach ($array_menu as $m) {
					if ($m->menu_item_parent && ($m->menu_item_parent == $menu_item_id)) {
						$unique_items[$m->ID] = array('item_type' => 'menu_item', 'ID' => $m->ID, 'title' => $m->title, 'url' => $m->url, 'description' => $m->description, 'options' => array());
						$new_menu_item_id[] = $m->ID;
					}
				}

				//print_row($get_layout);
				if (! empty($get_layout['layout'])){
					foreach($get_layout['layout'] as $lkey => $all_layout){
						if (count($all_layout['row'])){
							foreach ($all_layout['row'] as $rkey => $cols){
								//print_row($cols['items']);
								foreach ($cols['items'] as $col_key => $col_item){
									//print_row($col_item);
									if ($col_item['item_type'] === 'menu_item'){
										//print_row($unique_items[$col_item['ID']]['title']);
										//print_row($unique_items['ID']['title']);
										if (array_key_exists($col_item['ID'], $unique_items)){
											//Assigning New name, if changed name of item
											$get_layout['layout'][$lkey]['row'][$rkey]['items'][$col_key]['title'] = $unique_items[$col_item['ID']]['title'];
											unset($unique_items[$col_item['ID']]);
										}
										if ( ! in_array($col_item['ID'], $new_menu_item_id)){
											unset($get_layout['layout'][$lkey]['row'][$rkey]['items'][$col_key] );
										}
										//removing check, if not in array();
									}
								}
							}
						}
					}
				}

				//die(print_row($get_layout));
				if ( ! empty($unique_items)){
					$firrst_row_key = wpmm_get_array_first_key($get_layout['layout']);
					$first_col_key = wpmm_get_array_first_key($get_layout['layout'][$firrst_row_key]['row']);

					if ( ! empty($get_layout['layout'][$firrst_row_key]['row'][$first_col_key]['items'])){
						$get_layout['layout'][$firrst_row_key]['row'][$first_col_key]['items'] = array_merge($get_layout['layout'][$firrst_row_key]['row'][$first_col_key]['items'],
							array_values($unique_items));
					}else{
						//$get_layout['layout'][0]['row'][0]['col'] = 12;
						$get_layout['layout'][0]['row'][0]['items'] = array_values($unique_items);
					}
				}

				update_post_meta($menu_item_id, 'wpmm_layout', $get_layout);
			}

			include WPMM_DIR.'views/admin/item_settings.php';
			die();
		}

		/**
		* @param $items
		* @param $args
		* @return array
		*/

		public function add_widgets_to_menu( $items, $args ) {
			if ( ! $args->walker instanceof wp_megamenu) {
				return $items;
			}
			$wpmm_widgets_factory = new wp_megamenu_widgets();

			$reserved_sub_menu_item = array();
			$parent_item = array();

			foreach ($items as $key => $item){
				$get_layout = get_post_meta($item->ID, 'wpmm_layout', true);

				//Getting sub menu item
				if ($item->menu_item_parent) {

					$reserved_sub_menu_item[$key] = $item;
					unset($items[$key]);
				}else{
					if ( ! empty($get_layout['menu_type'])) {
						if ($get_layout['menu_type'] === 'wpmm_mega_menu') {
							$parent_item[$key] = $item;
						}
					}
				}

				if ( ! empty($get_layout['menu_type'])){
					if ($get_layout['menu_type'] === 'wpmm_mega_menu'){
						//unset($items[$key]);

						if ( ! empty($get_layout['layout'])){
							$item_count = 1;
							foreach ($get_layout['layout'] as $row_key =>$row){

								if ( ! empty($row['row'])){
									//Todo: need to make a better solution to generat $big_row_int_ID Row ID
									$big_row_int_ID = rand(9999, PHP_INT_MAX)+3684567+($row_key+4)+$item->ID;

									//Add dummy li
									$items[] = (object) array(
										'menu_item_parent'  => $item->ID,
										'type'              => 'wpmm_row',
										'title'             => 'Custom Row',
										'ID'                => $big_row_int_ID,
										'db_id'             => $big_row_int_ID,
										'classes'           => array('wpmm-row')
									);

									foreach ($row['row'] as $col_id => $col){
										if ( ! empty($col['col'])){
											$col_class = "wpmm-col-".$col['col'];
										}else{
											$col_class = "wpmm-col-";
										}
										//Todo: need to make a better solution to generat $big_col_int_ID ID
										$big_col_int_ID = rand(999999, PHP_INT_MAX)+$big_row_int_ID+993495349+ ($row_key+1)+($col_id+1)+$item_count;
										//Add dummy li
										$items[] = (object) array(
											'menu_item_parent'      => $big_row_int_ID,
											'type'      => 'wpmm_col',
											'title'     => 'Custom col',
											'ID'        => $big_col_int_ID,
											'db_id'     => $big_col_int_ID,
											'classes' => array('wpmm-col', $col_class)
										);

										if (! empty($col['items'])){
											foreach ($col['items'] as $widget_key => $widget_item){

												$menu_item = array(
													'type'                  => $widget_item['item_type'],
													'item_type'             => 'wpmm_generate',
													'title'                 => $widget_item['item_type'] == 'widget' ?  $widget_item['widget_id'] : $widget_item['title'],
													'description' 			=> $widget_item['item_type'] == 'widget' ?  $widget_item['widget_id'] : $widget_item['description'],
													'output'                => $widget_item['item_type'] == 'widget' ?  $wpmm_widgets_factory->show_widget($widget_item['widget_id']) : '',
													'menu_item_parent'      => $big_col_int_ID,
													//'db_id'                 => 0, //Always have no child menu
													'ID'                    => $widget_item['item_type'] == 'widget' ? $widget_key + $item->ID : $widget_item['ID'],
													'depth'                 => 1,
													'classes'               => array(
														"menu-item",
														"wpmm-type-widget",
														"menu-widget-class",
													),
												);

												if ($widget_item['item_type'] == 'widget'){
													$menu_item['db_id'] 	= 0; //Always have no child menu
													$menu_item['ID'] 		= $widget_item['item_type'] == 'widget' ? $widget_key + $item->ID : $widget_item['ID'];
													$menu_item['depth'] 	= 1;
													$menu_item['classes'][] = "wpmm-type-widget";
													$menu_item['description'] = isset( $widget_item['description'] ) ? $widget_item['description'] : '';
												}else{
													$menu_item['db_id'] = $widget_item['ID'];
													$menu_item['classes'][] = "wpmm-type-item";

													foreach ($reserved_sub_menu_item as $skey => $submenu){
														if ($widget_item['ID'] == $submenu->ID){
															unset($reserved_sub_menu_item[$skey]);
														}
													}
												}

												if ($widget_item['item_type'] == 'menu_item'){
													$menu_item['url'] = $widget_item['url'];
												}
												$items[] = (object) $menu_item;
											}
										}

										$item_count++;
									}
								}

							}
							//print_r($items);
						}
					}
				}
			}

			//print_row($items);die();
			$parent_id = array();
			foreach ($parent_item as $pkey => $pvalue){
				$parent_id[] = $pvalue->ID;
			}

			if (count($reserved_sub_menu_item)){
				foreach ($reserved_sub_menu_item as $slkey => $slvalue){
					if ( ! in_array( $slvalue->menu_item_parent, $parent_id)){
						$items[] = $slvalue;
					}
				}
			}

			// Add Social Links & buttons
			if ( ! empty($args->menu->term_id)) {
				$wp_nav_menu_object = wp_get_nav_menu_object($args->menu->term_id);
				$theme_id = wpmm_theme_by_selected_nav_id($wp_nav_menu_object->term_id);
                $cta_btn_text = get_wpmm_theme_option('cta_btn_text', $theme_id);
                $cta_btn_link = get_wpmm_theme_option('cta_btn_link', $theme_id);

                $login_signup = get_wpmm_theme_option('login_signup', $theme_id);   
                $enable_login_form = get_wpmm_theme_option('enable_login_form', $theme_id);

                $enable_woocart = get_wpmm_theme_option('enable_woocart', $theme_id);   

                if (function_exists('wpmm_pro_init')){
	                # Woocart Button
	                if ( $enable_woocart == true ) {
	                    $items[] = (object)array(
	                        'title'             => 'Woocart',
	                        'type'              => 'wpmm_woo_cart',
	                        'menu_item_parent'  => 0,
	                        'ID'                => 'wpmm-woo-cart',
	                        'db_id'             => '',
	                        'url'               => '#wpmm_woo_cart',
	                        'classes'           => array( 'wpmm-woo-cart', 'menu-item'),
	                    );
	                }

	                # Login/Signup
	                if ( $enable_login_form == true ) {
	                	$login_text = ( !empty( $login_signup ) ) ? $login_signup : 'Login/Signup';
	                	if ( !empty($login_text) ) {
		                    $items[] = (object)array(
		                        'title'             => $login_text,
		                        'type'              => 'wpmm_login_form',
		                        'menu_item_parent'  => 0,
		                        'ID'                => 'wpmm-login-form',
		                        'db_id'             => '',
		                        'url'               => '#wpmm_login_form',
		                        'classes'           => array( 'wpmm-login-form', ),
		                    );
		                }
	                }

	                # CTA Button
	                if (!empty($cta_btn_text) && !empty($cta_btn_link)){
	                    $items[] = (object)array(
	                        'title'             => $cta_btn_text,
	                        'type'              => 'wpmm_social',
	                        'menu_item_parent'  => 0,
	                        'ID'                => 'wpmm-cta-button',
	                        'db_id'             => '',
	                        'url'               => $cta_btn_link,
	                        'classes'           => array('wpmm-social-link', 'wpmm-cta-button')
	                    );
	                }
	            }

                # Search Icon.
                $enable_search_bar = get_wpmm_theme_option('enable_search_bar', $theme_id);
                if ($enable_search_bar == 'true' ) {
                    $items[] = (object)array(
                        'title'             => '<i class="fa fa-search"></i>',
                        'type'              => 'wpmm_search_form',
                        'menu_item_parent'  => 0,
                        'ID'                => 'wpmm-search-icon',
                        'db_id'             => '',
                        'url'               => '#',
                        'classes'           => array('wpmm-social-link', 'wpmm-social-link-search')
                    );
                }

                $enable_social_links = get_wpmm_theme_option('enable_social_links', $theme_id);

				if ($enable_social_links == 'true'){
					$wpmm_db_version = get_option('WPMM_VER');
					if (version_compare($wpmm_db_version, '1.2.0', '>')) {
						$wpmm_social_icon = get_wpmm_theme_option('social_icon', $theme_id);
						$icon_index = 0;
						if ($wpmm_social_icon && array_key_exists('icon', $wpmm_social_icon)) {
							foreach ($wpmm_social_icon['icon'] as $icon) {
								$icon_name = str_replace("fa-", "", $icon);
								$icon_name = str_replace("fa ", "", $icon_name);
								$items[] = (object)array(
									'title' 	=> '<i class="' . $icon . '"></i>',
									'type' 		=> 'wpmm_social',
									'menu_item_parent' => 0,
									'ID' 		=> $icon_name,
									'db_id' 	=> '',
									'url' 		=> $wpmm_social_icon['url'][$icon_index],
									'classes' 	=> array('wpmm-social-link', 'wpmm-social-link-' . $icon_name, 'wpmm-social-index-'.$icon_index)
								);
								$icon_index += 1;
							}
						}
					}else{
						$wpmm_nav_social_links_item = wpmm_nav_social_links_item();
						foreach ($wpmm_nav_social_links_item as $social_links) {
							$social_item_link = get_wpmm_theme_option('social_links_' . $social_links, $theme_id);
							if (!empty($social_item_link)) {
								$icon = $social_links;
								if ($icon === 'gplus') {
									$icon = 'google-plus';
								}
								$items[] = (object)array(
									'title' 		=> '<i class="fa fa-' . $icon . '"></i>',
									'type' 			=> 'wpmm_social',
									'menu_item_parent' => 0,
									'ID' 			=> $social_links,
									'db_id' 		=> '',
									'url' 			=> $social_item_link,
									'classes' 		=> array('wpmm-social-link', 'wpmm-social-link-' . $social_links, 'wpmm-social-index-'.$icon_index)
								);
							}
						}
					}
				}
			}
			return $items;
		}

		public function add_body_classes($classes){
			$classes[] = 'wp-megamenu';
			return $classes;
		}

		/**
		 * Item option save
		 */
		public function wpmm_menu_item_option_save(){
			if(! current_user_can('administrator')) {
                return;
            }

			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$menu_item_id 		= sanitize_text_field($_POST['menu_item_id']);
			$get_menu_settings 	= (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
 
			$get_menu_settings['options']['menu_icon_image']        = wpmm_item_settings_input('menu_icon_image');
			$get_menu_settings['options']['menu_bg_image']          = wpmm_item_settings_input('menu_bg_image');
			$get_menu_settings['options']['disable_link']           = wpmm_item_settings_input('disable_link');
			$get_menu_settings['options']['hide_text']              = wpmm_item_settings_input('hide_text');
			$get_menu_settings['options']['hide_arrow']             = wpmm_item_settings_input('hide_arrow');
			$get_menu_settings['options']['hide_item_on_mobile']    = wpmm_item_settings_input('hide_item_on_mobile');
			$get_menu_settings['options']['hide_item_on_desktop']   = wpmm_item_settings_input('hide_item_on_desktop');
			$get_menu_settings['options']['item_align']             = wpmm_item_settings_input('item_align');
			$get_menu_settings['options']['dropdown_alignment']     = wpmm_item_settings_input('dropdown_alignment');
			$get_menu_settings['options']['icon_position']          = wpmm_item_settings_input('icon_position');
			$get_menu_settings['options']['badge_text']             = wpmm_item_settings_input('badge_text');
			$get_menu_settings['options']['badge_style']            = wpmm_item_settings_input('badge_style');

            $get_menu_settings['options']['mark_as_logo']      		= wpmm_item_settings_input('mark_as_logo');
            $get_menu_settings['options']['logged_in_only']      	= wpmm_item_settings_input('logged_in_only');
            $get_menu_settings['options']['item_logo_type']      	= wpmm_item_settings_input('item_logo_type');
            $get_menu_settings['options']['item_logo_url']      	= wpmm_item_settings_input('item_logo_url');
            $get_menu_settings['options']['item_logo_text']      	= wpmm_item_settings_input('item_logo_text');
            $get_menu_settings['options']['text_logo_google_font'] 	= wpmm_item_settings_input('text_logo_google_font');
            $get_menu_settings['options']['item_logo_position'] 	= wpmm_item_settings_input('item_logo_position');
            $get_menu_settings['options']['item_logo_max_height'] 	= wpmm_item_settings_input('item_logo_max_height');
            $get_menu_settings['options']['item_logo_max_width'] 	= wpmm_item_settings_input('item_logo_max_width');
            $get_menu_settings['options']['item_logo_margin_top'] 	= wpmm_item_settings_input('item_logo_margin_top');
            $get_menu_settings['options']['item_logo_margin_right']      	= wpmm_item_settings_input('item_logo_margin_right');
            $get_menu_settings['options']['item_logo_margin_bottom']      	= wpmm_item_settings_input('item_logo_margin_bottom');
            $get_menu_settings['options']['item_logo_margin_left']      	= wpmm_item_settings_input('item_logo_margin_left');
            $get_menu_settings['options']['item_logo_padding_top']      	= wpmm_item_settings_input('item_logo_padding_top');
            $get_menu_settings['options']['item_logo_padding_right']      	= wpmm_item_settings_input('item_logo_padding_right');
            $get_menu_settings['options']['item_logo_padding_bottom']      	= wpmm_item_settings_input('item_logo_padding_bottom');
            $get_menu_settings['options']['item_logo_padding_left']      	= wpmm_item_settings_input('item_logo_padding_left');
            $get_menu_settings['options']['text_logo_font_weight']      	= wpmm_item_settings_input('text_logo_font_weight');
            $get_menu_settings['options']['text_logo_font_size']      		= wpmm_item_settings_input('text_logo_font_size');
            $get_menu_settings['options']['text_logo_line_height']      	= wpmm_item_settings_input('text_logo_line_height');
            $get_menu_settings['options']['text_logo_case']      			= wpmm_item_settings_input('text_logo_case');
            $get_menu_settings['options']['text_logo_letter_spacing']      	= wpmm_item_settings_input('text_logo_letter_spacing');
            $get_menu_settings['options']['text_logo_color']      			= wpmm_item_settings_input('text_logo_color');


			$get_menu_settings['options']['single_item_text_color'] 	= wpmm_item_settings_input('single_item_text_color');
			$get_menu_settings['options']['single_menu_font_size'] 		= wpmm_item_settings_input('single_menu_font_size');
			$get_menu_settings['options']['single_menu_font_weight'] 	= wpmm_item_settings_input('single_menu_font_weight');
			$get_menu_settings['options']['single_menu_line_height'] 	= wpmm_item_settings_input('single_menu_line_height');

			$get_menu_settings['options']['single_menu_item_border_separator_width'] 	= wpmm_item_settings_input('single_menu_item_border_separator_width');
			$get_menu_settings['options']['single_menu_item_border_separator_type'] 	= wpmm_item_settings_input('single_menu_item_border_separator_type');
			$get_menu_settings['options']['single_menu_item_border_separator_color'] 	= wpmm_item_settings_input('single_menu_item_border_separator_color');


			# Padding
			$get_menu_settings['options']['single_menu_padding_top'] 	= wpmm_item_settings_input('single_menu_padding_top');
			$get_menu_settings['options']['single_menu_padding_right'] 	= wpmm_item_settings_input('single_menu_padding_right');
			$get_menu_settings['options']['single_menu_padding_bottom'] = wpmm_item_settings_input('single_menu_padding_bottom');
			$get_menu_settings['options']['single_menu_padding_left'] 	= wpmm_item_settings_input('single_menu_padding_left');

			# Margin
			$get_menu_settings['options']['single_menu_margin_top'] 	= wpmm_item_settings_input('single_menu_margin_top');
			$get_menu_settings['options']['single_menu_margin_right'] 	= wpmm_item_settings_input('single_menu_margin_right');
			$get_menu_settings['options']['single_menu_margin_bottom'] = wpmm_item_settings_input('single_menu_margin_bottom');
			$get_menu_settings['options']['single_menu_margin_left'] 	= wpmm_item_settings_input('single_menu_margin_left');

			# Submenu Padding
			$get_menu_settings['options']['wp_megamenu_submenu_menu_padding_top'] 		= wpmm_item_settings_input('wp_megamenu_submenu_menu_padding_top');
			$get_menu_settings['options']['wp_megamenu_submenu_menu_padding_right'] 	= wpmm_item_settings_input('wp_megamenu_submenu_menu_padding_right');
			$get_menu_settings['options']['wp_megamenu_submenu_menu_padding_bottom'] 	= wpmm_item_settings_input('wp_megamenu_submenu_menu_padding_bottom');
			$get_menu_settings['options']['wp_megamenu_submenu_menu_padding_left'] 		= wpmm_item_settings_input('wp_megamenu_submenu_menu_padding_left');


			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
            do_action('wpmm_regenerate_css');
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		/**
		 * icon save for the item
		 */
		public function wpmm_icon_update(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$icon = sanitize_text_field($_POST['icon']);
			$get_menu_settings = (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));

			$get_menu_settings['options']['icon']            = sanitize_text_field($icon);

			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		/**
		 * Save column number of panel
		 */
		public function save_item_panel_column(){
			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$wpmm_panel_column = (int) sanitize_text_field($_POST['wpmm_panel_column']);

			$get_menu_settings = (array)maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));

			$get_menu_settings['options']['panel_column']            = $wpmm_panel_column;
			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		/**
		 * Change menu type
		 */
		public function wpmm_change_menu_type(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$menu_type = sanitize_text_field($_POST['menu_type']);

			$get_menu_settings = (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
			$get_menu_settings['menu_type'] = $menu_type;
			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		public function wpmm_change_strees_row(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$wpmm_strees_row = sanitize_text_field($_POST['wpmm_strees_row']);

			$get_menu_settings = (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
			$get_menu_settings['menu_strees_row'] = $wpmm_strees_row;
			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		public function wpmm_set_menu_width(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );
			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$width = sanitize_text_field($_POST['width']);
			$get_menu_settings = (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
			$get_menu_settings['options']['width'] = $width;
			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		public function wpmm_set_strees_row_width(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );
			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$width = sanitize_text_field($_POST['width']);
			$get_menu_settings = (array) maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
			$get_menu_settings['options']['strees_row_width'] = $width;
			update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);
			wp_send_json_success( __('Saved Success', 'wp-megamenu') );
		}

		public function wpmm_save_layout(){
			if(! current_user_can('administrator')) {
                return;
            }
			check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

			$layout_format = sanitize_text_field($_POST['layout_format']);
			$layout_name = sanitize_text_field($_POST['layout_name']);
			$menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
			$current_rows = (int) sanitize_text_field($_POST['current_rows']);

			$get_layout = (array) maybe_unserialize( get_post_meta($menu_item_id, 'wpmm_layout', true));
			$layout_explode = explode(',', $layout_format);

			$col_data = array();
			$new_col_size = 0;
			foreach($layout_explode as $col_size){
				$new_col_size= $new_col_size + $col_size;
				$col_data[] = array('col' => $col_size);

				if ($new_col_size >= 12){
					$new_col_size = 0;
					$get_layout['layout'][]['row'] = $col_data;
					$col_data = array();
				}
			}

			//If this is first row, add top menu item here
			if ($current_rows === 0) {
				$menu_id = (int)sanitize_text_field($_POST['menu_id']);
				$array_menu = wp_get_nav_menu_items($menu_id);

				if (empty($get_menu_settings['menu_type'])) {
					$get_menu_settings['menu_type'] = 'wpmm_dropdown_menu';
				}

				$new_menu_item_id = array();
				$unique_items = array();
				foreach ($array_menu as $m) {
					if ($m->menu_item_parent && ($m->menu_item_parent == $menu_item_id)) {
						$unique_items[$m->ID] = array('item_type' => 'menu_item', 'ID' => $m->ID, 'title' => $m->title, 'url' => $m->url, 'description' => $m->description, 'options' => array());
						$new_menu_item_id[] = $m->ID;
					}
				}
				$get_layout['layout'][0]['row'][0]['items'] = array_values($unique_items);
			}

			//die(print_r($get_layout, true));
			$update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout);
		}

		public function wpmm_generate_css(){
			$wpmm_layouts_option = wpmm_get_post_meta_by_keys('wpmm_layout');
			//wp_die(print_row($wpmm_layouts_option));
			if ( count($wpmm_layouts_option)){
				$style = '<style type="text/css">';
				foreach ($wpmm_layouts_option as $key => $value){
					$options = maybe_unserialize($value->meta_value);
					
					if ( ! empty($options['options']['width'])){
						$style .= ".wp-megamenu-item-{$value->post_id} > ul{ ";
							$width = $options['options']['width'];

							if ( strpos($width, '%')  || strpos($width, 'px') ){

							}else{
								$width = $width.'px';
							}
							$style .= "width:{$width} !important ;";
						$style .= "}";
					}


					if ( ! empty($options['options']['strees_row_width'])) {
						$style .= '.wp-megamenu-wrap > ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-container 
                        > ul.wp-megamenu-sub-menu { ';
						$style .= "width: {$options['options']['strees_row_width']}px !important;";
						$style .= '}';

						$style .= ".wp-megamenu > li.wp-megamenu-item-{$value->post_id}.wpmm-item-fixed-width  > ul.wp-megamenu-sub-menu { ";
						$style .= "width: {$options['options']['strees_row_width']}px !important;";
						// $style .= "margin-left: -".($options['options']['strees_row_width'])."px !important;";
						$style .= "left: calc(100% - ".(intval($options['options']['strees_row_width']) / 2)."px - 20px) !important";
						$style .= '}';
					} else {
						$style .= '.wp-megamenu-wrap > ul.wp-megamenu > li.wpmm_mega_menu > .wpmm-strees-row-container 
                        > ul.wp-megamenu-sub-menu { ';
							$style .= "width: 100% !important;";
						$style .= '}';

						$style .= ".wp-megamenu > li.wp-megamenu-item-{$value->post_id}.wpmm-item-fixed-width  > ul.wp-megamenu-sub-menu { ";
							$style .= "width: 100% !important;";
						$style .= '}';
					}

					// .wp-megamenu > li.wpmm-item-fixed-width
					if ( ! empty($options['options']['dropdown_alignment']) ){
						$position = $options['options']['dropdown_alignment'];

						//.wp-megamenu-wrap .wpmm-nav-wrap > ul.wp-megamenu li.wpmm_dropdown_menu ul.wp-megamenu-sub-menu li.menu-item-has-children > ul.wp-megamenu-sub-menu
						$style .= ".wp-megamenu-wrap .wpmm-nav-wrap > ul.wp-megamenu li.wpmm_dropdown_menu ul.wp-megamenu-sub-menu li.menu-item-has-children.wp-megamenu-item-{$value->post_id}.wpmm-submenu-{$position} > ul.wp-megamenu-sub-menu {";
							$style .= ($position === 'left') ? 'right: 100%;': 'left: 100%;';
						$style .= "}";
					}


					$style .= ".wpmm-nav-wrap ul.wp-megamenu>li ul.wp-megamenu-sub-menu #wp-megamenu-item-{$value->post_id}>a { ";
						# Single menu font size.
						if ( ! empty($options['options']['single_menu_font_size'])){
							$style .= "font-size: {$options['options']['single_menu_font_size']}px !important;";
						}
						# Single menu color.
						if ( ! empty($options['options']['single_item_text_color'])){
							$style .= "color: {$options['options']['single_item_text_color']} !important;";
						}
						# Single menu font color.
						if ( ! empty($options['options']['single_menu_font_weight'])){
							$style .= "font-weight: {$options['options']['single_menu_font_weight']} !important;";
						}

						# Single menu font color.
						if ( ! empty($options['options']['single_menu_line_height'])){
							$style .= "line-height: {$options['options']['single_menu_line_height']}px !important;";
						}

						# Single menu font color.
						if ( ! empty($options['options']['item_align'])){
							$style .= "text-align: {$options['options']['item_align']} !important;";
						}
					$style .= "}";
					

					$style .= ".wpmm-nav-wrap .wp-megamenu>li>ul.wp-megamenu-sub-menu li#wp-megamenu-item-{$value->post_id}>a { ";
						if ( ! empty( $options['options']['single_menu_item_border_separator_width'] ) && ! empty( $options['options']['single_menu_item_border_separator_type'] ) && ! empty( $options['options']['single_menu_item_border_separator_color'] )){
							$style .= "border-bottom: {$options['options']['single_menu_item_border_separator_width']}px {$options['options']['single_menu_item_border_separator_type']} 
							{$options['options']['single_menu_item_border_separator_color']} !important;";
						}
					$style .= "}";


					# Margin  - Submenu item menu margin.
					$style .= "li#wp-megamenu-item-{$value->post_id}> ul ul ul> li { ";
						if ( !empty($options['options']['single_menu_margin_top']) ) {
							$style .= "margin-top: {$options['options']['single_menu_margin_top']}px !important;";
						}
						if ( !empty($options['options']['single_menu_margin_right']) ) {
							$style .= "margin-right: {$options['options']['single_menu_margin_right']}px !important;";
						}
						if ( !empty($options['options']['single_menu_margin_bottom']) ) {
							$style .= "margin-bottom: {$options['options']['single_menu_margin_bottom']}px !important;";
						}
						if ( isset( $options['options']['single_menu_margin_left'] ) ) {
							$style .= "margin-left: {$options['options']['single_menu_margin_left']}px !important;";
						}

						$style .= "width: 100%; display: inline-block;";
					$style .= "}";


					$style .= " li#wp-megamenu-item-{$value->post_id} a { ";
						if ( !empty($options['options']['single_menu_padding_top']) ) {
							$style .= "padding-top: {$options['options']['single_menu_padding_top']}px !important;";
						}
						if ( !empty($options['options']['single_menu_padding_right']) ) {
							$style .= "padding-right: {$options['options']['single_menu_padding_right']}px !important;";
						}
						if ( !empty($options['options']['single_menu_padding_bottom']) ) {
							$style .= "padding-bottom: {$options['options']['single_menu_padding_bottom']}px !important;";
						}
						if ( isset( $options['options']['single_menu_padding_left'] ) ) {
							$style .= "padding-left: {$options['options']['single_menu_padding_left']}px !important;";
						}
					$style .= "}";


					$style .= "#wp-megamenu-item-{$value->post_id}> .wp-megamenu-sub-menu { "; 

						if ( !empty($options['options']['wp_megamenu_submenu_menu_padding_top']) ) {
							$style .= "padding-top: {$options['options']['wp_megamenu_submenu_menu_padding_top']}px !important;";
						}
						if ( !empty($options['options']['wp_megamenu_submenu_menu_padding_right']) ) {
							$style .= "padding-right: {$options['options']['wp_megamenu_submenu_menu_padding_right']}px !important;";
						}
						if ( !empty($options['options']['wp_megamenu_submenu_menu_padding_bottom']) ) {
							$style .= "padding-bottom: {$options['options']['wp_megamenu_submenu_menu_padding_bottom']}px !important;";
						}
						if ( isset( $options['options']['wp_megamenu_submenu_menu_padding_left'] ) ) {
							$style .= "padding-left: {$options['options']['wp_megamenu_submenu_menu_padding_left']}px !important;";
						}

					$style .= "}";



					//Setting background image if any
					if ( ! empty($options['options']['menu_bg_image'])){
						$style .= ".wp-megamenu-item-{$value->post_id} > ul, .wp-megamenu-item-{$value->post_id} > div > ul.wp-megamenu-sub-menu{ ";
						$style .= "background-image: url('{$options['options']['menu_bg_image']}') !important;";
						$style .= "background-size: cover !important;";
						$style .= "background-repeat: no-repeat !important;";
						$style .= "background-position: center !important;";
						$style .= "}";
					} 
				}
				$style .= '</style>';
				echo wpmm_sanitize_inline_css_output( $style );
			}
		}
	}

	wp_megamenu_base::init();
}

# Include includes directories files.
$includes_php_files = glob(WPMM_DIR.'includes/*.php');
if ( ! empty($includes_php_files) && count($includes_php_files)){
	foreach ( $includes_php_files as $file){
		include $file;
	}
}

# Include Addons directory and there main file.
$addons_dir = array_filter(glob(WPMM_DIR.'addons/*'), 'is_dir');
if (count($addons_dir) > 0) {
	foreach ($addons_dir as $key => $value) {
		$addon_dir_name = str_replace(dirname($value).'/', '', $value);
		$file_name = WPMM_DIR . 'addons/'.$addon_dir_name.'/'.$addon_dir_name.'.php';
		if ( file_exists($file_name) ){
			include_once $file_name;
		}
	}
}


