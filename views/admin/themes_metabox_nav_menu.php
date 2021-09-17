<?php
//$selected_nav = absint( get_user_option( 'nav_menu_recently_edited' ) );
$selected_nav =  ! empty( $_REQUEST['menu'] ) ? absint( $_REQUEST['menu'] ) : 0;
if ( ! $selected_nav){
	$selected_nav = absint( get_user_option( 'nav_menu_recently_edited' ) );
}

$selected_nav_theme = get_term_meta($selected_nav, 'wpmm_nav_options', true);
$selected_nav_theme = maybe_unserialize($selected_nav_theme);

$selected_theme_id = null;
if ( ! empty($selected_nav_theme)){
	$selected_theme_id = (int) $selected_nav_theme['theme_id'];
}

$wpmm_theme_title =  get_wpmm_theme_option('wpmm_theme_title', $selected_theme_id);
$wpmm_theme_name =  get_wpmm_theme_option('wpmm_theme_name', $selected_theme_id);

?>
<div id="wpmm_themes" class="wpmm_themes_div">
    <div class="wpmm_themes_metabox_content">
        <div id="wpmm_themes_response"></div>
		<?php
		$get_attached_location_with_menu = get_attached_location_with_menu($selected_nav);

		$current_location = '';
		if ( ! empty($get_attached_location_with_menu)) {
			foreach ($get_attached_location_with_menu as $current_location => $location_name);
			$wpmm_nav_location_settings = get_wpmm_option($current_location);
			?>

            <table>
                <tbody>
                <tr>
                    <td><?php esc_html_e( 'Enable', 'wp-megamenu' ); ?></td>
                    <td>
                        <input type="hidden" name="wpmm_nav_settings[<?php esc_attr_e( $current_location ); ?>][menu_location]" value="<?php esc_attr_e( $current_location ); ?>">

                        <input type="checkbox" class="wpmm_is_enabled" name="wpmm_nav_settings[<?php esc_attr_e( $current_location ); ?>][is_enabled]" value="1" <?php checked( ! empty($wpmm_nav_location_settings['is_enabled'])); ?> >
                    </td>
                </tr>
                </tbody>
            </table>
			<?php
		} else {
			?>
            <div class="wpmm-notice-warning">
                <p>
					<?php esc_html_e( 'This menu is not in any location, please set a location first', 'wp-megamenu' ); ?>
                </p>
            </div>
			<?php
		}
		?>

        <table>
            <tbody>
            <tr>
                <td><?php esc_html_e('Theme', 'wp-megamenu') ?></td>
                <td>
					<?php
					$post_args = array(
						'post_type'   => 'wpmm_theme',
						'post_status' => 'publish',
						'order_by'    => 'desc'
					);
					$query     = new WP_Query( $post_args );

					if ( $query->have_posts() ) {
						echo '<ul>';
						echo "<li> <label class='menu-item-title' > " . esc_html__( 'Disable Theme', 'wp-megamenu' ) . " <input data-title='Disable-Theme' type='radio' value='0' name='selected_theme' " . checked( 0, $selected_theme_id, false ) . " />  </label> </li> ";
						while ( $query->have_posts() ): $query->the_post();
							?>
                            <li>
                                <label class="menu-item-title">
									<?php esc_html_e( get_the_title() ); ?>
                                    <input type="radio" data-title="<?php esc_attr_e( get_the_title() ); ?>" value="<?php esc_attr_e( get_the_ID() ); ?>" name="selected_theme" <?php checked(get_the_ID(), $selected_theme_id); ?> />
                                </label>
                            </li>
							<?php
						endwhile;

						echo '</ul>';
						$query->reset_postdata();
					}
					?>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    <p class="button-controls wp-clearfix">
        <span class="add-to-menu">
            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'wp_megamenu_nav_export' ) ) ); ?>" class="button button-primary menu-save wp-megamenu-nav-export" style="margin-right: 20px;" ><?php esc_html_e('Export Mega Menu', 'wp-megamenu'); ?> </a>
            <?php wp_nonce_field( 'wpmmm_nav_export_action', 'wpmmm_nav_export_nonce_field' ) ?>
            <input type="submit"  class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Save', 'wp-megamenu'); ?>" name="save_wpmm_theme_nav" id="save_wpmm_theme_nav" />
        </span>
    </p>
</div><!-- /.wpmm_themes_div -->