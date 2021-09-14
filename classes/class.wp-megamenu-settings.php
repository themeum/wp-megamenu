<?php

/**
 * Class WP_MegaMenu_Settings
 *
 */
if ( ! class_exists('WP_MegaMenu_Settings')){

	class WP_MegaMenu_Settings{

		public static function init(){
			$return = new self();
			return $return;
		}

		public function __construct() {
			add_action('admin_init', array($this, 'save_wpmm_settings'));
		}

		/**
		 * Save the settings
		 */
		public function save_wpmm_settings(){
			if ( ! empty($_POST['wpbb_settings_panel']) && ! $_POST['wpbb_settings_panel'] === 'true' ){
				return;
			}

			//Checking the verified Data
			if( isset( $_POST['wpmm_settings_nonce_field'] )
			    && wp_verify_nonce( $_POST['wpmm_settings_nonce_field'], 'wpmm_settings_nonce_action' ) )
			{
				//processing data.
				$get_wpmm_option = (array) maybe_unserialize(get_option('wpmm_options'));

				if (! empty($_POST['wpmm_options'])){
					$options = $_POST['wpmm_options'];

					foreach ( $options as $key => $value ) {
						if ( 'disable_wpmm_on_mobile' !== $key ) {
							$options[ $key ] = wpmm_sanitize_settings_options( $key );
						}
					}

					//Getting checkmark value
					$options['disable_wpmm_on_mobile'] = get_wpmm_option_input_checkmark( 'disable_wpmm_on_mobile' );

					$wpmm_option = array_merge($get_wpmm_option, $options);
					$wpmm_option = apply_filters('update_wpmm_settings', $wpmm_option);
					update_option('wpmm_options', $wpmm_option);
					do_action('wpmm_regenerate_css');
				}

			}
		}
	}

	WP_MegaMenu_Settings::init();
}