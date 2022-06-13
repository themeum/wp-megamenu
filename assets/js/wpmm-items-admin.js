/**
 * Request for registered widgets to select as megamenu item.
 *
 * @return html
 */
function ajax_request_widget_panel_to_select_menu_item(menu_item_id, addElemBtn) {
    formData = new FormData();

    requestData = {
        wpmm_nonce: wpmm.wpmm_nonce,
        action: "wpmm_item_widget_panel",
    }

    Object.entries(requestData).forEach(([key, value]) => {
        formData.append(key, value);
    });

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', ajaxurl, true);
    xhttp.send(formData);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
            widgetItemPanel = document.querySelector('.wpmm-item-widget-panel');
            widgetItemPanel.dataset.rowIndex = addElemBtn.dataset.rowIndex;
            widgetItemPanel.dataset.colIndex = addElemBtn.dataset.colIndex;
            widgetItemPanel.style.display = 'block';
            widgetItemPanel.innerHTML = xhttp.response;
            widgetSearchField = document.getElementById('widget_search_field');
            closeWidgetModal = document.querySelector('.close-widget-modal');

            if (null !== closeWidgetModal) {
                closeWidgetModal.addEventListener('click', () => {
                    if ('block' === widgetItemPanel.style.display) {
                        widgetItemPanel.style.display = 'none';
                        widgetItemPanel.innerHTML = '';
                        wpmm_loading(false, 10);
                    }
                })
            }

            if (widgetSearchField) {
                widgetSearchField.onkeyup = (e) => {
                    widget_search_on_modal(e);
                }
            }

            insert_widget_to_column(menu_item_id, addElemBtn);
        }
    };


}

function wpmm_add_new_item(addElemBtn) {
    menu_item_id = document.querySelector('.wp-megamenu-item-settins-wrap').dataset.id;
    ajax_request_widget_panel_to_select_menu_item(menu_item_id, addElemBtn);
}


/**
 * Get latest widget id by id_base.
 */
function get_latest_widget_id_by_id_base(id_base) {
    base_ids = document.querySelectorAll('[data-base-id="' + id_base + '"]');
    console.log(base_ids);
    widget_ids = [];
    base_ids.forEach(item => {
        item_base_id = item.dataset.widgetId.split(id_base + '-')[1];
        if (0 !== item.length && 'undefined' !== typeof item_base_id) {
            widget_ids.push(item.dataset.widgetId.split(id_base + '-')[1]);
        }
    })

    if (1 > widget_ids.length) {
        new_widget_id = 1;
    } else {
        new_widget_id = parseInt(Math.max(...widget_ids));
    }

    return new_widget_id;
}


/**
 *
 */
function create_row_layout(layout, layout_array, new_row_id) {
    let column_ui = '';

    layout_array.forEach((col, index) => {
        console.log(col);
        colWidth = col.column;
        column_ui += `
                <div class="wpmm-item-col wpmm-item-${colWidth}" style="--col-width: calc(${colWidth}% - 1em)" data-width="${colWidth}" data-rowid="${new_row_id}" data-col-id="${index}">
                    <div class="wpmm-column-contents-wrapper">
                        <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                            <span class="wpmm-col-sorting-icon">
                                <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                            </span>
                        </div>
                        <div class="wpmm-column-contents">
                        </div>
                        <div class="wpmm-add-item-wrapper">
                            <button class="wpmm-add-new-item" onclick="wpmm_add_new_item(this)" data-col-index="${index}" data-row-index="${new_row_id}" title="Add Module">
                                <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                            </button>
                        </div>
                    </div>
                </div>`;
    })
    rowLayout = `
        <div class="wpmm-layout-row" data-row-id="${new_row_id}">
            <div class="wpmm-row-toolbar wpmm-item-row wpmm-space-between wpmm-align">
                <div class="wpmm-row-toolbar-left wpmm-row-sorting-icon">
                    <i class="fa fa-sort wpmm-mr-2"></i>
                    <span>Row</span>
                </div>
                <div class="wpmm-row-toolbar-right">
                    <span class="wpmm-row-delete-icon" onclick="wpmm_delete_any_row(this)">
                        <i class="fa fa-trash-o"></i>
                    </span>
                </div>
            </div>
            <div class="wpmm-columns-container wpmm-item-row wpmm-flex-wrap wpmm-gap-1">
                ${column_ui}
            </div>
        </div>`;
    layout.insertAdjacentHTML('beforeend', rowLayout);
}

