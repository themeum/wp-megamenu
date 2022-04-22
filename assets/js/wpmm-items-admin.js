(function ($) {


    /**
     *
     */
    function load_row_layout() {
        console.log('load layout');
    }

    /**
     * Open item settings from bottom popup
     */
    $('.wp_megamenu_lauch').click(function (e) {
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

        document.addEventListener('keydown', function (event) {
            if (event.keyCode === 27) {
                if (!$('#widget_search_field').val()) {
                    if ($('.wpmm-item-widget-panel').is(":visible")) {
                        $('.wpmm-item-widget-panel').hide().html('');
                    } else {
                        $('.wp-megamenu-item-settins-wrap').hide();
                        $('#wpmmSettingOverlay').hide();
                    }
                }
            }
        });

    });

    var MenuChild = $('#menu-to-edit').children('li.menu-item');
    menu_id = $('input#menu').val();
    MenuChild.on('click', function () {
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
                initiate_actions_on_layout_modal();

            }
        });
    }

    /**
     * @return [type]
     */
    function initiate_actions_on_layout_modal() {
        $('.widget-list-item').on('click', function (e) {
            e.preventDefault();
            //alert('Connected Receive first call');
            /* wpmm_saving_indicator('show');
            var from_item_index = ui.item.attr('data-item-key-id');

            var item_order = $(this).sortable('toArray', { attribute: 'data-item-key-id' }).toString();
            var last_index = item_order.split(',').pop();


            var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));

            var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
            var col_id = parseInt($(this).closest('.wpmm-col').data('col-id'));

            var from_row_id = parseInt(ui.sender.closest('.wpmm-row').data('row-id'));
            var from_col_id = parseInt(ui.sender.closest('.wpmm-col').data('col-id'));

            //outsideWidget drag to inside
            if (ui.sender.attr('data-type') === 'outsideWidget') {
                var reorder_item_type = ui.sender.attr('data-type');
                var widget_base_id = ui.sender.attr('data-widget-id-base');

                var data = {
                    action: 'wpmm_drag_to_add_widget_item',
                    menu_item_id: menu_item_id,
                    item_order: item_order,
                    row_id: row_id,
                    col_id: col_id,

                    type: 'connect',
                    from_item_index: from_item_index,

                    widget_base_id: widget_base_id,
                    reorder_item_type: reorder_item_type,
                    last_index: last_index
                };

                //Saving via post method in db
                $.post(ajaxurl, data, function (response) {
                    if (response.success) {
                        var menu_id = $('input#menu').val();
                        ajax_request_load_menu_item_settings(menu_item_id, 0, menu_id);
                    }
                    wpmm_saving_indicator('hide');
                });

            } else {
                //rearrange inner widget or menu item
                var from_item_order = ui.sender.sortable('toArray', { attribute: 'data-item-key-id' }).toString();

                var data = {
                    action: 'wpmm_reorder_items',
                    menu_item_id: menu_item_id,
                    item_order: item_order,
                    row_id: row_id,
                    col_id: col_id,

                    type: 'connect',
                    from_item_order: from_item_order,
                    from_item_index: from_item_index,
                    from_row_id: from_row_id,
                    from_col_id: from_col_id
                };


                $.post(ajaxurl, data, function (response) {
                    wpmm_saving_indicator('hide');
                });
            } */
        });

        $('.widget-form-open,.widget-controls a.close').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.widget').find('.widget-inner').slideToggle('fast');
        });

        $('.wpmm-column-layout').on('click', function (e) {
            load_row_layout();
        })


        $('.wpmm-add-row button').on('click', function (e) {
            $('.wpmm-add-slots').slideToggle('fast');
        })

    }


    function search_widget_on_modal(e) {
        var filter, ul, li, items_count_hidden, i, txtValue;
        filter = e.target.value.toUpperCase();
        ul = document.querySelector(".wpmm-widget-items .wpmm-item-grid");
        li = ul.querySelectorAll('.widget-list-item');
        no_item = document.querySelector('.no_item');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            txtValue = li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }

        items_count_hidden = Array.prototype.slice.call(li).reduce(function (a, b) {
            return a + (b.style.display != "none" ? 1 : 0)
        }, 0);
        no_item.style.display = items_count_hidden > 0 ? 'none' : 'block';
    }

    /**
     * Request for registered widgets to select as megamenu item.
     *
     * @return html
     */
    function ajax_request_widget_panel_to_select_menu_item() {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: "wpmm_item_widget_panel",
                wpmm_nonce: wpmm.wpmm_nonce
            },
            // cache: false,
            beforeSend: function () {
                // $('.wpmm-item-settings-content').html('<div class="wpmm-item-loading"></div>');
            },
            complete: function () {
                //$('.wpmm-item-settings-content').empty();
            },
            success: function (response) {
                $('.wpmm-item-widget-panel').show().html(response);
                var widgetSearchField = document.getElementById('widget_search_field');

                $('.close-widget-modal').on('click', function (e) {
                    if ($('.wpmm-item-widget-panel').is(":visible")) {
                        $('.wpmm-item-widget-panel').hide().html('');
                    }
                })

                if (widgetSearchField) {
                    widgetSearchField.onkeyup = (e) => {
                        search_widget_on_modal(e);
                    }
                }
            }
        });
    }



    /**
     * Initial sortable for drag and drop
     */
    function initiate_sortable() {
        if (jQuery().sortable()) {
            $(".wpmm-item-wrap").sortable({
                connectWith: ".wpmm-item-wrap, .wmmDraggableWidgetLists",
                items: " .widget",
                placeholder: "drop-highlight",
                receive: function (event, ui) {
                    console.log(ui);
                    //alert('Connected Receive first call');
                    wpmm_saving_indicator('show');
                    var from_item_index = ui.item.attr('data-item-key-id');

                    var item_order = $(this).sortable('toArray', { attribute: 'data-item-key-id' }).toString();
                    var last_index = item_order.split(',').pop();


                    var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));

                    var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
                    var col_id = parseInt($(this).closest('.wpmm-col').data('col-id'));

                    var from_row_id = parseInt(ui.sender.closest('.wpmm-row').data('row-id'));
                    var from_col_id = parseInt(ui.sender.closest('.wpmm-col').data('col-id'));

                    //outsideWidget drag to inside
                    if (ui.sender.attr('data-type') === 'outsideWidget') {
                        var reorder_item_type = ui.sender.attr('data-type');
                        var widget_base_id = ui.sender.attr('data-widget-id-base');

                        var data = {
                            action: 'wpmm_drag_to_add_widget_item',
                            menu_item_id: menu_item_id,
                            item_order: item_order,
                            row_id: row_id,
                            col_id: col_id,

                            type: 'connect',
                            from_item_index: from_item_index,

                            widget_base_id: widget_base_id,
                            reorder_item_type: reorder_item_type,
                            last_index: last_index
                        };

                        //Saving via post method in db
                        $.post(ajaxurl, data, function (response) {
                            if (response.success) {
                                var menu_id = $('input#menu').val();
                                ajax_request_load_menu_item_settings(menu_item_id, 0, menu_id);
                            }
                            wpmm_saving_indicator('hide');
                        });

                    } else {
                        //rearrange inner widget or menu item
                        var from_item_order = ui.sender.sortable('toArray', { attribute: 'data-item-key-id' }).toString();

                        var data = {
                            action: 'wpmm_reorder_items',
                            menu_item_id: menu_item_id,
                            item_order: item_order,
                            row_id: row_id,
                            col_id: col_id,

                            type: 'connect',
                            from_item_order: from_item_order,
                            from_item_index: from_item_index,
                            from_row_id: from_row_id,
                            from_col_id: from_col_id
                        };


                        $.post(ajaxurl, data, function (response) {
                            wpmm_saving_indicator('hide');
                        });
                    }
                },

                update: function (event, ui) {
                    //console.log(ui);
                    if (!ui.sender && ui.item.attr('data-type') !== 'outsideWidget') {
                        //console.log(ui.item.attr('data-type'));
                        //alert('Update second call:');

                        wpmm_saving_indicator('show');

                        var item_order = $(this).sortable('toArray', { attribute: 'data-item-key-id' }).toString();
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


            /* wpmm new block */
            $('.wpmm-add-new-item').on('click', function (e) {
                e.preventDefault();
                ajax_request_widget_panel_to_select_menu_item();
            })


            $(".wpmm-column-contents").sortable({
                connectWith: ".wpmm-column-contents",
                items: " .wpmm-cell",
                handle: '.widget-top',
                placeholder: "drop-highlight",
                update: function (event, ui) {
                    console.log('item updated');
                }

            }).disableSelection();

            $('#wpmm_layout_wrapper').sortable({
                items: '.wpmm-layout-row',
                handle: '.wpmm-row-sorting-icon',
                placeholder: "drop-highlight",
                update: function (event, ui) {
                    console.log('layout updated');
                }
            });


            $('.wpmm-item-row').sortable({
                items: '.wpmm-item-col',
                handle: '.wpmm-col-sorting-icon',
                placeholder: "drop-col-highlight",
                update: function (event, ui) {
                    console.log('column updated');
                }
            });
            /* wpmm new block */



            $('#wpmm_item_layout_wrap').sortable({
                items: '.wpmm-row',
                handle: '.wpmmRowSortingIcon',
                placeholder: "drop-highlight",
                update: function (event, ui) {
                    wpmm_saving_indicator('show');
                    var row_order = $(this).sortable('toArray', { attribute: 'data-row-id' }).toString();
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
                update: function (event, ui) {
                    wpmm_saving_indicator('show');
                    var col_order = $(this).sortable('toArray', { attribute: 'data-col-id' }).toString();
                    var menu_item_id = parseInt($(this).closest('.wpmm-item-settings-panel').data('id'));
                    var row_id = parseInt($(this).closest('.wpmm-row').data('row-id'));
                    //alert(col_order);
                    var data = {
                        action: 'wpmm_reorder_col',
                        menu_item_id: menu_item_id,
                        col_order: col_order,
                        row_id: row_id
                    };
                    $.post(ajaxurl, data, function (response) {
                        wpmm_saving_indicator('hide');
                    });
                }
            });

        }
    }


})(jQuery);