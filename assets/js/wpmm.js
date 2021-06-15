(function($) {

    // Search 
    $(".search-open-icon").on('click',function(e){
        e.preventDefault();
        $(".wpmm-search-input-wrap, .top-search-overlay").fadeIn(200);
        $(this).hide();
        $('.search-close-icon').show().css('display','inline-block');
    });
    $(".search-close-icon, .top-search-overlay").on('click',function(e){
        e.preventDefault();
        $(".wpmm-search-input-wrap, .top-search-overlay").fadeOut(200);
        $('.search-close-icon').hide();
        $('.search-open-icon').show();
    });

   //Sticky Nav
    jQuery(window).on('scroll', function(){'use strict';
        if ( jQuery(window).scrollTop() > 100 ) {
            jQuery('.wpmm-sticky').addClass('wpmm-sticky-wrap');
        } else {
            jQuery('.wpmm-sticky').removeClass('wpmm-sticky-wrap');
        }
    });

    // Mobile Menu Dsiable
    var wpmm_disable_mobile = wpmm_object.wpmm_disable_mobile;

    $(document).on('click', '.wpmm_mobile_menu_btn', function(e){
        e.preventDefault();
        $(this).toggleClass('menu-active').closest('.wpmm-mobile-menu').find('.wp-megamenu').slideToggle();
    });
    function wpmmMobileMenuActive() {
        var current_width = parseInt($(window).width());
        var wpmm_responsive_breakpoint = parseInt(wpmm_object.wpmm_responsive_breakpoint.replace('px', ''));

        if ( wpmm_disable_mobile == 'true' ) {
            if (current_width < (wpmm_responsive_breakpoint + 1) ){
                $('.wp-megamenu-wrap').addClass('wpmm-hide-mobile-menu');
            }else{
                $('.wp-megamenu-wrap').removeClass('wpmm-hide-mobile-menu');
            }
        }else{
            //Default Break Point 768
            if (current_width < (wpmm_responsive_breakpoint + 1) ){
                $('.wp-megamenu-wrap').addClass('wpmm-mobile-menu');

                $('ul.wp-megamenu li a b, ul.wp-megamenu li a > svg.svg-inline--fa').off('click').on('click',function(e){
                    if ($(this).closest('li').hasClass('wpmm_dropdown_menu') || $(this).closest('li').hasClass('wpmm_mega_menu') || $(this).closest('li').hasClass('menu-item-has-children') ){
                        e.preventDefault();
                        if ($(this).closest('li').find('ul').length) {
                            if ($(this).closest('li').hasClass('wpmm_dropdown_menu')) {
                                $(this).closest('li').find('ul.wp-megamenu-sub-menu').first().toggle();
                            } else {
                                $(this).closest('li').find('ul').toggle();
                                $(this).closest('li').find(".wpmm-row ul").show();
                            }
                        }
                    }
                });
            }else{
                $('.wp-megamenu-wrap').removeClass('wpmm-mobile-menu');
            }
        }
    }

    wpmmMobileMenuActive();
    $(window).on('resize load scroll', wpmmMobileMenuActive);

    //Row Stress
    var megamenu_strees_row_content = function () {
        var fullWidth = $(window).width();
        $('.wpmm-strees-row-and-content-container').each(function () {
            var base_width = $(this).find('>.wpmm-row-content-strees-extra').width();
            $(this).css({
                'width': fullWidth,
                'position' : 'absolute'
            });
            $(this).offset({
                left: 0
            })
        });
    };
    megamenu_strees_row_content();
    $(window).on('load resize scroll', megamenu_strees_row_content);



    /* --------------------------------------
    *       Login popup
    *  -------------------------------------- */
    $(".show").on("click", function(){
        $(".wpmm-mask").addClass("active");
    });

    function closeModal(){
        $(".wpmm-mask, .wpmm-mask1").removeClass("active");
    }
    $(".close, .wpmm-mask, wpmm-mask1").on("click", function(){
        closeModal();
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            closeModal();
        }
    });



    /* --------------------------------------
    *       Perform AJAX Login
    *  -------------------------------------- */
    $('form#login').on('submit', function(e){ 'use strict';
        $('form#login p.status').show().text(ajax_objects.loadingmessage);
        var checked = false;
        if( $('form#login #rememberlogin').is(':checked') ){ checked = true; }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_objects.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #usernamelogin').val(),
                'password': $('form#login #passwordlogin').val(),
                'remember': checked,
                'security': $('form#login #securitylogin').val() },
            success: function(data){
                console.log( 'working!!!' );
                if (data.loggedin == true){
                    $('form#login div.login-error').removeClass('alert-danger').addClass('alert-success');
                    $('form#login div.login-error').text(data.message);
                    document.location.href = ajax_objects.redirecturl;
                }else{
                    $('form#login div.login-error').removeClass('alert-success').addClass('alert-danger');
                    $('form#login div.login-error').text(data.message);
                }
                if($('form#login .login-error').text() == ''){
                    $('form#login div.login-error').hide();
                }else{
                    $('form#login div.login-error').show();
                }
            }
        });
        e.preventDefault();
    });
    if($('form#login .login-error').text() == ''){
        $('form#login div.login-error').hide();
    }else{
        $('form#login div.login-error').show();
    }


    /* --------------------------------------
    *       Register New User
    *  -------------------------------------- */
    $('form#register').on('submit', function(e){ 'use strict';
        $('form#register p.status').show().text(ajax_objects.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_objects.ajaxurl,
            data: {
                'action':   'ajaxregister', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#register #username').val(),
                'email':    $('form#register #email').val(),
                'password': $('form#register #password').val(),
                'security': $('form#register #security').val() },
            success: function(data){

                if (data.loggedin == true){
                    $('form#register div.login-error').removeClass('alert-danger').addClass('alert-success');
                    $('form#register div.login-error').text(data.message);
                    $('form#register')[0].reset();
                }else{
                    $('form#register div.login-error').removeClass('alert-success').addClass('alert-danger');
                    $('form#register div.login-error').text(data.message);
                }
                if($('form#register .login-error').text() == ''){
                    $('form#register div.login-error').hide();
                }else{
                    $('form#register div.login-error').show();
                }
            }
        });
        e.preventDefault();
    });

    if($('form#register .login-error').text() == ''){
        $('form#register div.login-error').hide();
    }else{
        $('form#register div.login-error').show();
    }
})(jQuery);


// Login query Selector On Click.
var el = document.querySelector('.img__btn');
if(el){
    el.addEventListener('click', function() {
        document.querySelector('.cont').classList.toggle('s--signup');
    });
}