function insert_widget_to_column(menu_item_id, addElemBtn) {

    widgetListItem = document.querySelectorAll('.widget-list-item');
    widgetListItem.forEach(item => {
        item.addEventListener('click', event => {

            rowID = addElemBtn.dataset.rowIndex;
            colID = addElemBtn.dataset.colIndex;
            widgetBaseId = item.dataset.widgetIdBase;
            widgetByBaseId = get_latest_widget_id_by_id_base(widgetBaseId);

            formData = new FormData();
            requestData = {
                wpmm_nonce: wpmm.wpmm_nonce,
                action: "wpmm_add_widget_to_column",
                menu_item_id: menu_item_id,
                base_id: widgetBaseId,
                widget_existing_id: widgetByBaseId,
                row_id: rowID,
                col_id: colID,
            }

            Object.entries(requestData).forEach(([key, value]) => {
                formData.append(key, value);
            });

            xhttp = new XMLHttpRequest();
            xhttp.open('POST', ajaxurl, true);
            xhttp.send(formData);
            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4) {

                    respData = JSON.parse(xhttp.response);
                    viewData = new FormData();
                    requestData = {
                        wpmm_nonce: wpmm.wpmm_nonce,
                        action: "wpmm_new_widget_ui",
                        id_base: widgetBaseId,
                        new_base_id: respData.new_base_id,
                    }
                    Object.entries(requestData).forEach(([key, value]) => {
                        viewData.append(key, value);
                    });

                    xhttp = new XMLHttpRequest();
                    xhttp.open('POST', ajaxurl, true);
                    xhttp.send(viewData);
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState === 4) {

                            targetedColumn = document.querySelector('.wpmm-item-col[data-rowid="' +
                                rowID + '"][data-col-id="' + colID + '"]');

                            columnToAddWidgets = targetedColumn && targetedColumn.querySelector('.wpmm-column-contents');

                            columnToAddWidgets.insertAdjacentHTML('beforeend', xhttp.response);
                            widgetItemPanel = document.querySelector('.wpmm-item-widget-panel');

                            if ('block' === widgetItemPanel.style.display) {
                                widgetItemPanel.style.display = 'none';
                                widgetItemPanel.innerHTML = '';
                            }
                            initiate_sortable();
                            triggerWidget = jQuery(`[data-widget-id="${respData.new_base_id}"]`);
                            console.log(triggerWidget);

                            jQuery(document).trigger('widget-added', [triggerWidget]);


                        }
                    }



                }
            };

        })
    });

    document.querySelectorAll('.widget-form-open,.widget-controls a.close').forEach(item => {
        item.addEventListener('click', event => {
            console.log(item);
            wpmm_loading(false, 10);
        })
    });

}


function get_maximum_widget_id_from_ui() {
    var max = 0;
    $('#sortable li').each(function () {
        var val = $(this).data('itemid');
        if (val > max) max = val;
    });
    return max;
}


