<?php
/**
 * Twenty Sixteen Theme
 */
add_action('wp_head', 'wpmm_css_compatibility');
function wpmm_css_compatibility(){
    $style = '<style type="text/css">';

    $current_theme = wp_get_theme();
    $theme_name = $current_theme->get_stylesheet();
    if ($theme_name === 'twentysixteen'){
        $style .= '.site-header-menu{';
        $style .= 'display: block;';
        $style .= '}';
    }

    if($theme_name === 'storefront'){
        $style .= "
            .main-navigation ul ul, .secondary-navigation ul ul{float: none}
            .main-navigation ul.menu ul li a, .main-navigation ul.nav-menu ul li a{padding: 0}
            .handheld-navigation, .main-navigation div.menu>ul:not(.nav-menu), .nav-menu{overflow: visible}
            .wp-megamenu-wrap .wpmm-nav-wrap::after, .wp-megamenu-wrap .wpmm-nav-wrap::before{content: '';display: table;clear: both;}
            
        ";
    }

    $style .= '</style>';
    echo wpmm_sanitize_inline_css_output( $style );
}