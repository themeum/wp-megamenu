<?php

if ( ! class_exists( 'wpmm_blocks' ) ) {
	class wpmm_blocks {

		public static function init() {
			return new self();
		}

		public function __construct() {
			add_action( 'init', array( $this, 'fancy_custom_block_block_init' ) );
		}

		function fancy_custom_block_block_init() {

			// automatically load dependencies and version
			$asset_file = include plugin_dir_path( __FILE__ ) . 'blocks/build/index.asset.php';

			wp_register_script(
				'wpmm-block-editor',
				plugins_url('js/wpmm.dev.js',__DIR__),
				array(),
				array()
			);

			/* wp_register_style(
				'wpmm-block-editor',
				plugins_url( 'blocks/editor.css', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . 'blocks/editor.css' )
			);

			wp_register_style(
				'wpmm-blocks',
				plugins_url( 'blocks/style.css', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . 'blocks/style.css' )
			); */

			register_block_type(
				'wpmm/heading',
				array(
					// 'editor_script' => 'wpmm-block-editor',
					// 'editor_style'  => 'wpmm-block-editor',
					'style'         => 'wpmm-blocks',
				)
			);
		}
	}

	wpmm_blocks::init();

}