function get_layout_array() {

    layout = document.getElementById('wpmm_layout_wrapper');
    rows = layout && layout.querySelectorAll('.wpmm-layout-row');

    layout_array = [];
    if (0 !== rows.length) {
        rows.forEach(row => {
            columns = row.querySelectorAll('.wpmm-item-col')
            colsArr = [];

            columns.forEach(column => {
                col_items = column && column.querySelectorAll('.wpmm-column-contents .wpmm-cell');
                cellItemsArr = [];
                col_items.forEach(item => {
                    cellItemsArr.push({
                        widget_id: item.dataset.widgetId,
                        base_id: item.dataset.baseId,
                        item_type: 'widget',
                        widget_class: '',
                        widget_name: '',
                        options: {},
                    })
                })
                layout_colwidth = Math.ceil((column.dataset.width * 12) / 100);

                colsArr.push({
                    col: layout_colwidth,
                    width: column.dataset.width,
                    items: cellItemsArr
                })
            })
            layout_array.push({
                row: colsArr
            })
        });
    }

    return layout_array;
}


function update_layout_array() {

    layout = document.getElementById('wpmm_layout_wrapper');
    rows = layout && layout.querySelectorAll('.wpmm-layout-row');

    layout_array = [];
    if (0 !== rows.length) {
        rows.forEach((row, index) => {
            row.dataset.rowId = index;
        });
    }

}

function get_nav_item_settings() {
    settingsArray = [];
    Array.from(document.getElementById('wpmm_nav_item_settings').elements).forEach(item => {
        if ('' !== item.name) {
            settingsArray.push({
                [item.name]: '' !== item.value ? item.value : ''
            })
        }
    });
    return settingsArray;
}

function wpmm_toggle_layout_builder(button) {
    console.log(button);
    document.getElementById('layout-modal').classList.toggle('show');
}

function toggle_widget_form(thisToggler) {


    widgetInner = thisToggler.parentElement.parentElement.nextElementSibling;
    widgetInner.style.display = 'block' === widgetInner.style.display ? 'none' : 'block';
    wpmm_loading(false, 10);
    widgetWrapper = widgetInner.closest('.widget.wpmm-cell');
    widgetWrapper.classList.toggle('open');

    /* widgetElements = document.querySelectorAll('.widget.wpmm-cell');
    widgetElements.forEach(item => {
        console.log(item);
    }) */

    // setTimeout(() => {
    elemTrigger = jQuery(widgetWrapper);
    // console.log([(elemTrigger)]);
    jQuery(document).trigger('widget-added', [elemTrigger]);
    // wp.mediaWidgets.handleWidgetAdded(event, self.ui.form);


    /* console.log(jQuery(document).trigger('widget-added', [elemTrigger]));

    document.addEventListener("widget-added", function (e) {
        console.log(e.detail); // Prints "Example of an event"
    }); */
    // console.log(wp.media.view.Attachment.render());


    // console.log(elemTrigger.querySelectorAll('textarea'));
    // wp.editor.initialize(elemTrigger[0].id + '-text');


    //getting empty widgetId for for WordPress 4.8 widgets when popup settings is opened, closed and
    // reopened


    // $('.widget').each(function() {
    //     add_wpmm_events_to_widget($(this));
    // });

    // }, 1000)


}
/**
 * @return mixed
 */
function initiate_actions_on_layout_modal(menu_item_id) {

    /* document.querySelectorAll('.widget-form-open,.widget-controls a.close').forEach(item => {
        item.addEventListener('click', event => {
            widgetInner = item.parentElement.parentElement.nextElementSibling;
            widgetInner.style.display = 'block' === widgetInner.style.display ? 'none' : 'block';
            wpmm_loading(false, 10);
        })
    }); */

    document.querySelectorAll('.wpmm-column-layout.wpmm-custom').forEach(item => {
        item.addEventListener('click', event => {
            document.querySelector('.wpmm-custom-layout').style.display = 'block';
        })
    })


    document.querySelectorAll('.wpmm-column-layout:not(.wpmm-custom), .wpmm-custom-layout-apply').forEach(item => {
        item.addEventListener('click', event => {

            if (item.classList.contains('wpmm-custom-layout-apply')) {
                new_layout = document.querySelector('input.wpmm-custom-layout-field').value;
                new_layout_structure = ('string' === typeof new_layout && new_layout.includes('+'))
                    ? new_layout.split('+') : [new_layout];
            } else {
                new_layout = item.dataset.layout;
                new_layout_structure = ('string' === typeof new_layout && new_layout.includes(','))
                    ? new_layout.split(',') : [new_layout];
            }

            column_count = Math.ceil(new_layout_structure.reduce((a, b) => parseFloat(a) + parseFloat(b), 0));

            if (100 < column_count) {
                console.log('Sum of columns should less than or equal to 100');
                return false;
            }

            new_columns = [];
            new_layout_structure.forEach((col, i) => {
                new_columns.push({
                    id: i,
                    column: col
                })
            })
            layout_array = get_layout_array();

            if ('undefined' !== typeof layout_array) {
                layout_array.push({
                    columns: new_layout_structure
                });
                layout_array_new = layout_array;
            }
            new_row_id = 'undefined' !== typeof layout_array_new ? layout_array_new.length - 1 : 0;

            create_row_layout(layout, new_columns, new_row_id);
            initiate_sortable();

        })
    })

}


