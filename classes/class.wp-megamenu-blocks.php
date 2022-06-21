<?php

if ( ! class_exists( 'wpmm_blocks' ) ) {
	class wpmm_blocks {

		public static function init() {
			return new self();
		}

		public function __construct() {
			add_action( 'init', array( $this, 'fancy_custom_block_block_init' ) );
		}

		public function fancy_custom_block_block_init() {
			$blocks = array( 'wpmm-block', 'wpmm-menu' );

			foreach ( $blocks as $block ) {
				if ( 'wpmm-menu' === $block ) {
					$attr = array(
						'render_callback' => 'render_block_core_navigation',
						'attributes'      => array(
							'home_url' => array(
								'default' => get_home_url(),
								'type'    => 'string',
							),
							'home_prl' => array(
								'default' => get_default_block_editor_settings(),
								'type'    => 'array',
							),
						),
					);
				} else {
					$attr = array();
				}

				register_block_type( WPMM_DIR . '/blocks/build/' . $block, $attr );
			}

		}

		public function render_block_core_navigation() {
			return '<h2>Navigation MegaMenu</h2>';
		}

	}

	wpmm_blocks::init();

}


