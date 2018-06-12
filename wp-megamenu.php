<?php
/*
Plugin Name: WP Mega Menu
Plugin URI: https://www.themeum.com/product/wp-megamenu/
Description: WP Mega Menu is a beautiful, responsive, highly customizable, and user-friendly drag and drop menu builder plugin for WordPress. Build an awesome mega menu today.
Author: Themeum
Author URI: https://www.themeum.com
Version: 1.1.7
Text Domain: wp-megamenu
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! defined( 'WPMM_VER' ) ){
    define( 'WPMM_VER', '1.1.7' );
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
include WPMM_DIR.'classes/class.wp-megamenu-base.php';
include WPMM_DIR.'classes/class.wp-megamenu.php';
include WPMM_DIR.'classes/class.wp-megamenu-widgets.php';
include WPMM_DIR.'classes/class.wp-megamenu-themes.php';
include WPMM_DIR.'classes/wp_megamenu_functions.php';
include WPMM_DIR.'classes/class.wp-megamenu-css.php';
include WPMM_DIR.'classes/class.wp-megamenu-settings.php';



add_action('admin_init', 'export_wp_megamenu_nav_menu');
function export_wp_megamenu_nav_menu(){
	global $wpdb;
	if ( ! isset($_GET['action']) || $_GET['action'] !== 'wp_megamenu_nav_export'){
		return;
	}

	$nav_menu_id = (int) sanitize_text_field($_GET['menu']);
	$term = get_term($nav_menu_id);

	$nav_item_posts = array();
	$query_term_relationships = $wpdb->get_results("select * from {$wpdb->term_relationships} WHERE term_taxonomy_id = {$nav_menu_id} ");
	if (is_array($query_term_relationships) && count($query_term_relationships)){

		//echo '<pre>';
		//die(print_r($query_term_relationships));

		foreach ($query_term_relationships as $relationship){

			$object = get_post($relationship->object_id, ARRAY_A);
			$post_key_only = array('ID','post_author', 'post_content', 'post_title', 'post_excerpt','post_status', 'post_type');
			$object_post = array_intersect_key($object, array_flip($post_key_only));

			$post_meta = array();
			$post_meta_query = $wpdb->get_results("select * from {$wpdb->postmeta} where post_id = {$relationship->object_id}");

			if (is_array($post_meta_query) && count($post_meta_query)){
				foreach ($post_meta_query as $mvalue){
					$post_meta[$mvalue->meta_key] = $mvalue->meta_value;
				}
				$object_post['postmeta'] = $post_meta;
			}
			$nav_item_posts[] = $object_post;

			//die(print_r($object_post));
		}
	}

	$nav = array(
		'site_url'  => site_url(),
		'term' => $term,

		'terms'         => array(
			'term_id'           => $term->term_id,
			'name'              => $term->name,
			'slug'              => $term->slug,
			'term_group'        => $term->term_group,
		),
		'term_taxonomy'         => array(
			'term_taxonomy_id'  => $term->term_taxonomy_id,
			'term_id'           => $term->term_id,
			'taxonomy'          => $term->taxonomy,
			'description'       => $term->description,
			'parent'            => $term->parent,
			'count'             => $term->count,
		),
		'posts' => $nav_item_posts,
	);

	//echo '<pre>';
	//die(print_r($nav_item_posts));

	$file_name = 'wp-megamenu-nav-'.$nav_menu_id.'.txt';
	$handle = fopen($file_name, "w");
	fwrite($handle, serialize($nav));
	fclose($handle);

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file_name));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file_name));
	readfile($file_name);
	exit();
}



add_action('admin_init', 'wpmm_import_menu');
function wpmm_import_menu(){
	global $wpdb;

	if ( isset( $_POST['wpmmm_import_menu_nonce_field'] ) && wp_verify_nonce( $_POST['wpmmm_import_menu_nonce_field'], 'wpmmm_import_menu_action' )) {
		$uploaded_file = $_FILES['wpmm_import_menu_file'];
		if ( $uploaded_file['error'] == 0 ) {
			$wp_check_filetype = wp_check_filetype( $uploaded_file['name']);
			if ( ! empty($wp_check_filetype['ext']) && strtolower($wp_check_filetype['ext']) === 'txt') {

				$serilized_data = file_get_contents($uploaded_file['tmp_name']);
				if (wpmm_is_serialized($serilized_data)) {
					$site_url = site_url();
					$post_data = unserialize($serilized_data);
					$import_site_url = $post_data['site_url'];

					$post_data = json_decode(str_replace($import_site_url, $site_url, json_encode($post_data)));

					//echo '<pre>';
					//die(print_r($post_data));

					//Insert Term
					$terms_name = $post_data->terms->name;
					$duplicate_nav_count = (int) $wpdb->get_var("SELECT COUNT(term_id) FROM {$wpdb->terms} WHERE NAME LIKE '%{$terms_name}%' ");
					if ($duplicate_nav_count){
						$terms_name = $terms_name." ($duplicate_nav_count)";
					}
					$insert_term = wp_insert_term($terms_name,'nav_menu');
					$term_id = $insert_term['term_id'];

					//Insert Post and Post meta
					if (is_array($post_data->posts) && count($post_data->posts)){
						$previous_and_new_post_ids = array();
						$parent_item_ids = array();

						foreach ($post_data->posts as $post){
							$previousPostID = $post->ID;
							$post = (array) $post;
							$post_with_id = $post;
							unset($post['ID']);

							$post_meta = (array) $post['postmeta'];
							unset($post['postmeta']);

							$post_id = wp_insert_post($post);
							$previous_and_new_post_ids[$previousPostID] = $post_id;

							//Deleting previous post meta
							$wpdb->query("delete from {$wpdb->postmeta} WHERE post_id = '{$post_id}' ");
							//attaching nav with post id
							wp_set_object_terms($post_id, $term_id, 'nav_menu');
							foreach ($post_meta as $key => $value){
								if ($key === '_menu_item_menu_item_parent' && $value > 0){
									$parent_item_ids[$post_id] = $value;
								}

								//Updating previous posts meta
								//update_post_meta($post_id, $key, $value);

								//Update meta value if exists, or creating new
								$previous_value = $wpdb->get_row("SELECT * from {$wpdb->postmeta} WHERE post_id={$post_id} AND meta_key='{$key}' ");
								if ($previous_value){
									$wpdb->update( $wpdb->postmeta, array(
										'post_id'    => $post_id,
										'meta_key'   => $key,
										'meta_value' => $value,
									), array('meta_id' => $previous_value->meta_id ) );
								}else {
									$wpdb->insert( $wpdb->postmeta, array(
										'post_id'    => $post_id,
										'meta_key'   => $key,
										'meta_value' => $value,
									) );
								}

							}
						}

						//Setting parent menu items
						if (is_array($parent_item_ids) && count($parent_item_ids)){
							foreach ($parent_item_ids as $postID => $parentID){
								if (isset($previous_and_new_post_ids[$parentID])){
									update_post_meta($postID, '_menu_item_menu_item_parent', $previous_and_new_post_ids[$parentID]);
								}
							}
						}


					}

				}
			}

		}
	}


}