function wpmm_loading(load = true, duration = 1000) {
    wpmmItemContent = document.querySelector('.wpmm-item-content');
    setTimeout(() => {
        if (true === load) { wpmmItemContent.classList.add('loading'); }
        else { wpmmItemContent.classList.remove('loading'); }
    }, duration);

}


function ajax_request_load_menu_item_settings(menu_item_id, depth, menu_id) {

    formData = new FormData();

    requestData = {
        wpmm_nonce: wpmm.wpmm_nonce,
        action: "wpmm_item_settings_load",
        menu_item_id: menu_item_id,
        menu_item_depth: depth,
        menu_id: menu_id,
    }

    Object.entries(requestData).forEach(([key, value]) => {
        formData.append(key, value);
    });

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', ajaxurl, true);
    xhttp.send(formData);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {

            document.querySelector('.wpmm-item-settings-content').innerHTML = xhttp.response;

            initiate_actions_on_layout_modal(menu_item_id);
            initiate_sortable();

            wpmm_loading(false, 200);

            document.querySelectorAll('.close-modal').forEach(item => {
                item.addEventListener('click', () => {
                    document.querySelectorAll('.wp-megamenu-item-settins-wrap, #wpmmSettingOverlay').forEach(item => {
                        item.style.display = 'none';
                        wpmm_loading(true, 0);
                    })
                })
            })

            /* $(document).on('click','.wpmm-isp-close-btn,.close-modal,#wpmmSettingOverlay1', function(e){
                e.preventDefault();
                $('.wp-megamenu-item-settins-wrap').hide();
                $('#wpmmSettingOverlay').hide();
            }); */

            document.querySelectorAll('input.wpmm-form-check-input').forEach(item => {
                set_checkbox_status(item);
                item.onchange = () => set_checkbox_status(item);
            });

        }
    };

}

// Checkbox status by event. set true or false
function set_checkbox_status(item) {
    item.previousElementSibling.value = (true === item.checked) ? true : false;
}

