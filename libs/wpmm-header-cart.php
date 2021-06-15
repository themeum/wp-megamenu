<?php 
/* -------------------------------------------
*   show menu cart
* -------------------------------------------- */
if ( ! function_exists( 'wpmm_header_cart' ) ) {
    function wpmm_header_cart() {
        ob_start();
        $output = ''; 
        $output .= '<div id="site-header-cart" class="site-header-cart menu">';
            $output .= '<span class="cart-icon">';
                // $output .= '<img src="'.plugin_dir_url( __FILE__ ).'../assets/images/cart-icon.svg" alt="">';
                $output .= '<i class="icofont-cart-alt"></i>';
                $output .= '<a class="cart-contents" data-toggle="modal" href="#modal-cart">';
                $output .= '<span class="count">'.wp_kses_data( sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'wp-megamenu' ), WC()->cart->get_cart_contents_count() ) ).'</span>';
                $output .= '</a>';
           $output .= '</span>';
        $output .= '</div>';
        $output .= ob_get_clean();
        return $output;
    }
}

# Header Cart.
if ( ! function_exists( 'wpmm_header_cart_widgets' ) ) {
    function wpmm_header_cart_widgets() {
        $output = '';
        ob_start();
        $output .= the_widget( 'WC_Widget_Cart', 'title=' );
        $output .= ob_get_clean();
        return $output;

    }
}

# Cart Fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'wpmm_cart_link_fragment' );
if ( ! function_exists( 'wpmm_cart_link_fragment' ) ) {
    function wpmm_cart_link_fragment( $fragments ) {
        global $woocommerce;
        ob_start(); ?>

        <a class="cart-contents" data-toggle="modal" href="#modal-cart" title="<?php esc_attr_e( 'View your shopping cart', 'wp-megamenu' ); ?>">
            <span class="count">
                <?php echo wp_kses_data( sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'wp-megamenu' ), WC()->cart->get_cart_contents_count() ) );?>  
            </span>
        </a>
          
        <?php $fragments['a.cart-contents'] = ob_get_clean();
        return $fragments;
    }
}



