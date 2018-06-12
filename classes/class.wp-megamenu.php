<?php

/**
 * Class wp_megamenu
 */
if ( ! class_exists('wp_megamenu')) {

	class wp_megamenu extends Walker_Nav_Menu{

		private $container_type = null;
		private $wpmm_item_id = null;

		function start_lvl(&$output, $depth = 0, $args = array()) {
			//In a child UL, add the 'wp-megamenu-submenu' class
			$indent = str_repeat("\t", $depth);

			$is_row_content_strees_extra_div = '';
			if ($this->container_type === 'wpmm-strees-row-and-content'){
				$is_row_content_strees_extra_div = "<div class='wpmm-row-content-strees-extra'></div> ";
			}

			if ($this->container_type === 'wpmm-strees-row' || $this->container_type === 'wpmm-strees-row-and-content' ){
				$output .= "\n{$indent}<div id='{$this->wpmm_item_id}' class='{$this->container_type}-container'> {$is_row_content_strees_extra_div} <ul class=\"wp-megamenu-sub-menu\">\n";
			}else{
				$output .= "\n{$indent}<ul class=\"wp-megamenu-sub-menu\">\n";
			}
		}

		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
			$nav_term_id = $args->menu->term_id;
			$theme_id = wpmm_theme_by_selected_nav_id($nav_term_id);

			$theme_options_array = get_wpmm_theme_full_options_as_array($theme_id);
			$display_mode = get_wpmm_theme_option_from_array('display_mode', $theme_options_array);

			$description_header_item = get_wpmm_option('description_header_item');
			$description_top_level = get_wpmm_option('description_top_level');


			$wpmm_item_settings = maybe_unserialize(get_post_meta($item->ID, 'wpmm_layout', true));

			$indent = ($depth) ? str_repeat("\t", $depth) : '';

			$li_attributes = '';
			$class_names = $value = '';

			$classes = empty($item->classes) ? array() : (array)$item->classes;

			$classes[] = 'wp-megamenu-item-' . $item->ID;

			$item_a_class = array();
			if ($depth ==0){
				$wpmm_menu_type = empty($wpmm_item_settings['menu_type']) ? ' wpmm_dropdown_menu ': " {$wpmm_item_settings['menu_type']} ";
				$classes[] = $wpmm_menu_type;

				if ( ! empty($theme_options_array['animation_type']) ){
					$classes[] = 'wpmm-'.$theme_options_array['animation_type'];
				}


				$wpmm_ite_fixed_width = '';
				if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['strees_row_width']) ){
					$wpmm_ite_fixed_width = 'wpmm-item-fixed-width';
				}
				if (! empty($wpmm_item_settings['menu_strees_row']) ){
					if ($wpmm_item_settings['menu_strees_row'] == 'wpmm-strees-default'){
						$classes[] = $wpmm_ite_fixed_width;
					}
				}else{
					$classes[] = $wpmm_ite_fixed_width;
				}

			}
			if ($depth ==0 && ! empty($wpmm_item_settings['menu_strees_row'])){
				$this->container_type = $wpmm_item_settings['menu_strees_row'];
				$this->wpmm_item_id = 'wpmm-strees-row-'.$item->ID;
				$classes[] = $wpmm_item_settings['menu_strees_row'];
			}else{
				$this->container_type = null;
			}
			if( $depth == 1 ){
				if ( ! empty($item->wpmm_column_class)){
					$classes[] = " {$item->wpmm_column_class} " ;
				}
			}

			if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['hide_item_on_mobile']) && $wpmm_item_settings['options']['hide_item_on_mobile'] === 'true' ){
				$classes[] = 'wpmm-hide-mobile';
			}
			if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['hide_item_on_desktop']) && $wpmm_item_settings['options']['hide_item_on_desktop'] === 'true' ){
				$classes[] = 'wpmm-hide-desktop';
			}

			$item_id = 'wp-megamenu-item-' . $item->ID;

			if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['dropdown_alignment']) && $wpmm_item_settings['options']['dropdown_alignment'] === 'left' ){
				$classes[] = 'wpmm-submenu-left';
			}else{
				$classes[] = 'wpmm-submenu-right';
			}

			//Make sure you still add all of the WordPress classes.
			$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
			$class_names = ' class="' . esc_attr($class_names) . '"';

			$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
			$id = strlen($id) ? ' id="' . esc_attr($item_id) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

			//Add attributes to link element.
			$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
			//$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
			$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
			//$attributes = !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
			//$attributes .= ($args->has_children) ? ' class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"' : '';

			/**
			 * Anchor class
			 */
			$item_a_class = apply_filters('wpmm_item_anchor_classes', $item_a_class );
			$menu_item_a_attributes = '';
			if (count($item_a_class)){
				$menu_item_a_attributes .= ' class="'.join(' ',$item_a_class).'" ';
			}

			//Social Link Target
			if ( ! empty($item->type) && $item->type === 'wpmm_social'){
				if ( empty($theme_options_array['social_links_target']) ||
				     $theme_options_array['social_links_target'] !== '_self'){
					$attributes .= ' target="_blank" ';
				}else{
					$attributes .= ' target="_self" ';
				}
			}else{
				$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
			}
			/*echo '<pre>';
			print_r($item);*/

			if ($item->type === 'wpmm_row' || $item->type === 'wpmm_col'){
				$item_output = '';
			}
			elseif ( $item->type === 'widget'){
				$item_output = $item->output;
			}else{
				$wpmm_icon = '';
				if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['icon'])){
					$icon_position = '';
					if ( ! empty($wpmm_item_settings['options']['icon_position'])){
						$icon_position = 'wpmm-selected-icon-'.$wpmm_item_settings['options']['icon_position'];
					}
					$wpmm_icon .= "<span class='wpmm-selected-icon {$icon_position}'><i class='{$wpmm_item_settings['options']['icon']}'></i></span>";
				}

				$item_title = $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;

				if ( is_array($wpmm_item_settings) && ! empty($wpmm_item_settings['options']['hide_text'])){
					if ($wpmm_item_settings['options']['hide_text'] == 'true'){
						$item_title = '';
					}
				}

				$menu_href = !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
				if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['disable_link'])){
					if ($wpmm_item_settings['options']['disable_link'] == 'true'){
						$menu_href = ' href="javascript:;" ';
					}
				}

				//Add attributes
				$attributes .= $menu_item_a_attributes;
				$attributes .= $menu_href;

				$item_output = $args->before;
				$item_output .= '<a' . $attributes . '>';

				if ( is_array($wpmm_item_settings) && ! empty($wpmm_item_settings['options']['icon_position'])){
					if ($wpmm_item_settings['options']['icon_position'] == 'left'){
						$item_output .= $wpmm_icon;
					}elseif ($wpmm_item_settings['options']['icon_position'] == 'top'){
						$item_output .= $wpmm_icon;
					}
				}else{
					$item_output .= $wpmm_icon;
				}

				$item_output .= $item_title;
				if (is_array($wpmm_item_settings) &&  ! empty($wpmm_item_settings['options']['icon_position'])){
					if ($wpmm_item_settings['options']['icon_position'] == 'right'){
						$item_output .= ' '.$wpmm_icon;
					}
				}

				//Show dropdown menu indicator
				if (is_array($wpmm_item_settings)) {
					if ( ! empty( $wpmm_item_settings['options']['hide_arrow'] ) && $wpmm_item_settings['options']['hide_arrow'] != 'true' ) {

						if ( $depth == 0 && $args->has_children && ! empty( $theme_options_array['dropdown_arrow_down'] ) ) {
							$item_output .= ' <b class="fa ' . $theme_options_array['dropdown_arrow_down'] . '"></b> ';
						} elseif ( $depth > 0 && $args->has_children && ! empty( $theme_options_array['dropdown_arrow_right'] ) ) {

							$right_arrow_caret = '<b class="fa fa-angle-right"></b>';
							if ( ! empty( $theme_options_array['dropdown_arrow_right'] ) ) {
								$right_arrow_caret = '<b class="fa ' . $theme_options_array['dropdown_arrow_right'] . '"></b>';
							}
							$left_arrow_caret = '<b class="fa fa-angle-left"></b>';
							if ( ! empty( $theme_options_array['dropdown_arrow_left'] ) ) {
								$left_arrow_caret = '<b class="fa ' . $theme_options_array['dropdown_arrow_left'] . '"></b>';
							}

							//dropdown_alignment
							if ( ! empty( $wpmm_item_settings['options']['dropdown_alignment'] ) ) {
								$dropdown_alignment = $wpmm_item_settings['options']['dropdown_alignment'];

								if ( $dropdown_alignment === 'left' ) {
									$item_output .= $left_arrow_caret;
								} else {
									$item_output .= $right_arrow_caret;
								}
							} else {
								$item_output .= $right_arrow_caret;
							}
							//$item_output .= ' <b class="fa '.$theme_options_array['dropdown_arrow_right'].'"></b> ';
						}
					}


					if ( $depth == 0 && $args->has_children && empty( $wpmm_item_settings['options']['hide_arrow'] ) && ! empty( $theme_options_array['dropdown_arrow_down']
						) ) {
						$item_output .= ' <b class="fa ' . $theme_options_array['dropdown_arrow_down'] . '"></b> ';
					}

				}else{
					//Sett a
					if ( $depth == 0 && $args->has_children && empty( $wpmm_item_settings['options']['hide_arrow'] ) && ! empty( $theme_options_array['dropdown_arrow_down']
						) ) {
						$item_output .= ' <b class="fa ' . $theme_options_array['dropdown_arrow_down'] . '"></b> ';
					}
				}
				//Print badge
				if ( is_array($wpmm_item_settings) && ! empty($wpmm_item_settings['options']['badge_text'])
				) {
					$item_output .= "<span class='wpmm-badge wpmm-badge-{$wpmm_item_settings['options']['badge_style']}'>{$wpmm_item_settings['options']['badge_text']}</span>";
				}
				$item_output .= '</a>';

				//Append description
				if ( $depth == 0 && $description_top_level == 'true' && ! empty($item->description)){
					$item_output .= '<br /><span class="wpmm_item_description">' . $item->description . '</span>';
				}
				if ( $depth == 1 && $description_header_item == 'true' && ! empty($item->description)){
					$item_output .= '<br /><span class="wpmm_item_description">' . $item->description . '</span>';
				}

				$item_output .= $args->after;
			}

			$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		}

		//Overwrite display_element function to add has_children attribute. Not needed in >= Wordpress 3.4
		function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output){
			if (!$element)
				return;

			//Add Twenty Fifteen Themes Support
			if (!isset($element->description)){
				$element->description = '';
			}
			//End Twenty Fifteen Support

			$id_field = $this->db_fields['id'];

			//display this element
			if (is_array($args[0]))
				$args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
			else if (is_object($args[0]))
				$args[0]->has_children = !empty($children_elements[$element->$id_field]);
			$cb_args = array_merge(array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);

			$id = $element->$id_field;

			// descend only when the depth is right and there are childrens for this element
			if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {
				foreach ($children_elements[$id] as $child) {
					if (!isset($newlevel)) {
						$newlevel = true;
						//start the child delimiter
						$cb_args = array_merge(array(&$output, $depth), $args);
						call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
					}
					$this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
				}
				unset($children_elements[$id]);
			}

			if (isset($newlevel) && $newlevel) {
				//end the child delimiter
				$cb_args = array_merge(array(&$output, $depth), $args);
				call_user_func_array(array(&$this, 'end_lvl'), $cb_args);

				//$wpmm_item_settings = get_post_meta($element->ID, 'wpmm_layout', true);
				if ($depth ==0 && ($this->container_type === 'wpmm-strees-row' || $this->container_type === 'wpmm-strees-row-and-content' )  ) {
					$output .= '</div>';
				}
			}
			//end this element
			$cb_args = array_merge(array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'end_el'), $cb_args);
		}
	}

}



