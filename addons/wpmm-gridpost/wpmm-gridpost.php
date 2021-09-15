<?php
add_action('widgets_init','register_wpmm_grid_post_posts_widget');
function register_wpmm_grid_post_posts_widget(){
    register_widget('wpmm_grid_posts_widget');
}

class wpmm_grid_posts_widget extends WP_Widget{

    function __construct(){
        parent::__construct( 'wpmm_grid_posts_widget','WPMM Grid Posts',array('description' => 'Grid Posts widget to display in WP Mega Menu'));
    }

    /*-------------------------------------------------------
    *				Front-end display of widget
    *-------------------------------------------------------- */
    function widget($args, $instance)
    {
        extract($args);
        $title = null;

        if ( ! empty($instance['title'])){
            $title = apply_filters('widget_title', $instance['title'] );
        }

        if ( ! empty($instance['count']) ) {
            $count 	= $instance['count'];
        } else {
            $count 	= 4;
        }
        if ( ! empty($instance['no_column']) ) {
            $no_column 	= $instance['no_column'];
        } else {
            $no_column 	= 'col4';
        }

        echo $args['before_widget'];

        $output = '';

        if ( $title )
            echo $args['before_title'] . esc_attr($title) . $args['after_title'];

        global $post;

        if ( ! empty( $instance['order_by']) && $instance['order_by'] == 'popular') {
            $args = array(
                'posts_per_page' 	=> esc_attr($count),
                'meta_key' 			=> 'wpmm_postgrid_views',
                'orderby' 			=> 'meta_value_num',
                'post_status' 		=> 'publish',
                'order' 			=> 'DESC'
            );
        } else {
            $args = array(
                'posts_per_page' 	=> esc_attr($count),
                'post_status' 		=> 'publish',
                'order' 			=> 'DESC',
                'paged' 			=> 1
            );
        }

        if( ! empty($instance['show_cat']) && $instance['show_cat'] == 'on' ){

            if( !empty($instance['category']) ){
                $output .='<div class="wpmm-vertical-tabs">';
                $output .='<div class="wpmm-vertical-tabs-nav">';
                $output .='<ul class="wpmm-tab-btns">';
                $i = 1;
                foreach ( $instance['category'] as $value ) {
                    $catName = __('All Post','wp-megamenu');
                    if( $value != 'allpost' ){
                        $catObj = get_category_by_slug( $value );
                        if(isset($catObj->name)){
                            $catName = $catObj->name;
                        }
                    }
                    if( $value=='allpost' ){
                        $output .='<li class="active"><a href="javascript:void(0)">'.esc_html( $catName ).'</a></li>';
                    }else{
                        $output .='<li class=""><a href="'. get_category_link($catObj->term_id) .'">'.esc_html( $catName ).'</a></li>';
                    }
                    $i++;
                }
                $output .='</ul>';
                $output .='</div>';
                $output .='<div class="wpmm-vertical-tabs-content">';
                $output .='<div class="wpmm-tab-content">';
                $i = 1;
                foreach ( $instance['category'] as $value ) {
                    if( $value ){
                        $cat_data = array();
                        $cat_data['relation'] = 'AND';
                        if( 'allpost' != $value ){
                            $cat_data[] = array(
                                'taxonomy' 	=> 'category',
                                'field' 	=> 'slug',
                                'terms' 	=> $value
                            );
                        }
                        $args['tax_query'] = $cat_data;
                    }
                    $data = new WP_Query( $args );
                    $output .='<div class="wpmm-tab-pane '.(($i==1)?"active":"").'">';
                    if( $data->have_posts()){

                        $output .='<div class="wpmm-grid-post-addons wpmm-grid-post-row">';
                        while ( $data->have_posts() ) {
                            $data->the_post();
                            $output .='<div class="wpmm-grid-post '.esc_attr($no_column).'">';
                            $output .='<div class="wpmm-grid-post-content">';

                            if ( has_post_thumbnail() ) {
                                $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large-thumb' );
                                $image ='style="background: url('.esc_url($img[0]).') no-repeat;background-size: cover;"';

                            }else {
                                $image ='style="background: #333;"';
                            }

                            $output .='<div class="wpmm-grid-post-img-wrap">';

	                        if (has_post_thumbnail(get_the_ID())){
		                        $output .='<a href="'.get_permalink(get_the_ID()).'">';
		                        $output .='<div class="wpmm-grid-post-img" '.$image.'>';
		                        $output .= '</div>';
		                        $output .= '</a>';
	                        }

                            if( $instance['show_category'] == 'on' ){
                                $output .= '<span class="post-in-image">'.get_the_category_list(' ').'</span>';
                            }
                            $output .= '</div>';//wpmm-grid-post-img-wrap
                            $output .= '<h4 class="grid-post-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h4>';

                            $output .= '</div>'; //.wpmm-grid-post-content
                            $output .= '</div>'; //.wpmm-grid-post
                        }
                        wp_reset_postdata();
                        $output .= '</div>'; //wpmm-grid-post-addons

                        if( $instance['show_nav'] == 'on' ){
                            $output .= '<span data-count="'.esc_attr($count).'"  data-showcat="'.esc_attr( $instance['show_category'] ).'" data-type="post" data-category="'.esc_attr( $value ).'" data-current="1" data-oderby="'.esc_attr( $instance["order_by"] ).'" data-column="'.esc_attr( $no_column ).'"  data-total="'.esc_attr( $data->max_num_pages ).'" class="dashicons dashicons-arrow-left-alt2 wpmm-left wpmm-gridcontrol-left disablebtn"></span>';
                            $var = ($data->max_num_pages == 1)? 'disablebtn' : '';
                            $output .= '<span data-count="'.esc_attr($count).'"  data-showcat="'.esc_attr( $instance['show_category'] ).'" data-type="post" data-category="'.esc_attr( $value ).'"  data-current="1" data-oderby="'.esc_attr( $instance["order_by"] ).'" data-column="'.esc_attr( $no_column ).'"  data-total="'.esc_attr( $data->max_num_pages ).'" class="dashicons dashicons-arrow-right-alt2 wpmm-right wpmm-gridcontrol-right '.esc_attr( $var ).'"></span>';
                        }
                    }
                    $output .='</div>';
                    $i++;
                }


                $output .='</div>';
                $output .='</div>';
                $output .='</div>';
            }

        } else {
            if( ! empty($instance['category']) ){
                $cat_data = array();
                $cat_data['relation'] = 'AND';
                if( !in_array( 'allpost', $instance['category'] ) ){
                    $cat_data[] = array(
                        'taxonomy' 	=> 'category',
                        'field' 	=> 'slug',
                        'terms' 	=> $instance['category']
                    );
                }
                $args['tax_query'] = $cat_data;
            }
            $data = new WP_Query( $args );

            if( $data->have_posts()){
                $output .='<div class="wpmm-grid-post-addons wpmm-grid-post-row">';
                while ( $data->have_posts() ) {
                    $data->the_post();
                    $output .='<div class="wpmm-grid-post '.esc_attr($no_column).'">';
                    $output .='<div class="wpmm-grid-post-content">';

                    if ( has_post_thumbnail() ) {
                        $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large-thumb' );
                        $image ='style="background: url('.esc_url($img[0]).') no-repeat;background-size: cover;"';

                    }else {
                        $image ='style="background: #333;"';
                    }

                    $output .='<div class="wpmm-grid-post-img-wrap">';

                    if (has_post_thumbnail(get_the_ID())){
		                $output .='<a href="'.get_permalink(get_the_ID()).'">';
		                $output .='<div class="wpmm-grid-post-img" '.$image.'>';
		                $output .= '</div>';
		                $output .= '</a>';
	                }

                    if( ! empty($instance['show_category'] ) && $instance['show_category'] == 'on' ){
                        $output .= '<span class="post-in-image">'.get_the_category_list(' ').'</span>';
                    }
                    $output .= '</div>';//wpmm-grid-post-img-wrap

                    $output .= '<h4 class="grid-post-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h4>';
                    $output .= '</div>'; //.wpmm-grid-post-content
                    $output .= '</div>'; //.wpmm-grid-post
                }
                wp_reset_postdata();
                $output .= '</div>'; //wpmm-grid-post-addons

                if( ! empty($instance['show_nav']) && $instance['show_nav'] == 'on' ){
                    $data_category = '';
                    if ( ! empty($instance['category'])){
                        $data_category = implode(',',$instance['category']);
                    }
                    $output .= '<span data-count="'.esc_attr($count).'" data-showcat="'.esc_attr( $instance['show_category'] ).'" data-type="post" data-category="'.esc_attr( $data_category ).'" data-current="1" data-oderby="'.esc_attr( $instance["order_by"] ).'" data-column="'.esc_attr( $no_column ).'"  data-total="'.esc_attr( $data->max_num_pages ).'" class="dashicons dashicons-arrow-left-alt2 wpmm-left wpmm-gridcontrol-left disablebtn"></span>';
                    $var = ($data->max_num_pages == 1)? 'disablebtn' : '';
                    $output .= '<span data-count="'.esc_attr($count).'" data-showcat="'.esc_attr( $instance['show_category'] ).'" data-type="post" data-category="'.esc_attr( $data_category ).'"  data-current="1" data-oderby="'.esc_attr( $instance["order_by"] ).'" data-column="'.esc_attr( $no_column ).'"  data-total="'.esc_attr( $data->max_num_pages ).'" class="dashicons dashicons-arrow-right-alt2 wpmm-right wpmm-gridcontrol-right '.esc_attr( $var ).'"></span>';
                }
            }
        }

        echo $output;
        echo ! empty($args['after_widget']) ? $args['after_widget'] : '';
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] 			= strip_tags( $new_instance['title'] );
        $instance['no_column'] 		= strip_tags( $new_instance['no_column'] );
        $instance['order_by'] 		= strip_tags( $new_instance['order_by'] );
        $instance['count'] 			= strip_tags( $new_instance['count'] );
        $instance['category'] 		=  $new_instance['category'];
        $instance['show_cat'] 		= strip_tags( $new_instance['show_cat'] );
        $instance['show_nav'] 		= strip_tags( $new_instance['show_nav'] );
        $instance['show_category'] 	= strip_tags( $new_instance['show_category'] );