function widget_search_on_modal(e) {
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

function wpmm_save_widget_item(saveButton) {
    saveButton.closest('form').addEventListener('submit', (e) => {
        e.preventDefault();
        saveButton.classList.add('wpmm-btn-spinner');
        thisForm = e.target;
        widget_element = thisForm.closest('.widget.wpmm-cell');
        menu_item = thisForm.closest('.wp-megamenu-item-settins-wrap');

        console.log(widget_element, menu_item.dataset.id);

        formData = new FormData(thisForm);

        requestData = {
            wpmm_nonce: wpmm.wpmm_nonce,
            action: "wpmm_save_widget",
            menu_item_id: menu_item.dataset.id,
            widget_key_id: widget_element.id,
        }
        // console.log(requestData);
        Object.entries(requestData).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const xhttp = new XMLHttpRequest();
        xhttp.open('POST', ajaxurl, true);
        xhttp.send(formData);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                console.log(xhttp.response);
                setTimeout(() => {
                    saveButton.classList.remove('wpmm-btn-spinner');
                }, 1000)

            }
        };

    })
    // {"widget_id":"pages-15","item_type":"widget","widget_class":"","widget_name":"","options":{}}
    /* $(document).on('submit', 'form.wpmm_widget_save_form', function (e) {
        e.preventDefault();
        wpmm_saving_indicator('show');

        var menu_item_id = $(this).closest('.wpmm-item-settings-panel').data('id');
        var widget_key_id = $(this).closest('.widget').data('widget-key-id');
        var form_input = $(this).closest('form').serialize()+'&action=wpmm_save_widget&menu_item_id='+menu_item_id+'&widget_key_id='+widget_key_id+'&wpmm_nonce='+wpmm.wpmm_nonce;
        $.post(ajaxurl, form_input, function (response) {
            wpmm_saving_indicator('hide');
        });
    }); */
}
/**
 * Select Widget as menu item
 */
function wpmm_delete_this_widget(deleteButton) {
    widget_element = deleteButton.closest('.widget.wpmm-cell');
    // widget_element
    rowId = widget_element.closest('.wpmm-item-col').dataset.rowid;
    colId = widget_element.closest('.wpmm-item-col').dataset.colId;
    console.log(rowId, colId, widget_element.id);
    /* var data = {
        action: 'wpmm_delete_this_widget',
        menu_item_id: menu_item_id,
        row_id: row_id,
        wpmm_nonce: wpmm.wpmm_nonce
    };
    $.post(ajaxurl, data, function (response) {
        if (response.success) {
            button_clicked.closest('.wpmm-layout-row').remove();
        }
    }); */
    widget_element.remove();
}

/*
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
*/
/**
 * Select Widget as menu item
 */
function wpmm_delete_any_row(rowDeleteButton) {
    var menu_item_id = parseInt(rowDeleteButton.closest('.wp-megamenu-item-settins-wrap').dataset.id);
    var row_id = parseInt(rowDeleteButton.closest('.wpmm-layout-row').dataset.rowId);
    var data = {
        action: 'wpmm_delete_row',
        menu_item_id: menu_item_id,
        row_id: row_id,
        wpmm_nonce: wpmm.wpmm_nonce
    };
    jQuery.post(ajaxurl, data, function (response) {
        if (response.success) {
            rowDeleteButton.closest('.wpmm-layout-row').remove();
            update_layout_array();
        }
    });
}

/**
 * Initial sortable for drag and drop
 */
function initiate_sortable() {

    Sortable.create(document.querySelector('#wpmm_layout_wrapper'), {
        draggable: ".wpmm-layout-row",
        handle: ".wpmm-row-sorting-icon",
        animation: 150,
        ghostClass: 'wpmm-blue-bg',
        onEnd: (e) => {
            update_layout_array();
        }
    });

    document.querySelectorAll('.wpmm-item-row').forEach(item => {
        Sortable.create(item, {
            draggable: ".wpmm-item-col",
            handle: '.wpmm-col-sorting-icon',
            animation: 150,
            ghostClass: 'wpmm-blue-bg'
        });
    });

    document.querySelectorAll('.wpmm-column-contents').forEach(item => {
        Sortable.create(item, {
            draggable: ".wpmm-cell",
            handle: '.widget-top',
            animation: 150,
            ghostClass: 'wpmm-blue-bg',
            group: 'wpmm-layout-row'
        });
    });

}