add_filter('wp_nav_menu_args', 'overrite_functions_wp_megamenu', 9999);

function overrite_functions_wp_megamenu($args){
	//die(print_r($args['menu_id']));
	if (empty($args['theme_location'])) {
		return $args;
	}

	$wpmm_db_version = get_option('WPMM_VER');
	if (version_compare($wpmm_db_version, '1.1.1', '>')) {
		$wpmm_options = get_option( 'wpmm_options' );
		if ( empty( $wpmm_options[ $args['theme_location'] ]['is_enabled'] ) || $wpmm_options[ $args['theme_location'] ]['is_enabled'] != '1' ) {
			return $args;
		}
	}
	//Get Nav term id || Nav theme id by Location
	$locations = get_nav_menu_locations();
	$theme_id = null;
	if ( ! empty($locations[ $args['theme_location'] ])){
		$menu_id = $locations[ $args['theme_location'] ] ;
		$wp_nav_menu_object = wp_get_nav_menu_object($menu_id);
		$theme_id = wpmm_theme_by_selected_nav_id($wp_nav_menu_object->term_id);
	}

	$sticky_class = '';
	$menu_layout_class = '';
	$menu_custom_class = '';
	$brand_logo = '';
	$logo_width = '';
	$logo_height = '';
	$search_form = '';
	$toggle_btn_open_text = __('MENU', 'wp-megamenu');

	if ($theme_id){
		if (get_wpmm_theme_option('enable_sticky_menu', $theme_id) == 'true'){
			$sticky_class = 'wpmm-sticky';
		}
		$menu_layout = get_wpmm_theme_option('menu_layout', $theme_id);
		if ($menu_layout){
			$menu_layout_class = " wpmm-{$menu_layout} ";
		}

		$menu_custom_class = get_wpmm_theme_option('wpmm_class', $theme_id);

		$brand_logo = get_wpmm_theme_option('brand_logo', $theme_id);

		$brand_logo_width = get_wpmm_theme_option('brand_logo_width', $theme_id);
		$brand_logo_height = get_wpmm_theme_option('brand_logo_height', $theme_id);
		if ( ! empty($brand_logo_width)){
			$logo_width = wpmm_unit_to_int($brand_logo_width);
			$logo_width = " width='{$logo_width}px' ";
		}
		if ( ! empty($brand_logo_height)){
			$logo_height = wpmm_unit_to_int($brand_logo_height);
			$logo_height = " height='{$logo_height}px' ";
		}

		$enable_search_bar = get_wpmm_theme_option('enable_search_bar', $theme_id);
		if( $enable_search_bar == 'true' ){
			$search_form .= '<form class="wpmm-search-form" action="'.esc_url( home_url( "/" ) ).'">';
			$search_form .= '<input type="text" placeholder="'.__("Search","wp-megamenu").'..." name="s">';
			$search_form .= '</form>';
		}

		$toggle_btn_open_text = get_wpmm_theme_option('toggle_btn_open_text', $theme_id);

		do_action('wpmm_before_nav_theme_activate', $theme_id);
	}


	/**
	 * Backword Compatibility
	 *
	 * is this newer version
	 */
	if (version_compare($wpmm_db_version, '1.1.1', '>')) {
		$container = 'nav';
		$config_container = get_wpmm_option('container_tag');
		if ($config_container == 'div'){
			$container = $config_container;
		}

		$home_url = '';
		$brand_logo_wrap = '';
		if (! empty($brand_logo)){
			$home_url = esc_url( home_url( '/' ) );
			$brand_logo_wrap = "<div class='wpmm_brand_logo_wrap'><a href='{$home_url}'> <img src='{$brand_logo}' {$logo_width} {$logo_height}/> </a> </div>";
		}

		$wpmm_on_mobile = '';
		$disable_wpmm_on_mobile = get_wpmm_option('disable_wpmm_on_mobile');

		if ($disable_wpmm_on_mobile != true) {
			$wpmm_on_mobile = '<a href="javascript:;" class="wpmm_mobile_menu_btn"><i class="fa fa-bars"></i> '.$toggle_btn_open_text.'</a>';
		}

		$item_wrap = '<div class="wpmm-fullwidth-wrap"></div><div class="wpmm-nav-wrap wpmm-main-wrap-'
		             .$args['theme_location'].'">' .$wpmm_on_mobile.' '.$brand_logo_wrap.'<ul id="%1$s" class="%2$s">%3$s</ul>'.$search_form.'</div>';


		$wpmm_wrap_class = '';
		if ($theme_id){
			$wpmm_wrap_class = "wp-megamenu-wrap {$sticky_class} {$menu_layout_class} {$menu_custom_class} ";
		}
//die(print_row($args));
		return $argunets = array(
			'menu'              => $args['menu'],
			'theme_location'    => $args['theme_location'],
			'container'         => $container,
			'container_id'      => 'wp-megamenu-' . $args['theme_location'],
			'container_class'   => $wpmm_wrap_class,
			'menu_class'        => 'wp-megamenu',
			'echo'              => true,
			'fallback_cb'       => 'wp_page_menu',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'items_wrap'        => $item_wrap,
			'depth'             => 0,
			'walker'            => new wp_megamenu()
		);
	}else{

		$auto_intergration_menu = (array) get_wpmm_option('auto_intergration_menu');
		if (count($auto_intergration_menu)){
			if ( ! in_array($args['theme_location'], $auto_intergration_menu )) {
				return $args;
			}

			$container = 'nav';
			$config_container = get_wpmm_option('container_tag');
			if ($config_container == 'div'){
				$container = $config_container;
			}

			$home_url = '';
			$brand_logo_wrap = '';
			if (! empty($brand_logo)){
				$home_url = esc_url( home_url( '/' ) );
				$brand_logo_wrap = "<div class='wpmm_brand_logo_wrap'><a href='{$home_url}'> <img src='{$brand_logo}' {$logo_width} {$logo_height}/> </a> </div>";
			}

			$wpmm_on_mobile = '';
			$disable_wpmm_on_mobile = get_wpmm_option('disable_wpmm_on_mobile');
			if ($disable_wpmm_on_mobile != 'true') {
				$wpmm_on_mobile = '<a href="javascript:;" class="wpmm_mobile_menu_btn"><i class="fa fa-bars"></i> '.$toggle_btn_open_text.'</a>';
			}

			$item_wrap = '<div class="wpmm-fullwidth-wrap"></div><div class="wpmm-nav-wrap wpmm-main-wrap-'
			             .$args['theme_location'].'">' .$wpmm_on_mobile.' '.$brand_logo_wrap.'<ul id="%1$s" class="%2$s">%3$s</ul>'.$search_form.'</div>';


			$wpmm_wrap_class = '';
			if ($theme_id){
				$wpmm_wrap_class = "wp-megamenu-wrap {$sticky_class} {$menu_layout_class} {$menu_custom_class} ";
			}

			return $argunets = array(
				//'menu'              => wp_get_nav_menu_object( $args['menu_id'] ),
				'menu'              => $args['menu'],
				'theme_location'    => $args['theme_location'],
				'container'         => $container,
				'container_id'      => 'wp-megamenu-' . $args['theme_location'],
				'container_class'   => $wpmm_wrap_class,
				'menu_class'        => 'wp-megamenu',
				'echo'              => true,
				'fallback_cb'       => 'wp_page_menu',
				'before'            => '',
				'after'             => '',
				'link_before'       => '',
				'link_after'        => '',
				'items_wrap'        => $item_wrap,
				'depth'             => 0,
				'walker'            => new wp_megamenu()
			);

		}


	}
	return $args;
}

add_action('admin_footer', 'wp_megamenu_add_menu_settings_wrap_admin_footer');
function wp_megamenu_add_menu_settings_wrap_admin_footer() {
	$current_screen = get_current_screen();
	if (property_exists($current_screen,'base')){
		if ($current_screen->base === 'nav-menus'){
			$html = '<div id="wpmmSettingOverlay" style="display: none;"></div><div class="wp-megamenu-item-settins-wrap" style="display: none;">';
			$html .= '<div class="wpmm-item-settings-content">';
			$html .= '</div>';
			$html .= '</div>';
			echo $html;
		}
	}
}