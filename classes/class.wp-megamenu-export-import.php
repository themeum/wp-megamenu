<?php
/**
 * Class WP_MegaMenu_Export_Import
 *
 */
if ( ! class_exists('WP_MegaMenu_Export_Import')){
	class WP_MegaMenu_Export_Import{

		public static function init(){
			$return = new self();
			return $return;
		}

		public function __construct() {
			add_action('wp_ajax_export_wp_megamenu_nav_menu', array($this, 'export_wp_megamenu_nav_menu'));
			add_action('admin_init', array($this, 'wpmm_import_menu'));
		}

		/**
		 * Export the Menu
		 */
		function export_wp_megamenu_nav_menu(){
			global $wpdb;

			$wpmmm_nav_export_nonce_field = isset($_POST['wpmmm_nav_export_nonce_field']) ? $_POST['wpmmm_nav_export_nonce_field'] : false;

			if (! current_user_can( 'administrator' ) || ! isset( $_POST['wpmmm_nav_export_nonce_field'] ) || ! wp_verify_nonce( $wpmmm_nav_export_nonce_field, 'wpmmm_nav_export_action' ) ) {
                return;
			}
			
			if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'export_wp_megamenu_nav_menu' ) {
				return;
			}

			$nav_menu_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
			if ( ! $nav_menu_id ) {
				$nav_menu_id = absint( get_user_option( 'nav_menu_recently_edited' ) );
			}

			if ( ! $nav_menu_id ) {
				return;
			}

			$term = get_term( $nav_menu_id );

			$nav_item_posts = array();
			$testing_ids = array();
			$query_term_relationships = $wpdb->get_results("select * from {$wpdb->term_relationships} WHERE term_taxonomy_id = {$nav_menu_id} ");
			if (is_array($query_term_relationships) && count($query_term_relationships)){

				foreach ($query_term_relationships as $relationship){
					$object = get_post($relationship->object_id, ARRAY_A);

					$post_key_only = array('ID','post_author', 'post_content', 'post_title', 'post_excerpt','post_status', 'post_type');
					$object_post = array_intersect_key($object, array_flip($post_key_only));

					$post_meta = array();
					$post_meta_query = $wpdb->get_results("select * from {$wpdb->postmeta} where post_id = {$relationship->object_id}");

					if (is_array($post_meta_query) && count($post_meta_query)){
						foreach ($post_meta_query as $mvalue){
							$post_meta[$mvalue->meta_key] = $mvalue->meta_value;

							if ($mvalue->meta_key === '_menu_item_object_id' && $mvalue->meta_value != $relationship->object_id){
								//die(print_r($object_post));

								//Get origin post from '_menu_item_object_id' meta
								$origin_post = get_post($mvalue->meta_value, ARRAY_A);
								$testing_ids[] = $mvalue->meta_value;

								if (is_array($origin_post) && count($origin_post)){
									$origin_object_post = array_intersect_key($origin_post, array_flip($post_key_only));
									$origin_object_post['postmeta'] = array();
									$nav_item_posts['origin_posts'][$mvalue->meta_value] = $origin_object_post;
								}
							}
						}
						$object_post['postmeta'] = $post_meta;
					}
					$nav_item_posts[] = $object_post;
					//die(print_r($object_post));
				}
			}

			$widgets_options = get_option('sidebars_widgets');
			global $wp_registered_widget_controls;
			$widgets = array();

			if (isset( $widgets_options['wpmm'])){
				$wpbb_sidebar_widgets = $widgets_options['wpmm'];
				$widgets['sidebars'] = array('wpmm' => $wpbb_sidebar_widgets );

				foreach ($wpbb_sidebar_widgets as $saved_widget_id){
					if (isset($wp_registered_widget_controls[$saved_widget_id]) && isset
						($wp_registered_widget_controls[$saved_widget_id]['id_base'])  ){

						$id_base = $wp_registered_widget_controls[$saved_widget_id]['id_base'];

						$widget_option_name = "widget_".$id_base;
						$get_widget = get_option($widget_option_name);
						$widget_incremental_id = str_replace($id_base.'-', '', $saved_widget_id );

						$widgets['widgets_item'][$widget_option_name][$widget_incremental_id] = $get_widget[$widget_incremental_id];
					}
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
				'widgets'   => $widgets,
			);

			$file_name = 'wp-megamenu-nav-'.$nav_menu_id.'.txt';
			$handle = fopen($file_name, "w");
			fwrite($handle, base64_encode(serialize($nav)) );
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

		function sample_admin_notice__success() {
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Menu has been imported successfully', 'wp-megamenu' ); ?></p>
			</div>
			<?php
		}

		/*
		 * Import menu from the exported file
		 */
		function wpmm_import_menu(){
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

			if ( isset( $_POST['wpmmm_import_menu_nonce_field'] ) && wp_verify_nonce( $_POST['wpmmm_import_menu_nonce_field'], 'wpmmm_import_menu_action' )) {
				$uploaded_file = $_FILES['wpmm_import_menu_file'];

				if ( $uploaded_file['error'] == 0 ) {
					$wp_check_filetype = wp_check_filetype( $uploaded_file['name']);
					if ( ! empty($wp_check_filetype['ext']) && strtolower($wp_check_filetype['ext']) === 'txt') {

						$serilized_data = file_get_contents($uploaded_file['tmp_name']);
						$serilized_data = trim($serilized_data);


						if (wpmm_is_serialized(base64_decode($serilized_data))) {
							$site_url = site_url();
							$post_data = unserialize(base64_decode($serilized_data));
							$import_site_url = $post_data['site_url'];

							$post_data = json_decode(str_replace($import_site_url, $site_url, json_encode($post_data)));

							$origin_posts = array();
							if (isset($post_data->posts->origin_posts)){
								$origin_posts = (array) $post_data->posts->origin_posts;
								unset($post_data->posts->origin_posts);
							}

							$post_data->posts = (array) $post_data->posts;

							if (isset($post_data->widgets)){

								$widgets_data = json_decode(json_encode($post_data->widgets), true);
								$widgets_in_sidebar = $widgets_data['sidebars']['wpmm'];
								$widgets_options = get_option('sidebars_widgets');

								foreach ($widgets_in_sidebar as $widget_id){
									
									$saved_widget_id = substr($widget_id, strrpos($widget_id, '-')+1);
									$widget_based_id = substr($widget_id, 0, strrpos($widget_id, '-'));
									$next_widget_id = next_widget_id_number($widget_based_id);
			
									$widgets_options['wpmm'][] = $widget_based_id.'-'.$next_widget_id;

									update_option('sidebars_widgets', $widgets_options);
									$widget_option_name = "widget_".$widget_based_id;

									$widgets_data['widgets_item'][$widget_option_name][$next_widget_id] = $widgets_data['widgets_item'][$widget_option_name][$saved_widget_id];

									update_option($widget_option_name, $widgets_data['widgets_item'][$widget_option_name]);
									//die(print_r($widgets_data['widgets_item'][$widget_option_name]));
								}
							}

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

										if ($key === '_menu_item_object_id' && $value > 0 && isset($origin_posts[$value])){
											$origin_post = (array) $origin_posts[$value];
											unset($origin_post['postmeta'], $origin_post['ID']);

											//Insert Origin Post
											$origin_post_ID = wp_insert_post($origin_post);

											//Settings new Origin Post ID
											$value = $origin_post_ID;
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
											), array('meta_id'  => $previous_value->meta_id ) );
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

							add_action( 'admin_notices', array($this, 'sample_admin_notice__success') );

						}
					}


				}
			}

		}

	}

	WP_MegaMenu_Export_Import::init();
}