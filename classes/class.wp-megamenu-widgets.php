<?php

/**
 * Class wp_megamenu
 */
if ( ! class_exists('wp_megamenu_widgets')) {

    class wp_megamenu_widgets{

        /**
         * @return wp_megamenu_widgets
         */
        public static function init(){
            $return = new self();
            return $return;
        }

        /**
         * wp_megamenu_widgets constructor.
         */
        public function __construct(){
            add_action('init', array($this, 'wpmm_register_sidebar'));

            add_action('wp_ajax_wpmm_add_widget_to_item', array($this, 'wpmm_add_widget_to_item'));
            add_action('wp_ajax_wpmm_get_widget_to_item', array($this, 'wpmm_get_widget_to_item'));
            add_action('wp_ajax_wpmm_save_widget', array($this, 'wpmm_save_widget'));
            add_action('wp_ajax_wpmm_delete_widget', array($this, 'wpmm_delete_widget'));

            add_action('wp_ajax_wpmm_increase_widget_column', array($this, 'wpmm_increase_widget_column'));
            add_action('wp_ajax_wpmm_reorder_items', array($this, 'wpmm_reorder_items'));
            add_action('wp_ajax_wpmm_reorder_row', array($this, 'wpmm_reorder_row'));
            add_action('wp_ajax_wpmm_delete_row', array($this, 'wpmm_delete_row'));
            add_action('wp_ajax_wpmm_reorder_col', array($this, 'wpmm_reorder_col'));

            //Add widget
            add_action('wp_ajax_wpmm_drag_to_add_widget_item', array($this, 'wpmm_drag_to_add_widget_item'));

            //Edit Widget by ajax, @since v.1.0
            add_action('wp_ajax_wpmm_edit_widget', array($this, 'wpmm_edit_widget'));
        }

        /**
         * Register sidebar to call it smartly
         */
        public function wpmm_register_sidebar() {
            register_sidebar(
                array(
                    'id' => 'wpmm',
                    'name' => __("WP Mega Menu Widgets", "wp-megamenu"),
                    'description'   => __("This is for programmatically add widget to sidebar. It will be not show in the menu if you add this directly from here. Insted of you have to add any item from the menu ", "wp-megamenu")
                )
            );
        }

        /**
         * @param $widget_id
         *
         * Generate Widget form.
         *
         * @since v.1.0
         */
        public function show_wpmm_widget_form( $widget_id ) {
            global $wp_registered_widget_controls;

            $id_base = $this->wpmm_get_id_base_for_widget_id( $widget_id );
            $control = $wp_registered_widget_controls[ $widget_id ];
            $nonce = wp_create_nonce( 'megamenu_save_widget_' . $widget_id );
            ?>

            <form method='post'  class="wpmm_widget_save_form">
                <input type="hidden" name="widget-id" class="widget-id" value="<?php esc_attr_e( $widget_id ); ?>" />
                <input type='hidden' name='id_base'   class="id_base" value='<?php esc_attr_e( $id_base ); ?>' />
                <input type='hidden' name='widget_id' value='<?php esc_attr_e( $widget_id ); ?>' />
                <input type='hidden' name='_wpnonce'  value='<?php echo $nonce ?>' />
                <div class='widget-content'>
                    <?php
                        if ( is_callable( $control['callback'] ) ) {
                            call_user_func_array( $control['callback'], $control['params'] );
                        }
                    ?>

                    <div class='widget-controls'>
                        <a class='delete' href='#delete'><?php esc_html_e( 'Delete', 'wp-megamenu' ); ?></a> |
                        <a class='close' href='#close'><?php esc_html_e( 'Close', 'wp-megamenu' ); ?></a>
                    </div>

                    <?php submit_button( __( 'Save' ), 'button-primary alignright', 'savewidget', false ); ?>
                    <div class="clear"></div>
                </div>
            </form>
            <?php
        } 

        /**
         * get all registere available widget
         */
        public function get_all_registered_widget(){
            global $wp_widget_factory;

            $widgets = array();
            foreach( $wp_widget_factory->widgets as $widget ) {
                $widgets[] = array(
                    'name' => $widget->name,
                    'id_base' => $widget->id_base
                );
            }
            return $widgets;
        }

        /**
         * @param $id
         * @return string
         *
         * Show a widget output in the menu
         */
        public function show_widget( $id ) {
            
            //error_reporting('E_ALL & ~E_NOTICE');
            global $wp_registered_widgets;
            
            $params = array_merge(
                array( array_merge( array( 'widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name'] ) ) ),
                (array) $wp_registered_widgets[$id]['params']
            );
            $params[0]['before_title'] = apply_filters( "wpmm_before_widget_title", '<h4 class="wpmm-item-title">',
                $wp_registered_widgets[$id] );
            $params[0]['after_title'] = apply_filters( "wpmm_after_widget_title", '</h4>', $wp_registered_widgets[$id] );
            $params[0]['before_widget'] = apply_filters( "wpmm_before_widget", "", $wp_registered_widgets[$id] );
            $params[0]['after_widget'] = apply_filters( "wpmm_after_widget", "", $wp_registered_widgets[$id] );

            $callback = $wp_registered_widgets[$id]['callback'];

            if ( is_callable( $callback ) ) {
                ob_start();
                call_user_func_array( $callback, $params );
                return ob_get_clean();
            }
        }

        /**
         * @return bool | array
         *
         * get wp megamenu sidebar widgets
         */
        public function get_sidebar_widgets() {
            $widget = wp_get_sidebars_widgets();
            if ( ! isset( $widget[ 'wpmm'] ) ) {
                return false;
            }
            return $widget[ 'wpmm' ];
        }


        /**
         * @param $widgets_array
         *
         * Set widgets to wp megamenu sidebar
         */
        private function set_sidebar_widgets( $widgets_array ) {
            $widgets = wp_get_sidebars_widgets();
            $widgets[ 'wpmm' ] = $widgets_array;
            wp_set_sidebars_widgets( $widgets );
        }

        /**
         * @param $new_widget_id
         * @return mixed
         *
         */
        private function add_widget_to_wpmm_sidebar( $new_widget_id ) {
            $new_widgets = $this->get_sidebar_widgets();
            $new_widgets[] = $new_widget_id;
            $this->set_sidebar_widgets($new_widgets);
            return $new_widget_id;
        }

        /**
         * @param $widget_id
         * @return bool
         *
         * Get base widget id
         */
        public function wpmm_get_id_base_for_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;

            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];
            $id_base = isset( $control['id_base'] ) ? $control['id_base'] : $control['id'];
            return $id_base;
        }

        /**
         * @param $widget_id
         * @return bool|string
         */
        public function wpmm_get_widget_class_by_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;

            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];

            $widget_class_name = get_class($control['callback'][0]);
            return $widget_class_name;
        }

        /**
         * @param $widget_id
         * @return bool|string
         */
        public function wpmm_get_widget_name_by_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;
            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];
            $name = isset( $control['name'] ) ? $control['name'] : '';
            return $name;
        }

        /**
         * Add widget or item
         */
        public function wpmm_add_widget_to_item(){
            require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

            $widget_base_id = sanitize_text_field($_POST['widget_id']);
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);

            $next_id = next_widget_id_number( $widget_base_id );
            $widget_id = $widget_base_id.'-'.$next_id;

            $this->add_widget_to_wpmm_sidebar($widget_id);

            //get new widget id
            $get_widget_option = get_option('widget_'.$widget_base_id);
            $get_widget_option[$next_id] = array();
            update_option('widget_'.$widget_base_id, $get_widget_option);

            //Settings in item post meta
            $widget_name = $this->wpmm_get_widget_name_by_widget_id($widget_id);
            $widget_class = $this->wpmm_get_widget_class_by_widget_id($widget_id);

            //$get_menu_settings = (array) get_post_meta($menu_item_id, 'wpmm_layout', true);

            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            //Setting item settings in the menu
            //$get_menu_settings['items'][] = array( 'item_type' => 'widget', 'widget_class' => $widget_class, 'widget_name' => $widget_name, 'widget_id' => $widget_id, 'options' => array() );
            $get_layout['layout'][0]['row'][0]['items'][] = array( 'item_type' => 'widget', 'widget_class' => $widget_class, 'widget_name' => $widget_name, 'widget_id' => $widget_id, 'options' => array() );

            update_post_meta($menu_item_id, 'wpmm_layout', $get_layout);
            //update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);

            //Send json success
            wp_send_json_success( array('message' => __('Wiedget added', 'wp-megamenu'), 'widget_id' => $widget_id) );
        }

        /**
         * Call item to panel
         */
        public function wpmm_get_widget_to_item(){
            $widget_id = sanitize_text_field($_POST['widget_id']);
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);

            $get_menu_settings = get_post_meta($menu_item_id, 'wpmm_layout', true);
            if ( ! empty($get_menu_settings['items'])){
                foreach ($get_menu_settings['items'] as $key => $value){
                    if ($value['widget_id'] === $widget_id){
                        $this->widget_items($value['widget_id'], $get_menu_settings, $key);
                        die();
                    }
                }
            }
        }

        /**
         * @param $widget_id
         * @param $menu_item_id
         *
         *
         * Get widget item in item settings panel
         */
        public function widget_items($widget_id, $menu_item_id, $widget_key_id = 0){
            ?>
            <div id="widget-<?php esc_attr_e( $widget_id ); ?>" class="widget"  data-item-key-id="<?php
            esc_attr_e( $widget_key_id ); ?>">
                <div class="widget-top">

                    <div class="widget-title-action">
                        <button type="button" class="widget-action hide-if-no-js widget-form-open" aria-expanded="false">
                            <span class="screen-reader-text"><?php printf( __( 'Edit widget: %s' ), $this->wpmm_get_widget_name_by_widget_id($widget_id) ); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>

                    </div>
                    <div class="widget-title">
                        <h3><?php esc_html_e( $this->wpmm_get_widget_name_by_widget_id($widget_id) ); ?><span class="in-widget-title"></span></h3>
                    </div>
                </div>

                <div class="widget-inner widget-inside">
                    <?php $this->show_wpmm_widget_form($widget_id); ?>
                </div>

            </div>
            <?php
        }

        /**
         * @param $menu_item
         * @param int $widget_key_id
         *
         * Menu item show in widget area
         */
        public function menu_items( $menu_item, $widget_key_id = 0){
            ?>
            <div id="widget-<?php esc_attr_e( $menu_item['ID'] ); ?>" class="widget"  data-item-key-id="<?php esc_attr_e( $widget_key_id ); ?>">
                <div class="widget-top">
                    <div class="widget-title ui-sortable-handle">
                        <h3><?php esc_html_e( $menu_item['title'] ); ?></h3>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * @return bool
         *
         * Save or update a widget
         */
        public function wpmm_save_widget(){
            if(! current_user_can('administrator')) {
                return;
            }
            check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

            $id_base = sanitize_text_field( $_POST['id_base'] );
            $widget_id = sanitize_text_field( $_POST['widget-id'] );

            global $wp_registered_widget_updates;
            $control = $wp_registered_widget_updates[$id_base];
            if ( is_callable( $control['callback'] ) ) {
                call_user_func_array( $control['callback'], $control['params'] );
                return true;
            }
            wp_send_json_success( __('Widget saved success', 'wp-megamenu') );
        }

        /**
         *
         * Delete an item from widget area in wp megamenu
         */

        public function wpmm_delete_widget(){
            if(! current_user_can('administrator')) {
                return;
            }
            check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

            $id_base = sanitize_text_field( $_POST['id_base'] );
            $widget_id = sanitize_text_field($_POST['widget_id']);
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $widget_key_id = (int) sanitize_text_field($_POST['widget_key_id']);

            $row_id = (int) sanitize_text_field($_POST['row_id']);
            $col_id = (int) sanitize_text_field($_POST['col_id']);

            //Remove from sidebar
            $sidebar_widgets = $this->get_sidebar_widgets();
            $new_widgets = array();
            foreach ($sidebar_widgets as $key => $value){
                if ( $widget_id != $value ){
                    $new_widgets[] = $value;
                }
            }
            $this->set_sidebar_widgets($new_widgets);

            //Remove from option widget_{$widget_base_id}
            $get_widget_option = get_option('widget_'.$id_base);
            $key_in_widget_option = (int) str_replace($get_widget_option.'-', '', $widget_id);

            unset($get_widget_option[$key_in_widget_option]);
            update_option('widget_'.$id_base, $get_widget_option);

            //Remove from menu item post meta
            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            if ( ! empty($get_layout['layout'][$row_id]['row'][$col_id]['items'][$widget_key_id])){
                unset($get_layout['layout'][$row_id]['row'][$col_id]['items'][$widget_key_id]);
            }
            update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
        }


        /**
         * Increase or decrease column
         */
        public function wpmm_increase_widget_column(){
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $widget_key_id = (int) sanitize_text_field($_POST['widget_key_id']);
            $cols = (int) sanitize_text_field($_POST['cols']);

            $get_menu_settings = get_post_meta($menu_item_id, 'wpmm_layout', true);

            if ( ! empty($get_menu_settings['items'][$widget_key_id])) {
                $get_menu_settings['items'][$widget_key_id]['options']['cols'] = $cols;
                $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings );
                wp_send_json_success( __('Widget item column update', 'wp-megamenu') );
            }
        }

        /**
         * Reorder items in the widget area
         */
        public function wpmm_reorder_items(){
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $row_id = (int) sanitize_text_field($_POST['row_id']);
            $col_id = (int) sanitize_text_field($_POST['col_id']);

            $item_order = sanitize_text_field($_POST['item_order']);
            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            $item_order_array = explode(',', $item_order);

            //If move one col to another col
            if ( ! empty($_POST['type'])){
                $type = sanitize_text_field($_POST['type']);
                if ($type === 'connect'){
                    $from_row_id = (int) sanitize_text_field($_POST['from_row_id']);
                    $from_col_id = (int) sanitize_text_field($_POST['from_col_id']);
                    $from_item_index = (int) sanitize_text_field($_POST['from_item_index']);

                    $move_item = $get_layout['layout'][$from_row_id]['row'][$from_col_id]['items'][$from_item_index];

                    if (count($get_layout['layout'][$from_row_id]['row'][$from_col_id]['items']) > 1){
                        unset($get_layout['layout'][$from_row_id]['row'][$from_col_id]['items'][$from_item_index]);
                    }else{
                        unset($get_layout['layout'][$from_row_id]['row'][$from_col_id]['items']);
                    }

                    $all_items = (array) $get_layout['layout'][$row_id]['row'][$col_id]['items'];
                    $all_items[]= $move_item;
                    foreach ($all_items as $key => $item){
                        if (empty($item)){
                            unset($all_items[$key]);
                        }
                    }
                    $get_layout['layout'][$row_id]['row'][$col_id]['items'] = $all_items;

                    //remove empty key from array
                    $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
                    wp_send_json_success( __('Widget item column moved', 'wp-megamenu') );
                }
            }else{
                //Else sorting it within own col
                if ( ! empty($get_layout['layout'][$row_id]['row'][$col_id]['items'])) {
                    $item_count = count($get_layout['layout'][$row_id]['row'][$col_id]['items']);
                    //Determine it comes from update, not receive method in sortable
                    if ($item_count == count($item_order_array)){
                        $sorted_item = array();
                        if (count($item_order_array)){
                            for ($i=0; $i<count($item_order_array); $i++){
                                $sorted_item[$item_order_array[$i]] = $get_layout['layout'][$row_id]['row'][$col_id]['items'][$item_order_array[$i]];
                            }
                        }
                        $get_layout['layout'][$row_id]['row'][$col_id]['items'] = $sorted_item;
                        $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
                        wp_send_json_success( __('Widget item column update', 'wp-megamenu') );
                    }
                }
            }
        }

        /**
         * Add widget by drag and drop
         */
        public function wpmm_drag_to_add_widget_item(){
            require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

            $last_index = (int) sanitize_text_field($_POST['last_index']);
            $reorder_item_type = sanitize_text_field($_POST['reorder_item_type']); //outsideWidget
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $row_id = (int) sanitize_text_field($_POST['row_id']);
            $col_id = (int) sanitize_text_field($_POST['col_id']);
            $item_order = sanitize_text_field($_POST['item_order']);
            $widget_base_id = sanitize_text_field($_POST['widget_base_id']);

            //Add widget
            $next_id = next_widget_id_number( $widget_base_id );
            $widget_id = $widget_base_id.'-'.$next_id;

            $this->add_widget_to_wpmm_sidebar($widget_id);

            //get new widget id
            $get_widget_option = get_option('widget_'.$widget_base_id);
            $get_widget_option[$next_id] = array();
            update_option('widget_'.$widget_base_id, $get_widget_option);


            //Settings in item post meta
            $widget_name = $this->wpmm_get_widget_name_by_widget_id($widget_id);
            $widget_class = $this->wpmm_get_widget_class_by_widget_id($widget_id);

            //$get_menu_settings = (array) get_post_meta($menu_item_id, 'wpmm_layout', true);

            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            //Setting item settings in the menu
            $get_layout['layout'][$row_id]['row'][$col_id]['items'][] = array( 'item_type' => 'widget', 'widget_class' => $widget_class, 'widget_name' => $widget_name, 'widget_id' => $widget_id, 'options' => array() );

            update_post_meta($menu_item_id, 'wpmm_layout', $get_layout);
            //update_post_meta($menu_item_id, 'wpmm_layout', $get_menu_settings);

            //Send json success
            wp_send_json_success( array('message' => __('Wiedget added', 'wp-megamenu'), 'widget_id' => $widget_id) );
        }

        /**
         * Reorder row
         */
        public function wpmm_reorder_row(){
            $row_order = sanitize_text_field($_POST['row_order']);
            $row_order = explode(',', $row_order);

            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            $sorted_item = array();
            if (count($row_order)){
                foreach ($row_order as $k => $v){
                    $sorted_item[] = $get_layout['layout'][$v];
                    //print_r($get_layout['layout'][$v]);
                }
            }
            $get_layout['layout'] = $sorted_item;
            $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
            wp_send_json_success( __('Row updated', 'wp-megamenu') );
        }

        /**
         * Delete Row
         */
        public function wpmm_delete_row(){
            if(! current_user_can('administrator')) {
                return;
            }
            check_ajax_referer( 'wpmm_check_security', 'wpmm_nonce' );

            $menu_item_id   = (int) sanitize_text_field($_POST['menu_item_id']);
            $row_id         = (int) sanitize_text_field($_POST['row_id']);
            $get_layout = maybe_unserialize(get_post_meta($menu_item_id, 'wpmm_layout', true));
            if (key_exists($row_id, $get_layout['layout']) ){
                unset($get_layout['layout'][$row_id]);
            }
            $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
            wp_send_json_success( __('Row has been deleted', 'wp-megamenu') );
        }


        /**
         * Reorder col
         */
        public function wpmm_reorder_col(){
            $col_order = sanitize_text_field($_POST['col_order']);
            $col_order = explode(',', $col_order);

            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);
            $row_id = (int) sanitize_text_field($_POST['row_id']);
            $get_layout = get_post_meta($menu_item_id, 'wpmm_layout', true);

            $sorted_item = array();
            if (count($col_order)){
                foreach ($col_order as $k => $v){
                    $sorted_item[] = $get_layout['layout'][$row_id]['row'][$v];
                }
            }
            $get_layout['layout'][$row_id]['row'] = $sorted_item;
            $update = update_post_meta($menu_item_id, 'wpmm_layout', $get_layout );
            wp_send_json_success( __('Row updated', 'wp-megamenu') );
        }

        /**
         * Show Widget in edit mode.
         */
        public function wpmm_edit_widget(){
            $widget_id = sanitize_text_field($_POST['widget_id']);
            $widget_id = preg_replace('/widget-/', '', $widget_id, 1);

            $this->show_wpmm_widget_form( $widget_id );
            die();
        }

    }

    wp_megamenu_widgets::init();

    if ( ! function_exists('wp_megamenu_widgets')){
        function wp_megamenu_widgets(){
            return new wp_megamenu_widgets();
        }
    }
}

