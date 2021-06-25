(function($) {
    //Admin settings Tabs
    if (jQuery().tabs){
        $('#wpmm-tabs').tabs();
    }
    if (jQuery().wpColorPicker) {
        $('.wpmmColorPicker').wpColorPicker();
    }
    if (jQuery().slider) {
        $("#slider").slider();
    }
    if (jQuery().select2) {
        $('.select2').select2();
    }
    $('#menu-to-edit li.menu-item').each(function() {
        var menu_item = $(this);
        var button = $("<span>").addClass("wp_megamenu_lauch").html('WP Mega Menu');
        $('.item-title', menu_item).append(button);
    });
    /**
     * Saving indicator
     * @param method
     */
    function wpmm_saving_indicator(method){
        if (method == 'show'){
            $('.wpmm-saving-indecator').show();
        }else if(method =='hide'){
            $('.wpmm-saving-indecator').fadeOut();
        }
    }
    /**
     * Open item settings from bottom popup
     */
    $('.wp_megamenu_lauch').click(function(e){
        e.preventDefault();
        var menu_item = $(this).closest('li.menu-item');
        var menu_id = $('input#menu').val();
        var title = menu_item.find('.menu-item-title').text();
        var id = parseInt(menu_item.attr('id').match(/[0-9]+/)[0], 10);
        var depth = menu_item.attr('class').match(/\menu-item-depth-(\d+)\b/)[1];

        var wpmm_item_settings_wrap = $('.wp-megamenu-item-settins-wrap');
        //Show overlay
        $('#wpmmSettingOverlay').show();
        ajax_request_load_menu_item_settings(id, depth, menu_id);
        //Set this item id to settings wrap
        wpmm_item_settings_wrap.removeAttr('data-id');
        wpmm_item_settings_wrap.attr('data-id', id);
        wpmm_item_settings_wrap.show();

        //Press escape key to close modal

        document.addEventListener('keydown', function(event) {
            if (event.keyCode === 27){
                $('.wp-megamenu-item-settins-wrap').hide();
                $('#wpmmSettingOverlay').hide();
            }
        });

    });


    // Get all menu item with class
    var MenuChild  = $('#menu-to-edit').children('li.menu-item');
        menu_id = $('input#menu').val();
    MenuChild.each(function(){
        var id = parseInt($(this).attr('id').match(/[0-9]+/)[0], 10);
        var depth = $(this).attr('class').match(/\menu-item-depth-(\d+)\b/)[1];
        ajax_request_load_menu_item_settings(id, depth, menu_id);
    });

    function ajax_request_load_menu_item_settings(menu_item_id, depth, menu_id) {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: "wpmm_item_settings_load",
                menu_item_id: menu_item_id,
                menu_item_depth: depth,
                menu_id: menu_id,
                wpmm_nonce: wpmm.wpmm_nonce
            },
            cache: false,
            beforeSend: function () {
                $('.wpmm-item-settings-content').html('<div class="wpmm-item-loading"></div>');
            },
            complete: function () {
                //$('.wpmm-item-settings-content').empty();
            },
            success: function (response) {
                $('.wpmm-item-settings-content').html(response);
                //settings shortable
                initiate_sortable();
                $('.wpmm-icons-wrap').tabs();
                $('#wpmm-item-settings-tabs').wpmmBuilderTab();
                $('.wpmm-item-settings-heading').html($('#menu-item-'+menu_item_id+' .menu-item-title').text());

                if (jQuery().wpColorPicker) {
                    $('.wpmmColorPicker').wpColorPicker();
                }
                if (jQuery().select2) {
                    $('.wpmm-select2').select2({
                        templateSelection: Select2formatColor,
                        templateResult: Select2formatColor
                    });
                }

                //getting empty widgetId for for WordPress 4.8 widgets when popup settings is opened, closed and
                // reopened
                if (wp.textWidgets !== undefined) {
                    wp.textWidgets.widgetControls = {}; // WordPress 4.8 Text Widget
                }
                if (wp.mediaWidgets !== undefined) {
                    wp.mediaWidgets.widgetControls = {}; // WordPress 4.8 Media Widgets
                }

                $('.widget').each(function() {
                    add_wpmm_events_to_widget($(this));
                });

                wpmm_widget_search();
            }
        });
    }

    function wpmm_widget_search() {
        var wpmmWidgetSearch = $('#wpmm_widget_search input'),
            wmmDraggableWidgetLists = $('.wmmDraggableWidgetLists');
        wpmmWidgetSearch.on('keyup change paste', function () {
            var wpmmWidgetSearchVal = $(this).val().toLowerCase();
            wmmDraggableWidgetLists.find('.draggableWidget').filter(function () {
                $(this).toggle($(this).text().toLowerCase().includes(wpmmWidgetSearchVal));
            });
        });
    }

    $('.wpmm-select2-no-search').select2({
        minimumResultsForSearch: Infinity,
        templateSelection: Select2formatColor,
        templateResult: Select2formatColor
    });

    function Select2formatColor (icon) {
        var iConElement = icon.element;
        var $icon = $('<i class="fa ' + $(iConElement).data('icon') + '"/><span> '+ icon.text+'</span>');
        return $icon;
    }

    /**
     * Initial sortable for drag and drop
     */
    function initiate_sortable(){
        if (jQuery().sortable()){
            $(".wpmm-item-wrap").sortable({
                connectWith: ".wpmm-item-wrap, .wmmDraggableWidgetLists",
                items: " .widget",
                placeholder: "drop-highlight",
                receive: function(event, ui) {
                    //alert('Connected Receive first call');
                    wpmm_saving_indicator('show');
                    var from_item_index = ui.item.attr('data-item-key-id');

                    var item_order = $(this).sortable('toArray', {attribute: 'data-item-key-id'}).toString();
                    var last_index = item_order.split(',').pop();


                    var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));

                    var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
                    var col_id = parseInt($(this).closest('.wpmm-col').data('col-id'));

                    var from_row_id = parseInt(ui.sender.closest('.wpmm-row').data('row-id'));
                    var from_col_id = parseInt(ui.sender.closest('.wpmm-col').data('col-id'));

                    //outsideWidget drag to inside
                    if (ui.sender.attr('data-type') === 'outsideWidget'){
                        var reorder_item_type = ui.sender.attr('data-type');
                        var widget_base_id = ui.sender.attr('data-widget-id-base');

                        var data = {
                            action: 'wpmm_drag_to_add_widget_item',
                            menu_item_id: menu_item_id,
                            item_order: item_order,
                            row_id: row_id,
                            col_id: col_id,

                            type : 'connect',
                            from_item_index : from_item_index,

                            widget_base_id : widget_base_id,
                            reorder_item_type : reorder_item_type,
                            last_index : last_index
                        };

                        //Saving via post method in db
                        $.post(ajaxurl, data, function (response) {
                            if (response.success){
                                var menu_id = $('input#menu').val();
                                ajax_request_load_menu_item_settings(menu_item_id, 0, menu_id);
                            }
                            wpmm_saving_indicator('hide');
                        });

                    }else{
                        //rearrange inner widget or menu item
                        var from_item_order = ui.sender.sortable('toArray', {attribute: 'data-item-key-id'}).toString();

                        var data = {
                            action: 'wpmm_reorder_items',
                            menu_item_id: menu_item_id,
                            item_order: item_order,
                            row_id: row_id,
                            col_id: col_id,

                            type : 'connect',
                            from_item_order : from_item_order,
                            from_item_index : from_item_index,
                            from_row_id : from_row_id,
                            from_col_id : from_col_id
                        };


                        $.post(ajaxurl, data, function (response) {
                            wpmm_saving_indicator('hide');
                        });
                    }
                },

                update: function(event, ui) {
                    //console.log(ui);
                    if (!ui.sender && ui.item.attr('data-type') !== 'outsideWidget') {
                        //console.log(ui.item.attr('data-type'));
                        //alert('Update second call:');

                        wpmm_saving_indicator('show');

                        var item_order = $(this).sortable('toArray', {attribute: 'data-item-key-id'}).toString();
                        var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));

                        var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
                        var col_id = parseInt($(this).closest('.wpmm-col').data('col-id'));

                        var data = {
                            action: 'wpmm_reorder_items',
                            menu_item_id: menu_item_id,
                            item_order: item_order,
                            row_id: row_id,
                            col_id: col_id
                        };

                        $.post(ajaxurl, data, function (response) {
                            wpmm_saving_indicator('hide');
                        });
                    }
                }
            }).disableSelection();

            $(".draggableWidget").draggable({
                connectToSortable: ".wpmm-item-wrap",
                helper: "clone",
                revert: "invalid",
                revertDuration: 0
            }).disableSelection();

            $('#wpmm_item_layout_wrap').sortable({
                items: '.wpmm-row',
                handle: '.wpmmRowSortingIcon',
                placeholder: "drop-highlight",
                update: function(event, ui) {
                    wpmm_saving_indicator('show');
                    var row_order = $(this).sortable('toArray', {attribute: 'data-row-id'}).toString();
                    var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));

                    var data = {
                        action: 'wpmm_reorder_row',
                        menu_item_id: menu_item_id,
                        row_order: row_order
                    };
                    $.post(ajaxurl, data, function (response) {
                        wpmm_saving_indicator('hide');
                    });
                }
            });

            $('.wpmm-row').sortable({
                items: '.wpmm-col',
                handle: '.wpmmColSortingIcon',
                placeholder: "drop-col-highlight",
                update: function(event, ui) {
                    wpmm_saving_indicator('show');
                    var col_order = $(this).sortable('toArray', {attribute: 'data-col-id'}).toString();
                    var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));
                    var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
                    //alert(col_order);
                    var data = {
                        action: 'wpmm_reorder_col',
                        menu_item_id: menu_item_id,
                        col_order: col_order,
                        row_id:row_id
                    };
                    $.post(ajaxurl, data, function (response) {
                        wpmm_saving_indicator('hide');
                    });
                }
            });

        }
    }
    $(document).on('click', '.wpmmRowDeleteIcon', function () {
        var button_clicked = $(this);
        var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));
        var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
        var data = {
            action: 'wpmm_delete_row',
            menu_item_id: menu_item_id,
            row_id: row_id,
            wpmm_nonce: wpmm.wpmm_nonce
        };
        $.post(ajaxurl, data, function (response) {
            if (response.success){
                button_clicked.closest('.wpmm-row').hide();
            }
        });
    });
    $(document).on('click','.wpmm-isp-close-btn', function(e){
        e.preventDefault();
        $('.wp-megamenu-item-settins-wrap').hide();
        $('#wpmmSettingOverlay').hide();
    });
    $(document).on('change','#dropdown_widgets_list', function(){
        var widget_id = $(this).val();
        if (widget_id === ''){
            return false;
        }
        var selector = $(this);
        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');

        wpmm_saving_indicator('show');
        $.post(ajaxurl, { action: "wpmm_add_widget_to_item", widget_id: widget_id, menu_item_id : menu_item_id, wpmm_nonce: wpmm.wpmm_nonce}, function (response) {
            if (response.success){
                var widget_id = response.data.widget_id;/*
                 $.post(ajaxurl, { action: "wpmm_get_widget_to_item", widget_id: widget_id, menu_item_id : menu_item_id}, function (response) {
                 $('.item-widgets-wrap').append(response);
                 selector.val('');
                 });*/
                var menu_id = $('input#menu').val();
                ajax_request_load_menu_item_settings(menu_item_id, 0, menu_id);
            }
            wpmm_saving_indicator('hide');
        });
    });
    /**
     * Open widget form for saving them from item settings model
     */
    /*    $(document).on('click', '.widget-form-open', function (e) {
     e.preventDefault();
     $(this).closest('.widget').find('.wdiget-inner-wrap').slideToggle();
     });*/
    /**
     * Close widget form
     */
    $(document).on('click', '.widget-controls a.close', function (e) {
        e.preventDefault();
        $(this).closest('.widget').find('.wdiget-inner-wrap').slideUp();
    });
    /**
     * Save widget input
     */
    $(document).on('submit', 'form.wpmm_widget_save_form', function (e) {
        e.preventDefault();
        wpmm_saving_indicator('show');

        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');
        var widget_key_id = $(this).closest('.widget').data('widget-key-id');
        var form_input = $(this).closest('form').serialize()+'&action=wpmm_save_widget&menu_item_id='+menu_item_id+'&widget_key_id='+widget_key_id+'&wpmm_nonce='+wpmm.wpmm_nonce;
        $.post(ajaxurl, form_input, function (response) {
            wpmm_saving_indicator('hide');
        });
    });
    /**
     * Delete widget from menu
     */
    $(document).on('click', '.widget-controls a.delete', function (e) {
        e.preventDefault();

        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');
        var widget_key_id = $(this).closest('.widget').data('item-key-id');
        var widget_wrap = $(this).closest('.widget');

        var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
        var col_id = parseInt($(this).closest('.wpmm-col').data('col-id'));
        wpmm_saving_indicator('show');
        var form_data = $(this).closest('form').serialize()+'&action=wpmm_delete_widget&menu_item_id='+menu_item_id+'&widget_key_id='+widget_key_id+'&row_id='+row_id+'&col_id='+col_id+'&wpmm_nonce='+wpmm.wpmm_nonce;
        $.post(ajaxurl, form_data, function (response) {
            widget_wrap.find('.wdiget-inner-wrap').slideUp();
            widget_wrap.hide();
            wpmm_saving_indicator('hide');
        });
    });
    /**
     * Change menu type and seve them to item settings option
     */
    $(document).on('change', '#menu_type', function(e){
        e.preventDefault();
        wpmm_saving_indicator('show');
        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');
        var menu_type = $(this).val();

        $.post(ajaxurl, { action: 'wpmm_change_menu_type', menu_item_id : menu_item_id, menu_type : menu_type, wpmm_nonce: wpmm.wpmm_nonce}, function (response) {
            if (response.success){

            }
        });
        wpmm_saving_indicator('hide');
    });
    $(document).on('change', '#wpmm-onoff', function(e){
        e.preventDefault();
        wpmm_saving_indicator('show');
        var menu_item_id = $('.wpmm-item-settings-panel').data('id');
        var menu_type;

        if( $(this).is(':checked') ){
            menu_type = 'wpmm_mega_menu';

            $('.wpmmDraggableWidgetArea').removeClass('disabled');
            //$('.wpmm-widget-lists').removeClass('disabled');
            $('.wpmmWidgetListLi').show();

            //Force enabling MegaMenu Right now
            $wpmm_is_enabled = $('input.wpmm_is_enabled');
            if ( ! $wpmm_is_enabled.is(':checked') ){
                $('input.wpmm_is_enabled').prop('checked', true);
                $('#save_wpmm_theme_nav').trigger('click');
            }

        }else{
            menu_type = 'wpmm_dropdown_menu';
            $('.wpmmDraggableWidgetArea').addClass('disabled');
            //$('.wpmm-widget-lists').addClass('disabled');
            $('.wpmmWidgetListLi').hide();
        }
        $.post(ajaxurl, { action: 'wpmm_change_menu_type', menu_item_id : menu_item_id, menu_type : menu_type, wpmm_nonce: wpmm.wpmm_nonce}, function (response) {
            if (response.success){

            }
        });
        wpmm_saving_indicator('hide');
    });
    $(document).on('submit','form.wpmm_item_options_form', function (e) {
        e.preventDefault();
        var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));
        var form_input = $(this).serialize()+'&action=wpmm_menu_item_option_save&menu_item_id='+menu_item_id+'&wpmm_nonce='+wpmm.wpmm_nonce;

        wpmm_saving_indicator('show');
        $.post(ajaxurl, form_input, function (response) {
            wpmm_saving_indicator('hide');
        });
    });
    /**
     * Save item icon for menu item
     */
    $(document).on('click', '.wpmm-icons', function (e) {
        e.preventDefault();
        var icon = $(this).data('icon');
        var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));
        var item = $(this);

        wpmm_saving_indicator('show');
        $.post(ajaxurl, {action : 'wpmm_icon_update',  menu_item_id:menu_item_id, icon : icon, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
            $('.wpmm-icons').removeClass('wpmm-icon-selected');
            item.addClass('wpmm-icon-selected');
            wpmm_saving_indicator('hide');
        });
    });
    /**
     * Search iCon
     */
    $(document).on('keyup change paste', '#wpmm_icons_search', function (e) {
        var search_term = $(this).val().toUpperCase();
        $('.wpmm-icons').each(function(){
            search_term = search_term.toUpperCase();
            var icon_title = $(this).data('icon').toUpperCase();

            if (icon_title.indexOf(search_term) > -1 ){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
    /**
     * Predefined layout structure
     */
    $(document).on('click', '.wpmm-modal .close', function(e){
        e.preventDefault();
        $(this).closest('#layout-modal').hide();
    });
    $(document).on('click', 'button#choose_layout', function(e){
        e.preventDefault();
        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');
        $('#layout-modal').attr('data-menu-item-id', menu_item_id).toggle();
    });
    $(document).on('click', '.menu-layout-list li a', function(e){
        e.preventDefault();

        var layout_selector = $(this);
        var menu_item_id = parseInt($('#layout-modal').attr('data-menu-item-id'));
        var menu_id = $('input#menu').val();

        //var layout_design = $('#'+$(this).data('design')).html();
        var layout_format = $(this).data('layout');
        var layout_name = $(this).data('design');
        var current_rows = $('#wpmm_item_layout_wrap .wpmm-row').length;

        //$('.item-widgets-wrap').html(layout_design);
        $.post(ajaxurl, {action : 'wpmm_save_layout', layout_format : layout_format, layout_name : layout_name, menu_item_id : menu_item_id, current_rows:current_rows, menu_id:menu_id, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
            //$('#wpmm_item_layout_wrap').html(response);
            ajax_request_load_menu_item_settings(menu_item_id, 0, menu_id);
            //initiate_sortable();

            layout_selector.closest('#layout-modal').hide();
        });
    });
    /**
     * Theme Delete
     */
    $(document).on('click', '.deleteWpmmTheme', function (e) {
        e.preventDefault();
        if ( ! confirm('Are you sure?')){
            return false;
        }
        var this_btn = $(this);
        var theme_id = this_btn.data('id');

        $.post(ajaxurl, {action : 'wpmm_theme_delete', theme_id : theme_id, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
            if (response.success){
                this_btn.closest('tr').hide('slow');
            }
        });
    });
    $('#save_wpmm_theme_nav').click( function (e) {
        e.preventDefault();
        var menu_id = $('input#menu').val();
        var selected_theme = $('input[name=selected_theme]:checked').val();
        var wpmm_settings = JSON.stringify($("[name^='wpmm_nav_settings']").serializeArray());


        if (typeof selected_theme === 'undefined'){
            $('#wpmm_themes_response').html('<div class="notice notice-error"><p>'+wpmm.select_theme+'</p></div>');
            return false;
        }else{
            $('#wpmm_themes_response').html('');
        }

        $.post(ajaxurl, {action : 'wpmm_nav_menu_save', menu_id : menu_id, selected_theme:selected_theme, wpmm_settings : wpmm_settings, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
            if (response.success){
                $('#wpmm_themes_response').html('<div class="notice notice-success"><p>'+response.data.msg+'</p></div>');
            }
        });
    });
    /**
     * Plugin for WPMM Menu builder
     * @param options
     */
    $.fn.wpmmBuilderTab = function( options ) {
        // This is the easiest way to have default options.
        var settings = $.extend({ }, options );

        var t = this;
        this.find('.wpmm-tabs-content > div').hide();
        this.find('.wpmm-tabs-content > div:first-child').show();
        this.find('.wpmm-tabs-menu > ul > li > ul').hide();
        this.find('.wpmm-tabs-menu > ul > li.active > ul').show();

        this.find('.wpmm-tabs-menu > ul > li').click( function(e){
            e.preventDefault();

            var $selectedLi = $(this);

            if ( ! $selectedLi.hasClass( "active" )){
                t.find('.wpmm-tabs-menu > ul > li > ul').hide();
                t.find('.wpmm-tabs-menu > ul > li').removeClass('active');
                $selectedLi.addClass('active');
                $selectedLi.find('ul').slideDown();
            }

            $tabSelector = $selectedLi.children('a').attr('href');
            t.find('.wpmm-tabs-content > div').hide();
            t.find('.wpmm-tabs-content > div'+$tabSelector).show();
        });
    };

    $.fn.wpmmTooltip = function( options ) {
        var settings = $.extend({ }, options );
        $(this).each(function(){
            $(this).append('<span class="tooltiptext">'+$(this).data('tip')+'</span>');
        });
    };
    $('.tooltip').wpmmTooltip();

    $(document).on('change', '[name="wpmm_strees_row"]', function(e){
        var wpmm_strees_row = $(this).val();
        var wpmm_stress_row_width_label = $('#wpmm_stress_row_width_label');

        if (wpmm_strees_row === 'wpmm-strees-row' || wpmm_strees_row === 'wpmm-strees-default'){
            wpmm_stress_row_width_label.show();
        }else{
            wpmm_stress_row_width_label.hide();
        }

        if (wpmm_strees_row.length > 0) {
            wpmm_saving_indicator('show');
            var menu_item_id = $('.wpmm-item-settings-panel').data('id');

            $.post(ajaxurl, {
                action: 'wpmm_change_strees_row',
                menu_item_id: menu_item_id,
                wpmm_strees_row: wpmm_strees_row,
                wpmm_nonce: wpmm.wpmm_nonce
            }, function (response) {
                if (response.success) {

                }
            });
        }
        wpmm_saving_indicator('hide');
    });

    $(document).on('change past', '#wpmm_item_row_width', function(e){
        var width = $(this).val();
        if (width.length > 0){
            e.preventDefault();
            wpmm_saving_indicator('show');
            var menu_item_id = $('.wpmm-item-settings-panel').data('id');

            $.post(ajaxurl, { action: 'wpmm_set_menu_width', menu_item_id : menu_item_id, width : width, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
                if (response.success){

                }
            });
            wpmm_saving_indicator('hide');
        }
    });

    $(document).on('change past', '#wpmm_stress_row_width', function(e){
        var width = $(this).val();
        if ( ! width){
            width = 0;
        }
        //if (width.length > 0){
            e.preventDefault();
            wpmm_saving_indicator('show');
            var menu_item_id = $('.wpmm-item-settings-panel').data('id');

            $.post(ajaxurl, { action: 'wpmm_set_strees_row_width', menu_item_id : menu_item_id, width : width, wpmm_nonce: wpmm.wpmm_nonce }, function (response) {
                if (response.success){
                    //
                }
            });
            wpmm_saving_indicator('hide');
        //}
    });

    function add_wpmm_events_to_widget(widget) {
        var update = widget.find(".widget-action");
        var close = widget.find(".widget-controls .close");
        var id = widget.attr("id");
        update.on('click', function(){
            if (! widget.hasClass("open")) {
                //Supporting Black Studio TinyMCE
                if ( widget.is( '[id*=black-studio-tinymce]' ) ) {
                    bstw( widget ).deactivate().activate();
                }
                $( document ).trigger('widget-added', [widget]);
                widget.toggleClass("open");
            }else{
                widget.toggleClass('open');
            }
        });
        close.on('click', function (e) {
            e.preventDefault();
            widget.removeClass('open');
        });
        $(".widget").not(widget).removeClass("open");
    }

/*    $(document).on('click', '.widget-action', function (e) {
        e.preventDefault();

        var widget = $(this).closest('.widget');
        var widget_id = $(this).closest('.widget').attr('id');
        var widget_inner = $(this).closest('.widget').find('.widget-inner');
        var data = {widget_id : widget_id, action: 'wpmm_edit_widget'};
        if (! widget.hasClass("open")) {
            $.post(ajaxurl, data, function (response) {
                widget_inner.html(response);
                //Supporting Black Studio TinyMCE
                if ( widget.is( '[id*=black-studio-tinymce]' ) ) {
                    bstw( widget ).deactivate().activate();
                }
                $( document ).trigger('widget-added', [widget]);
                widget.toggleClass("open");
            });
        }else{
            widget.toggleClass('open');
        }
        /!*
        var widget = $(this).closest('.widget');
        if (! widget.hasClass("open")) {
            //Supporting Black Studio TinyMCE
            if ( widget.is( '[id*=black-studio-tinymce]' ) ) {
                bstw( widget ).deactivate().activate();
            }
            $( document ).trigger('widget-added', [widget]);
            widget.toggleClass("open");
        }else{
            widget.toggleClass('open');
        }*!/

    });*/


    //var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
    $(document).on('click','.wpmm_upload_image_button', function( event ){
        event.preventDefault();

        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id

        var button_selector = $(this);
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            // Set the post ID to what we want
            //file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            //wp.media.model.settings.post.id = set_to_post_id;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: false	// Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            button_selector.closest('div.wpmm-image-upload-wrap').find('.wpmm_upload_image_preview_wrap').html('<img' + ' src="'+attachment.url+'" class="wpmm_upload_image_preview" /><a href="javascript:;" class="wpmm_img_delete"><i class="fa fa-trash-o"></i></a>');
            //$( '#image_attachment_id' ).val( attachment.id );
            button_selector.closest('div.wpmm-image-upload-wrap').find('.wpmm_upload_image_field').val(attachment.url);
            // Restore the main post ID
            wp.media.model.settings.post.id = wp_media_post_id;
        });
        // Finally, open the modal
        file_frame.open();
    });
    // Restore the main ID when the add media button is pressed
    jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });

    $(document).on('click', '.wpmm_img_delete', function (e) {
        e.preventDefault();
        button_selector = $(this);
        button_selector.closest('div.wpmm-image-upload-wrap').find('.wpmm_upload_image_field').val('');
        button_selector.closest('div.wpmm-image-upload-wrap').find('.wpmm_upload_image_preview_wrap').html('');
    });

    $(document).on('click', '.nav-integration-code-by-slug', function (e) {
        $('.integration-code-by-id').hide();
        $('.integration-code-by-slug').show();
    });
    $(document).on('click', '.nav-integration-code-by-id', function (e) {
        $('.integration-code-by-id').show();
        $('.integration-code-by-slug').hide();
    });


    $('.add_item').on('click', function (e) {
        e.preventDefault();
        var form_parent = $(this).closest('.form_parent');
        var form_item = form_parent.find('.form_item').last().clone();
        form_item.insertBefore($(this));
        remove_item();
    });

    function remove_item() {
        $('.remove_item').on('click', function (e) {
            e.preventDefault();
            if ($('.form_parent').children().length > 3){
                $(this).closest('.form_item').remove();
            }
        });
    }

    remove_item();

    /*
     * Remove Review notice permanently
     */

    $('.wpmm-remove-rating-notice').on('click', function () {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: "wpmm_rating_notice",
                wpmm_nonce: wpmm.wpmm_nonce,
                wpmm_notice_action: $(this).data('type')
            },
            success: function (response) {
                console.log(response);
                $('.wpmm-review-notice').remove();
            }
        });
    });

    /*
     * Export Current Mega Menu Theme 
     */
    function download_to_txt(filename, text) {
      var element = document.createElement("a");
      element.setAttribute(
        "href",
        "data:text/plain;charset=utf-8," + encodeURIComponent(text)
      );
      element.setAttribute("download", filename);

      element.style.display = "none";
      document.body.appendChild(element);

      element.click();

      document.body.removeChild(element);
    }
    
    /*
     * Export Current Mega Menu Theme 
     */

    $(".export-wpmm-theme").on("click", function (e) {
      e.preventDefault();
      var theme_name = $(this).data("theme_name");
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "export_wpmm_theme",
          theme_id: $(this).data("theme_id"),
          wpmmm_save_new_theme_nonce_field: wpmm.wpmm_nonce,
        },
        success: function (response) {
            download_to_txt(theme_name, response);
        },
      });
    });

    /*
     * Export Mega Menu  
     */
    $(".wp-megamenu-nav-export").on("click", function (e) {
      e.preventDefault();

      var theme_name = $('[name="selected_theme"]:checked').data("title");

      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "export_wp_megamenu_nav_menu",
          wpmmm_nav_export_nonce_field: wpmm.wpmm_nonce,
        },
        success: function (response) {
          download_to_txt(theme_name, response);
        },
      });
    });


})(jQuery);