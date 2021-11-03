<?php
/*
Plugin Name: WP Mega Menu
Plugin URI: https://www.themeum.com/product/wp-megamenu/
Description: WP Mega Menu is a beautiful, responsive, highly customizable, and user-friendly drag and drop menu builder plugin for WordPress. Build an awesome mega menu today.
Author: Themeum
Author URI: https://www.themeum.com
Version: 1.4.2
Text Domain: wp-megamenu
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! defined( 'WPMM_VER' ) ){
	define( 'WPMM_VER', '1.4.2' );
}
// Plugin File
if( ! defined( 'WPMM_FILE' ) ){
	define( 'WPMM_FILE', __FILE__ );
}

// Plugin Folder URL
if( ! defined( 'WPMM_URL' ) ){
	define( 'WPMM_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Folder Path
if( ! defined( 'WPMM_DIR' ) ){
	define( 'WPMM_DIR', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'WPMM_BASENAME' ) ) {
	define('WPMM_BASENAME', plugin_basename(__FILE__));
}

// language
add_action( 'init', 'wp_meagmenu_language_load' );
function wp_meagmenu_language_load(){
	$plugin_dir = basename(dirname(__FILE__))."/languages/";
	load_plugin_textdomain( 'wp-megamenu', false, $plugin_dir );
}

include WPMM_DIR.'installation/class.wp-megamenu-initial-setup.php';
include WPMM_DIR.'classes/wp_megamenu_functions.php';
include WPMM_DIR.'classes/class.wp-megamenu-base.php';
include WPMM_DIR.'classes/class.wp-megamenu.php';
include WPMM_DIR.'classes/class.wp-megamenu-widgets.php';
include WPMM_DIR.'classes/class.wp-megamenu-themes.php';
include WPMM_DIR.'classes/class.wp-megamenu-css.php';
include WPMM_DIR.'classes/class.wp-megamenu-settings.php';
include WPMM_DIR.'classes/class.wp-megamenu-export-import.php';
include WPMM_DIR.'libs/wp-megamenu-login-register.php';
include WPMM_DIR.'libs/wpmm-header-cart.php';
