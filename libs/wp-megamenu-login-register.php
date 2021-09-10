<?php
/* ------------------------------------------ *
 *              Login Action
 * ------------------------------------------ */
add_action( 'wp_ajax_nopriv_ajaxlogin', 'wpmm_ajax_login' );
function wpmm_ajax_login() {
    check_ajax_referer( 'ajax-login-nonce', 'security' );
    $info = array();
    $info['user_login'] = sanitize_text_field( $_POST['username'] );
    $info['user_password'] = sanitize_text_field( $_POST['password'] );
    $info['remember'] = sanitize_text_field( $_POST['remember'] );

    $user_signon = wp_signon( $info, false );

    if ( is_wp_error( $user_signon ) ) {
        echo json_encode( array( 'loggedin' => false, 'message' =>__( 'Wrong username or password.', 'wp-megamenu' ) ) );
    } else {
        echo json_encode( array( 'loggedin' => true, 'message' =>__( 'Login successful, redirecting...', 'wp-megamenu' ) ) );
    }
    die();
}


/* ------------------------------------------ *
*              Registration Action
* ------------------------------------------ */
add_action( 'wp_ajax_nopriv_ajaxregister', 'wpmm_ajax_register_new_user' );
function wpmm_ajax_register_new_user() {
    check_ajax_referer( 'ajax-register-nonce', 'security' );
    if ( ! $_POST['username'] ) {
        echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Username field is empty.', 'wp-megamenu' ) ) );
        die();
    } elseif ( ! $_POST['email'] ) {
        echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Email field is empty.', 'wp-megamenu' ) ) );
        die();
    } elseif ( ! $_POST['password'] ) {
        echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Password field is empty.', 'wp-megamenu' ) ) );
        die();
    } else {
        if ( username_exists( $_POST['username'] ) ) {
            echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Username already exits.', 'wp-megamenu' ) ) );
            die();
        } elseif ( strlen( $_POST['password'] ) <= 6 ) {
            echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Password must 6 charecter or more.', 'wp-megamenu' ) ) );
            die();
        } elseif ( ! is_email( $_POST['email'] ) ) {
            echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Email address is not correct.', 'wp-megamenu' ) ) );
            die();
        } elseif ( email_exists( $_POST['email'] ) ) {
            echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong!!! Email user already exits in this site.', 'wp-megamenu') ) );
            die();
        } else {
            $user_input = array(
                'user_login'    =>  sanitize_text_field( $_POST['username'] ),
                'user_email'    =>  sanitize_email( $_POST['email'] ),
                'user_pass'     =>  sanitize_text_field( $_POST['password'] )
            );
            $user_id = wp_insert_user( $user_input );
            if ( ! is_wp_error( $user_id ) ) {
                echo json_encode( array( 'loggedin' => true, 'message' => __( 'Registration successful you can login now.', 'wp-megamenu') ) );
                die();
            } else {
                echo json_encode(array( 'loggedin' => false, 'message' => __( 'Wrong username or password.', 'wp-megamenu' ) ) );
                die();
            }
        }
    }
}