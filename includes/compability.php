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
    $style .= '</style>';
    echo $style;
}