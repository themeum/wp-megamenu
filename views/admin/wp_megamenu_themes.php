<?php

//section=add_theme
if ( ! empty($_GET['section'])){
    $section = sanitize_text_field($_GET['section']);
    if ($section === 'add_theme'){
        include WPMM_DIR.'views/admin/add_update_new_theme.php';
    }
}else{
    include WPMM_DIR.'views/admin/themes.php';
}