// Save action to save navigation settings and layout
const wpmmSaveNavItemFunction = (saveBtn) => {
    settings_form = document.getElementById('wpmm_nav_item_settings');
    settings_form.addEventListener('submit', (e) => {
        e.preventDefault();

        if ('undefined' !== typeof submitForm && true === submitForm) return;

        submitForm = true;
        menu_item_id = saveBtn.closest('.wp-megamenu-item-settins-wrap').dataset.id;
        layout_array_new = get_layout_array();
        menu_item_settings = get_nav_item_settings();
        saveBtn.classList.add('wpmm-btn-spinner');

        formData = new FormData(settings_form);

        requestData = {
            menu_item_id: menu_item_id,
            wpmm_nonce: wpmm.wpmm_nonce,
            action: "wpmm_nav_item_settings",
            layout: JSON.stringify(layout_array_new),
        }
        Object.entries(requestData).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const xhttp = new XMLHttpRequest();
        xhttp.open('POST', ajaxurl, true);
        xhttp.send(formData);
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                setTimeout(() => {
                    saveBtn.classList.remove('wpmm-btn-spinner');
                    console.log(xhttp.response);
                    submitForm = false;
                }, 1000)
            }
        };
    })
}

function initiate_sortable_X() {
    if (jQuery().sortable()) {
        $(".wpmm-item-wrap").sortable({
            connectWith: ".wpmm-item-wrap, .wmmDraggableWidgetLists",
            items: " .widget",
            placeholder: "drop-highlight",
            receive: function (event, ui) {
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



onConfirmRefresh = (e) => {
    console.log(e);
    e.preventDefault();
    return e.returnValue = "Are you sure you want to leave the page?";
}

// window.addEventListener("beforeunload", onConfirmRefresh, { capture: true });


/* window.addEventListener("beforeunload", (e) => {
    e.preventDefault();

    if (confirm("Press a button!") == true) {
        text = "You pressed OK!";
    } else {
        text = "You canceled!";
    }
}); */


(function ($) {

    /**
     * Open item settings from bottom popup
     */
    $('.wp_megamenu_lauch').click(function (e) {
        e.preventDefault();
        // wpmm_loading(false, 10);
        var menu_item = $(this).closest('li.menu-item');
        var menu_id = $('input#menu').val();
        var title = menu_item.find('.menu-item-title').text();
        var id = parseInt(menu_item.attr('id').match(/[0-9]+/)[0], 10);
        var depth = menu_item.attr('class').match(/\menu-item-depth-(\d+)\b/)[1];

        var wpmm_item_settings_wrap = $('.wp-megamenu-item-settins-wrap');
        //Show overlay
        $('#wpmmSettingOverlay').show();
        //Set this item id to settings wrap
        wpmm_item_settings_wrap.removeAttr('data-id');
        wpmm_item_settings_wrap.attr('data-id', id);
        wpmm_item_settings_wrap.show();
        ajax_request_load_menu_item_settings(id, depth, menu_id);


        //Press escape key to close modal

        document.addEventListener('keydown', function (event) {
            if (event.keyCode === 27) {
                if (!$('#widget_search_field').val()) {
                    if ($('.wpmm-item-widget-panel').is(":visible")) {
                        $('.wpmm-item-widget-panel').hide().html('');
                    } else {
                        $('.wp-megamenu-item-settins-wrap').hide();
                        $('#wpmmSettingOverlay').hide();
                        wpmm_item_settings_wrap.attr('data-id', 0);
                    }
                }
            }
        });


    });


    function add_wpmm_events_to_widget(widget) {
        var update = widget.find(".widget-action");
        var close = widget.find(".widget-controls .close");
        var id = widget.attr("id");
        update.on('click', function () {
            if (!widget.hasClass("open")) {
                //Supporting Black Studio TinyMCE
                if (widget.is('[id*=black-studio-tinymce]')) {
                    bstw(widget).deactivate().activate();
                }
                console.log(widget);
                $(document).trigger('widget-added', [widget]);
                widget.toggleClass("open");
            } else {
                widget.toggleClass('open');
            }
        });
        close.on('click', function (e) {
            e.preventDefault();
            widget.removeClass('open');
        });
        $(".widget").not(widget).removeClass("open");
    }


    /* $('.widget').each(function () {
        add_wpmm_events_to_widget($(this));
        console.log($(this));
    }); */
})(jQuery);

