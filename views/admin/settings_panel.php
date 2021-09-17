<div class="wrap wpmm-wrap">
    <form method="post" action="<?php echo esc_url( admin_url('admin.php?page=wp_megamenu') ); ?>">
        <div id="wpmm-tabs" class="wpmm-settings-panel wpmm-main-setting-panel">
            <div class="wpmm-tabs-content wpmm-main-setting-content">
                <div id="tabs-1">
                    <table class="form-table wpmm-option-table wpmm-main-setting-table">
                        <caption><?php esc_html_e('WP Mega Menu Settings', 'wp-megamenu'); ?> </caption>
                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Disable Mega Menu on Mobile', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php
                                $disable_wpmm_on_mobile = get_wpmm_option('disable_wpmm_on_mobile');
                                ?>
                                <label> <input type='checkbox' name='wpmm_options[disable_wpmm_on_mobile]' value='true' <?php checked($disable_wpmm_on_mobile, 'true') ?> > <span class="field-description"> <?php esc_html_e('Disable the Mega Menu for mobile devices.', 'wp-megamenu'); ?> </span>
                                </label>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('CSS Output Location', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php $css_output_location = get_wpmm_option('css_output_location'); ?>

                                <select name="wpmm_options[css_output_location]">
                                    <option value="filesystem" <?php selected($css_output_location, 'filesystem'); ?> ><?php esc_html_e('File System', 'wp-megamenu'); ?></option>
                                    <option value="head" <?php selected($css_output_location, 'head'); ?> ><?php esc_html_e(htmlentities('In <head>'), 'wp-megamenu'); ?></option>
                                </select>

                                <p class="field-description"><?php esc_html_e('The place where the compiled CSS of this menu themes will be stored.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Menu Container Tag', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php $container_tag = get_wpmm_option('container_tag'); ?>
                                <label>
                                    <input type="radio" name="wpmm_options[container_tag]" value="nav" <?php checked($container_tag, 'nav')
                                    ?> /> <?php esc_html_e( htmlentities('<nav>') ); ?>
                                </label>

                                <br />

                                <label>
                                    <input type="radio" name="wpmm_options[container_tag]" value="div" <?php checked($container_tag, 'div')
                                    ?> /><?php esc_html_e( htmlentities('<div>') ); ?>
                                </label>

                                <p class="field-description"><?php esc_html_e('It will wrap the menu with this tag. It’s better to use div tag for non-HTML5 websites.', 'wp-megamenu'); ?></p>

                            </td>
                        </tr>



                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Load font-awesome css in theme', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php $enable_font_awesome = get_wpmm_option('enable_font_awesome');
                                if (empty($enable_font_awesome)){
                                    $enable_font_awesome = 'enable';
                                }
                                ?>
                                <label>
                                    <input type="radio" name="wpmm_options[enable_font_awesome]" value="enable" <?php
                                    checked($enable_font_awesome, 'enable')
                                    ?> /> <?php esc_html_e('Load Font-awesome Icon css'); ?>
                                </label>

                                <br />

                                <label>
                                    <input type="radio" name="wpmm_options[enable_font_awesome]" value="disable" <?php
                                    checked($enable_font_awesome, 'disable')
                                    ?> /> <?php esc_html_e('Disable Font-awesome Icon css'); ?>
                                </label>

                                <p class="field-description"><?php esc_html_e('If your theme already supports Font-awesome icon css, then unload this form here.', 'wp-megamenu'); ?></p>

                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Load Icofont css in theme', 'wp-megamenu'); ?>
                            </th>

                            <td>
                                <?php $enable_icofont = get_wpmm_option('enable_icofont');
                                if (empty($enable_icofont)){
                                    $enable_icofont = 'enable';
                                }
                                ?>
                                <label>
                                    <input type="radio" name="wpmm_options[enable_icofont]" value="enable" <?php
                                    checked($enable_icofont, 'enable')
                                    ?> /> <?php esc_html_e('Load Icofont Icon css'); ?>
                                </label>

                                <br />

                                <label>
                                    <input type="radio" name="wpmm_options[enable_icofont]" value="disable" <?php
                                    checked($enable_icofont, 'disable')
                                    ?> /> <?php esc_html_e('Disable Icofont Icon css'); ?>
                                </label>

                                <p class="field-description"><?php esc_html_e('If your theme already supports Icofont icon css, then unload this form here.', 'wp-megamenu'); ?></p>

                            </td>
                        </tr>

                        <tr class="wpmm-field wpmm-field-group">
                            <th>
                                <?php esc_html_e('Responsive Breakpoint', 'wp-megamenu'); ?>
                            </th>
                            <td>
                                <?php
                                $responsive_breakpoint = get_wpmm_option('responsive_breakpoint');
                                if( ! $responsive_breakpoint){
                                    $responsive_breakpoint = '767px';
                                }
                                ?>
                                <input type="text" name="wpmm_options[responsive_breakpoint]" value="<?php esc_attr_e( $responsive_breakpoint ); ?>" required="required" placeholder="<?php esc_attr_e('Responsive Breakpoint', 'wp-megamenu');
                                ?>" />
                                <p class="field-description"><?php esc_html_e('Set the width at which the menu turns into a mobile menu. 0px will indicate to disable responsive menu. Default value is 767px.', 'wp-megamenu'); ?></p>
                            </td>
                        </tr>


                        <tr class="wpmm-field wpmm-field-group">
                            <th>
			                    <?php esc_html_e('Manual Integration Code', 'wp-megamenu'); ?>
                            </th>
                            <td>

                                <h3><?php esc_html_e('Integration code by nav menu', 'wp-megamenu'); ?></h3>

                                <a href="javascript:;" class="nav-integration-code-by-slug"><?php esc_html_e( 'Show by slug', 'wp-megamenu' ); ?></a>,
                                <a href="javascript:;" class="nav-integration-code-by-id"><?php esc_html_e( 'Show by ID', 'wp-megamenu' ); ?></a>

                                <?php
                                $navs = wp_get_nav_menus();

                                if ( is_array( $navs ) && count( $navs ) ) {
                                    foreach ( $navs as $nav ) { ?>

                                        <div class="wp-megamenu-integration-code integration-code-by-id">

                                        <h4><?php esc_html( $nav->name ); ?></h4>
                                        <p class="integration-code-row"> <span>PHP</span>  <code> &lt;?php wp_megamenu(array('menu' => '<?php esc_html_e( $nav->term_id ); ?>')); ?&gt</code> </p>
                                        <p class="integration-code-row"> <span>SHORTCODE</span> <code> [wp_megamenu menu="<?php esc_html_e( $nav->term_id ); ?>"] </code> </p>

                                    </div>

	                                <div class="wp-megamenu-integration-code integration-code-by-slug" style="display: none;">
	                                    <h4><?php esc_html_e( $nav->name ); ?></h4>
	                                    <p class="integration-code-row"> <span>PHP</span>  <code> &lt;?php wp_megamenu(array('menu' => '<?php esc_html_e( $nav->slug ); ?>')); ?&gt;</code> </p>
	                                    <p class="integration-code-row"> <span>SHORTCODE</span> <code> [wp_megamenu menu="<?php esc_html_e( $nav->slug ); ?>"] </code> </p>
	                                </div>

                                   <?php }
                                }

                                ?>


                                <h3><?php esc_html_e('Integration code by theme location', 'wp-megamenu'); ?></h3>

                                <?php
                                    $nav_location = get_registered_nav_menus();

                                    if ( is_array( $nav_location ) && count( $nav_location ) ) {
                                        foreach ( $nav_location as $nav_key => $nav ) { ?>

                                            <h4><?php esc_html_e( $nav ); ?></h4>

    	                                    <div class="wp-megamenu-integration-code">
    	                                        <p class="integration-code-row"> <span>PHP</span>  <code> &lt;?php wp_megamenu(array('theme_location' => '<?php esc_html_e( $nav_key ); ?>')); ?&gt;</code> </p>
    	                                        <p class="integration-code-row"> <span>SHORTCODE</span> <code> [wp_megamenu theme_location="<?php esc_html_e( $nav_key ); ?>"] </code> </p>

    	                                    </div>
                                      <?php  }
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="wpmm-form-submit-button submit-btn-wrap submit-btn-main-settings btn-theme-setting-wrap">
            <input type="hidden" name="wpbb_settings_panel" value="true" />
	        <?php wp_nonce_field( 'wpmm_settings_nonce_action', 'wpmm_settings_nonce_field' ); ?>
            <?php submit_button(); ?>
        </div>

    </form>
</div>