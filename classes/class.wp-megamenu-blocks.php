<?php

if ( ! class_exists( 'wpmm_blocks' ) ) {
	class wpmm_blocks {

		public static function init() {
			return new self();
		}

		public function __construct() {
			add_action( 'init', array( $this, 'fancy_custom_block_block_init' ) );
			add_action( 'after_setup_theme', array( $this, 'register_my_menu' ) );
			add_shortcode( 'foobar', array( $this, 'foobar_func' ) );

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


		public function wpmm_menu_list() {
			return wp_get_nav_menus();
			/*
			 $nav_items = array();
			foreach ( wp_get_nav_menus() as $nav ) {
				$nav->is_wpmm = 'wpmm_mega_menu' === get_post_meta( $nav->term_id, 'wpmm_layout', true )['menu_type'] ? true : false;
				$nav_items[]  = $nav;
			}
			return $nav_items; */
		}

		public function wpmm_menu_by_slug( WP_REST_Request $request ) {
			$nav_items = array();
			if ( '-' !== $request['slug'] ) {
				foreach ( wp_get_nav_menu_items( $request['slug'] ) as $navs ) {
					// $nav->is_wpmm = 'wpmm_mega_menu' === get_post_meta( $nav->term_id, 'wpmm_layout', true )['menu_type'] ? true : false;
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


		public function fancy_custom_block_block_init() {
			$blocks = array( 'wpmm-menu' ); // 'wpmm-block',

			foreach ( $blocks as $block ) {
				register_block_type(
					WPMM_DIR . '/blocks/build/' . $block,
					array(
						'render_callback' => array( $this, 'render_block_core_navigation' ),
					)
				);
			}

		}

		public function render_block_core_navigation( $attributes ) {
			// pr($attributes);
			return '<h2>Navigation MegaMenu</h2>';
		}

	}

	wpmm_blocks::init();

}


