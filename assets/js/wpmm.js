(function($) {

    $('.wpmm-social-link-search').on('click',function(e){
        e.preventDefault(); 
        $('.wpmm-search-form').toggle();
    });

   //Sticky Nav
    jQuery(window).on('scroll', function(){'use strict';
        if ( jQuery(window).scrollTop() > 100 ) {
            jQuery('.wpmm-sticky').addClass('wpmm-sticky-wrap');
        } else {
            jQuery('.wpmm-sticky').removeClass('wpmm-sticky-wrap');
        }
    });

    //Mobile Menu Dsiable
    var wpmm_disable_mobile = wpmm_object.wpmm_disable_mobile;

    $(document).on('click', '.wpmm_mobile_menu_btn', function(e){
        e.preventDefault();
        $(this).closest('.wpmm-mobile-menu').find('.wp-megamenu').slideToggle();
    });
    function wpmmMobileMenuActive() {
        var current_width = parseInt($(window).width());
        var wpmm_responsive_breakpoint = parseInt(wpmm_object.wpmm_responsive_breakpoint.replace('px', ''));

        if ( wpmm_disable_mobile == 'true' ) {
            //Default Break Point 768
            if (current_width < (wpmm_responsive_breakpoint + 1) ){
                $('.wp-megamenu-wrap').addClass('wpmm-hide-mobile-menu');
            }else{
                $('.wp-megamenu-wrap').removeClass('wpmm-hide-mobile-menu');
            }
        }else{
            //Default Break Point 768
            if (current_width < (wpmm_responsive_breakpoint + 1) ){
                $('.wp-megamenu-wrap').addClass('wpmm-mobile-menu');

                $('ul.wp-megamenu li a b').off('click').on('click',function(e){
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
    $(window).on('resize', wpmmMobileMenuActive);

    //Row Stress
    var megamenu_strees_row_content = function () {
        var fullWidth = $(window).width();
        $('.wpmm-strees-row-and-content-container').each(function () {
            var base_width = $(this).find('>.wpmm-row-content-strees-extra').width();
            var left = (fullWidth - base_width) / 2;
            $(this).css({'width': fullWidth, 'left': '-'+left+'px', 'position' : 'absolute'});
        });
    };
    megamenu_strees_row_content();
    $(window).on('resize', megamenu_strees_row_content);
    
})(jQuery);
