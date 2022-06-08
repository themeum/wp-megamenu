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
					'key'     => 'logged_in_only',
					'label'   => 'Logged in only',
					'type'    => 'checkbox',
					'default' => 'on',
				),
				array(
					'key'     => 'hide_text',
					'label'   => 'Hide Text',
					'type'    => 'checkbox',
					'default' => 'on',
				),
				array(
					'key'     => 'hide_arrow',
					'label'   => 'Hide Arrow',
					'type'    => 'checkbox',
					'default' => 'on',
				),
				array(
					'key'     => 'disable_link',
					'label'   => 'Disable Link',
					'type'    => 'checkbox',
					'default' => 'on',
				),
				array(
					'key'     => 'hide_item_on_mobile',
					'label'   => 'Hide Item on Mobile',
					'type'    => 'checkbox',
					'default' => 'on',
				),
				array(
					'key'     => 'hide_item_on_desktop',
					'label'   => 'Hide Item on Desktop',
					'type'    => 'checkbox',
					'default' => 'on',
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
					'label'     => 'Badge Text',
					'has_child' => true,
					'fields'    => array(
						array(
							'key'     => 'badge_text',
							'type'    => 'text',
							'default' => 'Badge Text',
						),
						array(
							'key'     => 'icon_position',
							'type'    => 'select',
							'value'   => 'left',
							'options' => array(
								'default' => 'default',
								'primary' => 'primary',
								'success' => 'success',
								'info'    => 'info',
								'warning' => 'warning',
								'danger'  => 'danger',
							),
						),
					),
				),
				array(
					'label'     => 'Padding',
					'has_child' => true,
					'fields'    => array(
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_top',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_right',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_bottom',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_left',
							'type'    => 'text',
							'default' => '',
						),
					),
				),
				array(
					'label'     => 'Margin',
					'has_child' => true,
					'fields'    => array(
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_top',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_right',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_bottom',
							'type'    => 'text',
							'default' => '',
						),
						array(
							'key'     => 'wp_megamenu_submenu_menu_padding_left',
							'type'    => 'text',
							'default' => '',
						),
					),
				),
			);

			// New navigation item setting fields
			$other_fields = array();

			$item_setting_fields = array_merge( $listed_fields, $other_fields );

			return $item_setting_fields;
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
