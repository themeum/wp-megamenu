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




function toggle_dropdown() {
    document.querySelectorAll('.toggler_button').forEach(item => {

        item.addEventListener('click', (e) => {
            close_all_opened_dropdown();
            item.closest('.area_toggler').classList.toggle('show');
            e.stopPropagation();
        })

        window.addEventListener('click', (e) => {
            close_all_opened_dropdown();
        })

    })

    wpmm_colum_resizer();

}

function close_all_opened_dropdown() {
    document.querySelectorAll('.area_toggler').forEach(item => {
        item.classList.contains('show') && item.classList.remove('show');
    })
    document.querySelectorAll('.dropdown_buttons').forEach(item => {
        item.addEventListener('click', (e) => e.stopPropagation());
    })
}


function add_new_column(button) {
    col_width = 20;
    row = button.closest('.wpmm-layout-row');

    row_cols = row && row.querySelectorAll('.wpmm-item-col');
    col_item = Array.from(row_cols).pop();

    row_index = row.dataset.rowId;
    if (0 <= row_cols.length) {
        col_index = col_item && col_item.dataset.colId;
        new_col_index = 'undefined' !== typeof col_index ? (parseInt(col_index) + 1) : 0;
    } else {
        new_col_index = 0;
    }

    col_width_all = col_total_width(row) + parseFloat(Math.ceil(col_width));


    layout = row && row.querySelector('.wpmm-columns-container');
    column_layout = column_layout_ui(row_index, new_col_index, col_width);
    if (col_width_all <= 100) {
        layout && layout.insertAdjacentHTML('beforeend', column_layout);
    } else {
        alert('Summ of column can\'t be more than 100%.');
    }
    toggle_dropdown();

}

function drag_and_resize() {

    resizers = document.querySelectorAll('.resizer');
    resizers.forEach(item => {
        item.addEventListener("dragover", function (e) {
            e = e || window.event;
            var dragX = e.pageX, dragY = e.pageY;
            adjustWidth = parseInt(dragX) - parseFloat(item.getBoundingClientRect());
            this_col = item.closest('.wpmm-item-col');
            set_width = parseFloat(this_col.offsetWidth) + parseFloat(adjustWidth);
            this_col.style.setProperty('width', `calc(${set_width}px - 1em)`);
        }, false);
    });

}




function column_layout_ui(row_index, col_index, col_width) {

    return `<div class="wpmm-item-col wpmm-item-${col_width}" style="--col-width: calc(${col_width}% - 1em)" data-width="${col_width}" data-rowid="${row_index}" data-col-id="${col_index}">
        <div class="wpmm-column-contents-wrapper">
            <div class="wpmm-column-toolbar wpmm-column-drag-handler">
                <span class="wpmm-col-sorting-icon">
                    <i class="fa fa-sort wpmm-mr-2 fa-rotate-90"></i> Column
                </span>
                <div class="colum_resizer area_toggler">
                    <button class="fa fa-cog toggler_button"></button>
                    <div class="dropdown_buttons">
                        <div class="btn-col">
                            <div class="col_item">
                                <input type="number" min="20" max="100" value="${col_width}">
                            </div>
                            <div class="col_item">
                                <button class="fa fa-plus increment"></button>
                            </div>
                            <div class="col_item">
                                <button class="fa fa-minus decrement"></button>
                            </div>
                            <div class="col_item">
                                <button class="fa fa-trash remove"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wpmm-column-contents">
            </div>
            <div class="wpmm-add-item-wrapper">
                <button class="wpmm-add-new-item" onclick="wpmm_add_new_item(this)" data-col-index="${col_index}" data-row-index="${row_index}" title="Add Module">
                    <span class="fa fa-plus-square-o wpmm-mr-2" aria-hidden="true"></span> Add Element
                </button>
            </div>
        </div>
    </div>`;
}



