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
				register_block_type( WPMM_DIR . '/blocks/build/' . $block );
			}
		}

	}

	wpmm_blocks::init();

}


