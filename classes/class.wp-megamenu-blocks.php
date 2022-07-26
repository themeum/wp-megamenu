<?php

if ( ! class_exists( 'wpmm_blocks' ) ) {
	/**
	 * This wpmm_blocks is for managing wpmm blocks.
	 */
	class wpmm_blocks {

		public static function init() {
			return new self();
		}

		/**
		 * Constructor of the class
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'wpmm_block_type_init' ) );
			add_action( 'after_setup_theme', array( $this, 'register_my_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wpmm_block_style_script' ) );

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
							'callback' => array( $this, 'wpmm_menu_by_slug' ),
						)
					);
				}
			);
		}


		public function register_my_menu() {
			register_nav_menu( 'block', __( 'Block Menu', 'wp-megamenu' ) );
		}

		/**
		 * Get registered menu items.
		 *
		 * @return array
		 */
		public function wpmm_menu_list() {
			return wp_get_nav_menus();
		}

		/**
		 * Get menu items by slug
		 *
		 * @param WP_REST_Request $request is a slug.
		 *
		 * @return array
		 */
		public function wpmm_menu_by_slug( WP_REST_Request $request ) {
			$nav_items = array();
			if ( '-' !== $request['slug'] ) {
				foreach ( wp_get_nav_menu_items( $request['slug'] ) as $navs ) {
					foreach ( $navs as $key => $nav ) {
						$nav_item[ $key ] = $nav;
					}
					if ( 'wpmm_mega_menu' === get_post_meta( $navs->ID, 'wpmm_layout', true )['menu_type'] ) {
						$nav_item['post_url'] = '#';
						$nav_item['is_wpmm']  = true;
					} else {
						$nav_item['post_url'] = get_permalink( $navs->ID );
						$nav_item['is_wpmm']  = false;
					}
					$nav_items[] = $nav_item;

				}
			}
			return $nav_items;
		}


		public function wpmm_block_style_script() {
			wp_register_script( 'wpmm_editorScript', WPMM_URL . 'blocks/build/index.js', array(), false, true );
			wp_register_style( 'wpmm_editorStyle', WPMM_URL . 'blocks/build/index.css', WPMM_VER );
			wp_register_style( 'wpmm_blockStyle', WPMM_URL . 'blocks/build/style-index.css', WPMM_VER );

		}

		/**
		 * Get json configuration of wpmm blocks
		 *
		 * @return array
		 */
		public function get_block_config_json() {
			$block_array   = array();
			$block_array[] = json_decode( file_get_contents( WPMM_DIR . '/blocks/src/wpmm-menu/block.json' ), true );
			$block_array[] = json_decode( file_get_contents( WPMM_DIR . '/blocks/src/wpmm-block/block.json' ), true );

			return $block_array;
		}

		/**
		 * Register wpmm blocks
		 *
		 * @return void
		 */
		public function wpmm_block_type_init() {

			$args = array(
				'editor_script' => 'wpmm_editorScript',
				'editor_style'  => 'wpmm_editorStyle',
				'style'         => 'wpmm_blockStyle',
			);

			$block_list = $this->get_block_config_json();

			foreach ( $block_list  as $block ) {

				register_block_type(
					$block['name'],
					$args
				);

			}
		}

		/**
		 * Output of wpmm menu block
		 *
		 * @param array $attributes is block attributes.
		 *
		 * @return string
		 */
		public function render_block_core_navigation( $attributes = array() ) {
			// pr($attributes);
			return '<h2>Navigation MegaMenu</h2>';
		}
	}
}

/**
 * Initialize wpmm_blocks()
 */
wpmm_blocks::init();