function create_row_layout(layout, layout_array, new_row_id) {
    let column_ui = '';

    layout_array.forEach((col, index) => {
        column_ui += column_layout_ui(new_row_id, index, col.column);
    })

    rowLayout = `
        <div class="wpmm-layout-row" data-row-id="${new_row_id}">
            <div class="wpmm-row-toolbar wpmm-item-row wpmm-space-between wpmm-align">
                <div class="wpmm-row-toolbar-left wpmm-row-sorting-icon">
                    <i class="fa fa-sort wpmm-mr-2"></i>
                    <span>Row</span>
                    <div class="colum_maker area_toggler">
                        <button class="fa fa-cog toggler_button"></button>
                        <div class="dropdown_buttons">
                            <button onclick="add_new_column(this)">
                                <i class="fa fa-plus"></i>
                                <span>Add Column</span>
                            </button>
                        </div>
                    </div>
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
    toggle_dropdown();

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


function reset_col_index(row) {

    cols = row && row.querySelectorAll('.wpmm-item-col');

    if (0 !== cols.length) {
        cols.forEach((col, index) => {
            col.dataset.colId = index;
        });
    }

}

function reset_row_index() {

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
    document.getElementById('layout-modal').classList.toggle('show');
}

function toggle_widget_form(thisToggler) {


    widgetInner = thisToggler.closest('.widget.wpmm-cell').querySelector('.widget-inner');
    widgetInner.style.display = 'block' === widgetInner.style.display ? 'none' : 'block';
    wpmm_loading(false, 10);
    widgetWrapper = widgetInner.closest('.widget.wpmm-cell');
    widgetWrapper.classList.toggle('open');

    elemTrigger = jQuery(widgetWrapper);

    jQuery(document).trigger('widget-added', [elemTrigger]);

}
/**
 * @return mixed
 */
function initiate_actions_on_layout_modal(menu_item_id) {

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


function col_total_width(row) {
    totalWidth = [];
    row && row.querySelectorAll('.wpmm-item-col').forEach(item => totalWidth.push(item.dataset.width));
    fullWidth = totalWidth.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
    totalWidth = [];
    return Math.ceil(fullWidth);
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

            let wpmm_settings_modal = document.querySelector('.wpmm-item-settings-content');
            wpmm_settings_modal.innerHTML = xhttp.response;
            params = { menu_item_id };

            // common functions run after item settings panel open
            _actions_after_open_settings_panel(params);

        }
    };

}

function _actions_after_open_settings_panel() {

    wpmm_loading(false, 200);

    initiate_actions_on_layout_modal(params.menu_item_id);

    initiate_sortable();

    document.querySelectorAll('.close-modal').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.wp-megamenu-item-settins-wrap, #wpmmSettingOverlay').forEach(item => {
                item.style.display = 'none';
                wpmm_loading(true, 0);
            })
        })
    })


    document.querySelectorAll('input.wpmm-form-check-input').forEach(item => {
        set_checkbox_status(item);
        item.onchange = () => set_checkbox_status(item);
    });

    document.querySelectorAll('.upload_image_button').forEach(item => {
        wpmm_image_uploader(item, 'Choose ');
    })

    toggle_dropdown();


}

// Colum resizer function
function wpmm_colum_resizer() {
    let wpmm_settings_modal = document.querySelector('.wpmm-item-settings-content');
    let colum_resizers = wpmm_settings_modal && wpmm_settings_modal.querySelectorAll('.colum_resizer');
    colum_resizers.forEach(item => {
        let timer,
            increment = item && item.querySelector('button.increment'),
            decrement = item && item.querySelector('button.decrement'),
            remove = item && item.querySelector('button.remove'),
            this_col = item && item.closest('.wpmm-item-col'),
            this_row = this_col && this_col.closest('.wpmm-columns-container'),
            col_width = this_col && this_col.dataset.width;

        function continuosIncerment() {
            inc_dec_btn_action(++col_width, this_col, this_row, item);
            timer = setTimeout(continuosIncerment, 200);
        }

        function continuosDecrement() {
            inc_dec_btn_action(--col_width, this_col, this_row, item);
            timer = setTimeout(continuosDecrement, 200);
        }



        function timeoutClear() {
            clearTimeout(timer);
        }


        function column_btn_event(event_type) {
            let change_type = 'increment' === event_type ? continuosIncerment : continuosDecrement;
            let change_value = 'increment' === event_type ? increment : decrement;

            change_value.addEventListener('mousedown', change_type);
            change_value.addEventListener('mouseup', timeoutClear);
            change_value.addEventListener('mouseleave', timeoutClear);
            inc_dec_event_control(col_total_width(this_row));
        }

        function inc_dec_btn_action(value, this_col, this_row, item) {
            this_col.dataset.width = value;
            item.querySelector('input[type=number]').value = value;
            this_col.style.setProperty('--col-width', `calc(${value}% - 1em)`);
            inc_dec_event_control(col_total_width(this_row));
        }


        function inc_dec_event_control(fullWidth) {
            if (100 > fullWidth) {
                inc_dec_row_buttons(this_row, 'increment', 'auto');
                if (20 >= col_width) {
                    decrement.style.pointerEvents = 'none';
                } else {
                    decrement.style.pointerEvents = 'auto';
                }
            } else {
                inc_dec_row_buttons(this_row, 'increment', 'none');
            }

        }

        column_btn_event('increment');
        column_btn_event('decrement');
        remove && remove.addEventListener('click', (e) => {
            this_col && this_col.remove();
            reset_col_index(this_row);
        })

    });

}

// reset column
function inc_dec_row_buttons(this_row) {
    this_row && this_row.querySelectorAll('.wpmm-item-col').forEach(item => {
        item.querySelector(`button.${type}`).style.pointerEvents = value;
    });
}

// enable/disable column resizer
function inc_dec_row_buttons(this_row, type, value) {
    this_row && this_row.querySelectorAll('.wpmm-item-col').forEach(item => {
        item.querySelector(`button.${type}`).style.pointerEvents = value;
    });
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

}

function get_menu_item_id() {
    return document.querySelector('.wp-megamenu-item-settins-wrap').dataset.id;
}

/**
 * Select Widget as menu item
 */
function wpmm_delete_this_widget(deleteButton) {
    widget_element = deleteButton.closest('.widget.wpmm-cell');
    // widget_element
    rowId = widget_element.closest('.wpmm-item-col').dataset.rowid;
    colId = widget_element.closest('.wpmm-item-col').dataset.colId;
    // console.log(rowId, colId, widget_element.id);
    formData = new FormData();

    requestData = {
        menu_item_id: get_menu_item_id(),
        wpmm_nonce: wpmm.wpmm_nonce,
        action: "wpmm_delete_this_widget",
        row_id: rowId,
    }
    Object.entries(requestData).forEach(([key, value]) => {
        formData.append(key, value);
    });

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', ajaxurl, true);
    xhttp.send(formData);
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4) {
            // setTimeout(() => {
            console.log(xhttp.response);
            widget_element.remove();
            // }, 1000)
        }
    };

    /* var data = {
        action: 'wpmm_delete_this_widget',
        menu_item_id: get_menu_item_id(),
        row_id: rowId,
        wpmm_nonce: wpmm.wpmm_nonce
    };
    jQuery.post(ajaxurl, data, function (response) {
        if (response.success) {
            console.log(response);
            // button_clicked.closest('.wpmm-layout-row').remove();
        }
    }); */
    // widget_element.remove();
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
            reset_row_index();
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
            reset_row_index();
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
const wpmmSaveNavItemFunction = (form, event) => {
    event.preventDefault();
    saveBtn = document.querySelector(`[form="${form.id}"]`);
    submitForm = true;
    menu_item_id = saveBtn.closest('.wp-megamenu-item-settins-wrap').dataset.id;
    layout_array_new = get_layout_array();
    menu_item_settings = get_nav_item_settings();
    saveBtn.classList.add('wpmm-btn-spinner');

    formData = new FormData(form);

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

/**
 * Image upload action for wpmm item
 */
function wpmm_image_uploader(element = null, title = null, btn_text = null, view = null) {

    var wpmm_media,
        img_element = document.createElement("img"),
        wrapper = element.closest('.wpmm_image_uploader'),
        image_preview = wrapper && wrapper.querySelector('.wpmm_image_preview'),
        input_media = wrapper && wrapper.querySelector('input.upload_image'),
        delete_btn = image_preview && image_preview.querySelector('.delete_image');

    element.addEventListener('click', (e) => {

        if (wpmm_media) {
            wpmm_media.open();
            return;
        }
        wpmm_media = wp.media.frames.file_frame = wp.media({
            title: !title ? 'Choose Image' : title,
            button: {
                text: !btn_text ? 'Choose Image' : btn_text
            },
            multiple: false
        });
        wpmm_media.on('select', function () {
            attachment = wpmm_media.state().get('selection').first().toJSON();
            if (wrapper && wrapper.querySelector('img')) {
                wrapper.querySelector('img').setAttribute("src", attachment.url);
                input_media.value = attachment.id;
            } else {
                img_element.setAttribute("src", attachment.url);
                image_preview && image_preview.prepend(img_element);
                input_media.value = attachment.id;
            }
        });
        wpmm_media.open();
    })

    imageToDelete = delete_btn.previousElementSibling;
    imageToDelete && delete_btn && delete_btn.addEventListener('click', (e) => {
        e.preventDefault();
        input_media.value = '';
        imageToDelete && imageToDelete.remove();
    })

}


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

