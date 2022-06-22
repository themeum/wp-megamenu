<?php

if ( ! class_exists( 'wpmm_blocks' ) ) {
	class wpmm_blocks {

		public static function init() {
			return new self();
		}

		public function __construct() {
			add_action( 'init', array( $this, 'fancy_custom_block_block_init' ) );

			add_action(
				'rest_api_init',
				function() {

					register_rest_route(
						'wpmm',
						'nav_menus',
						array(
							'methods'  => 'GET',
							'callback' => array( $this, 'wpmm_menu_list' ),
						)
					);
					register_rest_route(
						'wpmm',
						'nav_menu/(?P<slug>[a-zA-Z0-9-]+)',
						array(
							'methods'  => 'GET',
							'callback' => array( $this, 'wpmm_menu_item' ),
						)
					);
				}
			);
		}
		public function wpmm_menu_list() {
			return wp_get_nav_menus();
		}

		public function wpmm_menu_item( WP_REST_Request $request ) {

			// wp_send_json($request['slug']);
			// wp_send_json($_GET);
			return wp_get_nav_menu_items( $request['slug'] );
		}


		public function fancy_custom_block_block_init() {
			$blocks = array( 'wpmm-menu' ); // 'wpmm-block',

			foreach ( $blocks as $block ) {
				register_block_type( WPMM_DIR . '/blocks/build/' . $block );
			}

		}

		public function render_block_core_navigation() {
			return '<h2>Navigation MegaMenu</h2>';
		}

	}

	wpmm_blocks::init();

}


