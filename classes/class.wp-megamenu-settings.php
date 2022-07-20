<?php

/**
 * Class WP_MegaMenu_Settings
 */
if ( ! class_exists( 'WP_MegaMenu_Settings' ) ) {

	class WP_MegaMenu_Settings {

		public static function init() {
			$return = new self();
			return $return;
		}

		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_wpmm_settings' ) );
			add_filter( 'wpmm/pro_fields', array( $this, 'pro_fields' ), 10, 1 );
		}

		public function pro_fields() {

			return array(
				array(
					'label'      => 'Menu Width',
					'child_type' => 'input_group_inline',
					'has_child'  => true,
					'fields'     => array(
						array(
							'key'         => 'menu_width',
							'label'       => false,
							'type'        => 'number',
							'default'     => '100',
							'min'         => '0',
							'max'         => '100',
							'placeholder' => '',
						),
						array(
							'key'     => 'menu_width_type',
							'label'   => false,
							'type'    => 'select',
							'width'   => '70px',
							'default' => '%',
							'options' => array(
								'px'  => 'PX',
								'em'  => 'EM',
								'rem' => 'REM',
								'%'   => '%',
							),
						),
					),
				),
				array(
					'key'         => 'custom_class',
					'label'       => 'Custom Class',
					'type'        => 'text',
					'default'     => '',
					'placeholder' => 'Type Custom Class',
				),
				array(
					'key'     => 'badge_background',
					'label'   => 'Badge Background',
					'type'    => 'color',
					'default' => '',
				),
				array(
					'child_type' => 'multi_column',
					'has_child'  => true,
					'fields'     => array(
						array(
							'key'     => 'item_align_x',
							'label'   => 'Menu Item Alignment',
							'type'    => 'select',
							'default' => 'center',
							'layout'  => 'full',
							'options' => array(
								'left'   => 'Left',
								'center' => 'Center',
								'right'  => 'Right',
							),
						),
						array(
							'key'     => 'item_align_y',
							'label'   => 'Menu Item Alignment',
							'type'    => 'select',
							'default' => 'right',
							'layout'  => 'full',
							'options' => array(
								'left'   => 'Left',
								'center' => 'Center',
								'right'  => 'Right',
							),
						),
					),
				),
			);

		}
		/**
		 * Save the settings
		 */
		public function wpmm_item_setting_fields() {
			// Existing navigation item setting fields
			$listed_fields = array(
				array(
					'key'     => 'menu_bg_image',
					'label'   => 'Upload Background Image',
					'type'    => 'image',
					'default' => 2,
				),
				array(
					'key'     => 'menu_type',
					'label'   => 'Enable MegaMenu',
					'type'    => 'checkbox',
					'default' => 'false',
				),
				array(
					'key'     => 'menu_strees_row',
					'label'   => 'Select Stretch',
					'type'    => 'select',
					'area'    => 'location',
					'default' => 'primary',
					'width'   => '130px',
					'options' => array(
						'wpmm-strees-default'         => 'Default',
						'wpmm-strees-row'             => 'Row',
						'wpmm-strees-row-and-content' => 'Row and Content',
					),
				),
				array(
					'key'     => 'logged_in_only',
					'label'   => 'Logged in only',
					'type'    => 'checkbox',
					'default' => 'false',
				),
				array(
					'key'     => 'hide_text',
					'label'   => 'Hide Text',
					'type'    => 'checkbox',
					'default' => 'true',
				),
				array(
					'key'     => 'hide_arrow',
					'label'   => 'Hide Arrow',
					'type'    => 'checkbox',
					'default' => 'true',
				),
				array(
					'key'     => 'disable_link',
					'label'   => 'Disable Link',
					'type'    => 'checkbox',
					'default' => 'false',
				),
				array(
					'key'     => 'hide_item_on_mobile',
					'label'   => 'Hide Item on Mobile',
					'type'    => 'checkbox',
					'default' => 'false',
				),
				array(
					'key'     => 'hide_item_on_desktop',
					'label'   => 'Hide Item on Desktop',
					'type'    => 'checkbox',
					'default' => 'false',
				),
				array(
					'key'     => 'item_align',
					'label'   => 'Menu Item Alignment',
					'type'    => 'select',
					'default' => 'left',
					'options' => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
				),
				array(
					'key'     => 'dropdown_alignment',
					'label'   => 'Dropdown alignment',
					'type'    => 'select',
					'default' => 'left',
					'options' => array(
						'left'  => 'Left',
						'right' => 'Right',
					),
				),
				array(
					'key'     => 'icon_position',
					'label'   => 'Icon Position',
					'type'    => 'select',
					'default' => 'left',
					'options' => array(
						'left'  => 'Left',
						'top'   => 'Top',
						'right' => 'Right',
					),
				),
				array(
					'label'      => 'Badge Text',
					'child_type' => 'input_group',
					'has_child'  => true,
					'fields'     => array(
						array(
							'key'         => 'badge_text',
							'label'       => false,
							'type'        => 'text',
							'default'     => '',
							'placeholder' => 'Badge Text',
						),
						array(
							'key'     => 'badge_style',
							'label'   => false,
							'type'    => 'select',
							'default' => 'primary',
							'width'   => '130px',
							'options' => array(
								'default' => 'Default',
								'primary' => 'Primary',
								'success' => 'Success',
								'info'    => 'Info',
								'warning' => 'Warning',
								'danger'  => 'Danger',
							),
						),
					),
				),
				array(
					'label'      => 'Padding',
					'child_type' => 'multi_column',
					'has_child'  => true,
					'fields'     => array(
						array(
							'key'         => 'wp_megamenu_submenu_menu_padding_top',
							'label'       => 'Top',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'wp_megamenu_submenu_menu_padding_right',
							'label'       => 'Right',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'wp_megamenu_submenu_menu_padding_bottom',
							'label'       => 'Bottom',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'wp_megamenu_submenu_menu_padding_left',
							'label'       => 'Left',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
					),
				),
				array(
					'label'      => 'Margin',
					'child_type' => 'multi_column',
					'has_child'  => true,
					'fields'     => array(
						array(
							'key'         => 'single_menu_margin_top',
							'label'       => 'Top',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'single_menu_margin_right',
							'label'       => 'Right',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'single_menu_margin_bottom',
							'label'       => 'Bottom',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
						array(
							'key'         => 'single_menu_margin_left',
							'label'       => 'Left',
							'placeholder' => '0px',
							'type'        => 'text',
							'default'     => '',
						),
					),
				),
			);

			// $setting_fields = array_merge( $listed_fields, apply_filters( 'wpmm/pro_fields', array() ) );
			// $setting_fields = apply_filters( 'wpmm/pro_fields', $listed_fields );

			return array_merge( $listed_fields, apply_filters( 'wpmm/pro_fields', array() ) );
		}

		public function wpmm_field_type( $field, $saved_options = null ) {
			ob_start();
			if ( isset( $field['has_child'] ) && isset( $field['child_type'] ) ) {
				include wpmm()->path . "views/admin/fields/{$field['child_type']}.php";
			} elseif ( isset( $field['type'] ) ) {
				include wpmm()->path . "views/admin/fields/{$field['type']}.php";
			}
			echo ob_get_clean();
		}

		public function save_wpmm_settings() {
			if ( ! empty( $_POST['wpbb_settings_panel'] ) && ! $_POST['wpbb_settings_panel'] === 'true' ) {
				return;
			}

			// Checking the verified Data
			if ( isset( $_POST['wpmm_settings_nonce_field'] )
			&& wp_verify_nonce( $_POST['wpmm_settings_nonce_field'], 'wpmm_settings_nonce_action' ) ) {
				// processing data.
				$get_wpmm_option = (array) maybe_unserialize( get_option( 'wpmm_options' ) );

				if ( ! empty( $_POST['wpmm_options'] ) ) {
					$options = $_POST['wpmm_options'];

					foreach ( $options as $key => $value ) {
						if ( 'disable_wpmm_on_mobile' !== $key ) {
							$options[ $key ] = wpmm_sanitize_settings_options( $key );
						}
					}

					// Getting checkmark value
					$options['disable_wpmm_on_mobile'] = get_wpmm_option_input_checkmark( 'disable_wpmm_on_mobile' );

					$wpmm_option = array_merge( $get_wpmm_option, $options );
					$wpmm_option = apply_filters( 'update_wpmm_settings', $wpmm_option );
					update_option( 'wpmm_options', $wpmm_option );
					do_action( 'wpmm_regenerate_css' );
				}
			}
		}
	}

	WP_MegaMenu_Settings::init();


	if ( ! function_exists( 'wpmm_settings' ) ) {
		function wpmm_settings() {
			return new WP_MegaMenu_Settings();
		}
	}
}