        return $instance;
    }

    function form($instance)
    {
        $defaults = array(
            'title' 		=> 'Latest Posts',
            'no_column' 	=> 'col4',
            'order_by' 		=> 'latest',
            'count' 		=> 4,
            'category'		=> 'allpost',
            'show_cat'		=> false,
            'show_nav'		=> false,
            'show_category'	=> true
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Widget Title', 'wp-megamenu'); ?></label>
            <input id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" value="<?php esc_attr_e( $instance['title'] ); ?>" style="width:100%;" />
        </p>

        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'order_by' ) ); ?>"><?php esc_html_e('Ordered By', 'wp-megamenu'); ?></label>
            <?php
            $options = array(
                'popular' 	=> 'Popular',
                'latest' 	=> 'Latest',
            );
            if(isset($instance['order_by'])) $order_by = $instance['order_by'];
            ?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'order_by' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'order_by' ) ); ?>">
                <?php
                $op = '<option value="%s"%s>%s</option>';
                foreach ($options as $key=>$value ) {
                    if ($order_by === $key) {
                        printf($op, $key, ' selected="selected"', $value);
                    } else {
                        printf($op, $key, '', $value);
                    }
                }
                ?>
            </select>
        </p>


        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e('Select Category', 'wp-megamenu'); ?></label>
            <?php
            $options = array();
            $options['allpost'] = 'All Category';
            $query1 = get_terms( 'category' );
            if( $query1 ){
                foreach ( $query1 as $post ) {
                    $options[ $post->slug ] = $post->name;
                }
            }
            if(!empty($instance['category'])){
                $category = (array) $instance['category'];
            } else {
                $category = array( 'allpost' );
            }
            ?>
            <select multiple class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'category' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'category' ) ); ?>[]">
                <?php
                $op = '<option value="%s"%s>%s</option>';
                foreach ($options as $key=>$value ) {
                    if (in_array($key,$category)) {
                        printf($op, $key, ' selected="selected"', $value);
                    } else {
                        printf($op, $key, '', $value);
                    }
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'no_column' ) ); ?>"><?php esc_html_e('Number of Column', 'wp-megamenu'); ?></label>
            <?php
            $options = array(
                'col1' 	=> 'Column 1',
                'col2' 	=> 'Column 2',
                'col3'	=> 'Column 3',
                'col4'	=> 'Column 4',
                'col5'	=> 'Column 5',
            );
            if(isset($instance['no_column'])) $no_column = $instance['no_column'];
            ?>
            <select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'no_column' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'no_column' ) ); ?>">
                <?php
                $op = '<option value="%s"%s>%s</option>';

                foreach ($options as $key=>$value ) {
                    if ($no_column === $key) {
                        printf($op, $key, ' selected="selected"', $value);
                    } else {
                        printf($op, $key, '', $value);
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php esc_attr_e( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e('Post Count (Per page)', 'wp-megamenu'); ?></label>
            <input id="<?php esc_attr_e( $this->get_field_id( 'count' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'count' ) ); ?>" value="<?php esc_attr_e( $instance['count'] ); ?>" style="width:100%;" />
        </p>

        <?php $show_category = isset( $instance['show_category'] ) ? (bool) $instance['show_category'] : false; ?>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_category ); ?> id="<?php esc_attr_e( $this->get_field_id( 'show_category' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_category' ) ); ?>" />
            <label for="<?php esc_attr_e( $this->get_field_id( 'show_category' ) ); ?>"><?php esc_html_e( 'Show Category on the Post?' ); ?></label>
        </p>

        <?php $show_nav = isset( $instance['show_nav'] ) ? (bool) $instance['show_nav'] : false; ?>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_nav ); ?> id="<?php esc_attr_e( $this->get_field_id( 'show_nav' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_nav' ) ); ?>" />
            <label for="<?php esc_attr_e( $this->get_field_id( 'show_nav' ) ); ?>"><?php esc_html_e( 'Show Navigation on the Post?' ); ?></label>
        </p>

        <?php $show_cat = isset( $instance['show_cat'] ) ? (bool) $instance['show_cat'] : false; ?>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_cat ); ?> id="<?php esc_attr_e( $this->get_field_id( 'show_cat' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'show_cat' ) ); ?>" />
            <label for="<?php esc_attr_e( $this->get_field_id( 'show_cat' ) ); ?>"><?php esc_html_e( 'Show Left Category on the Widget?' ); ?></label>
        </p>

        <?php
    }
}


/*-----------------------------------------------------
 * 				script load
*----------------------------------------------------*/

function wpmm_postgrid_scripts() {
    wp_enqueue_style( 'postgrid_css', WPMM_URL .'addons/wpmm-gridpost/wpmm-gridpost.css', WPMM_VER, true );
    wp_enqueue_script( 'postgrid-style', WPMM_URL .'addons/wpmm-gridpost/wpmm-gridpost.js', WPMM_VER, true );
    wp_localize_script( 'postgrid-style', 'postgrid_ajax_load', array( 
        'ajax_url'          => admin_url( 'admin-ajax.php' ),
        'redirecturl'       => home_url('/'),
    ) );
}
add_action( 'wp_enqueue_scripts', 'wpmm_postgrid_scripts' );



/*-----------------------------------------------------
* 				popular post
*------------------------------------------------------ */

function wpmm_set_postgrid_views($postID) {
    $count_key = 'wpmm_postgrid_views';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function wpmm_track_postgrid_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    wpmm_set_postgrid_views($post_id);
}
add_action( 'wp_head', 'wpmm_track_postgrid_views');


/*-----------------------------------------------------
 * 				Load More
*----------------------------------------------------*/
add_action( 'wp_ajax_nopriv_gridpost_load_more_posts', 'gridpost_load_more_posts_cb' );
add_action( 'wp_ajax_gridpost_load_more_posts', 'gridpost_load_more_posts_cb' );
function gridpost_load_more_posts_cb(){

    $oderby 	= sanitize_text_field( $_POST['oderby'] );
    $column 	= sanitize_text_field( $_POST['column'] );
    $current 	= sanitize_text_field( $_POST['current'] );
    $type 		= sanitize_text_field( $_POST['type'] );
    $showcat 	= sanitize_text_field( $_POST['showcat'] );
    $category 	= sanitize_text_field( $_POST['category'] );
    $count 	    = sanitize_text_field( $_POST['count'] );

    if ( strpos($category, ',') !== false ) {
        $category 	= explode( ',',$category );
    }else{
        $category 	= array( $category );
    }

    global $post;
    $output = '';

    if( $type == 'post' ){ 
        $post_type = 'post'; 
    }elseif ($type == 'woocommerce') { 
        $post_type = 'product';
    }

    if ( $oderby == 'popular') {
        $args = array(
            'post_type'			=> $post_type,
            'posts_per_page' 	=> esc_attr($count),
            'meta_key' 			=> 'wpmm_postgrid_views',
            'orderby' 			=> 'meta_value_num',
            'order' 			=> 'DESC',
            'post_status' 		=> 'publish',
            'paged' 			=> $current
        );
    } else {
        $args = array(
            'post_type'			=> $post_type,
            'posts_per_page' 	=> esc_attr($count),
            'order' 			=> 'DESC',
            'post_status' 		=> 'publish',
            'paged' 			=> $current
        );
    }

    if( $category ){
        if( $type == 'post' ){ $cat_slug = 'category'; }
        if( $type == 'woocommerce' ){ $cat_slug = 'product_cat'; }

        $cat_data = array();
        $cat_data['relation'] = 'AND';
        if( !in_array( 'allpost', $category ) ){
            $cat_data[] = array(
                'taxonomy' 	=> $cat_slug,
                'field' 	=> 'slug',
                'terms' 	=> $category
            );
        }
        $args['tax_query'] = $cat_data;
    }


    $data = new WP_Query( $args );

    if( $data->have_posts()){
        while ( $data->have_posts() ) {
            $data->the_post();
            $output .='<div class="wpmm-grid-post '.esc_attr($column).'">';
            $output .='<div class="wpmm-grid-post-content">';
            if ( has_post_thumbnail() ) {
                $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large-thumb' );
                $image ='style="background: url('.esc_url($img[0]).') no-repeat;background-size: cover;"';

            }else {
                $image ='style="background: #333;"';
            }

            $output .='<div class="wpmm-grid-post-img-wrap">';

	        if (has_post_thumbnail(get_the_ID())){
		        $output .='<a href="'.get_permalink(get_the_ID()).'">';
		        $output .='<div class="wpmm-grid-post-img" '.$image.'>';
		        $output .= '</div>';
		        $output .= '</a>';
	        }

            $output .= '<span class="post-in-image">';
            if( $type == 'woocommerce' ){
                $var = get_the_term_list( get_the_ID(), 'product_cat' );
                if( !is_wp_error($var) && $showcat == 'on' ){
                    $output .= $var;
                }
            } else {
                if( $showcat == 'on' ){
                    $output .= get_the_category_list(' ');
                }
            }
            $output .= '</span>';

            $output .= '</div>';//wpmm-grid-post-img-wrap
            $output .= '<h4 class="grid-post-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h4>';
            //Regular And Sales Price
            if( $type == 'woocommerce' ){
                $output .= '<span class="post-in-price">';
                $price 	= get_post_meta( get_the_ID(), '_regular_price', true);
                if( $price ){
                    $output .= '<span class="post-regular-price">'.$price.get_option('woocommerce_currency').'</span>';
                }
                $sale 	= get_post_meta( get_the_ID(), '_sale_price', true);
                if( $sale ){
                    $output .= '<span class="post-sales-price">'.$sale.get_option('woocommerce_currency').'</span>';
                }
                $output .= '</span>';
            }
            $output .= '</div>'; //wpmm-grid-post-content
            $output .= '</div>'; //wpmm-grid-post
        }
    }

    die( $output );
}

# If Woocommerce is active.
if ( class_exists( 'WooCommerce' ) ) {
    require_once plugin_dir_path( __FILE__ ).'wpmm-grid-woocommerce.php';
}
