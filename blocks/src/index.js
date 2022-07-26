import { registerBlockType } from '@wordpress/blocks';

import BlockEdit from './wpmm-block/edit';
import BlockSave from './wpmm-block/save';
import MenuEdit from './wpmm-menu/edit';
import MenuSave from './wpmm-menu/save';

import './style.scss';


registerBlockType('wp-megamenu/wpmm-menu', {
    attributes: {
        set_nav: {
            type: 'string'
        },
        nav_items: {
            type: 'object'
        },
        alignment: {
            type: 'string',
            default: 'none',
        },
    },
    title: 'WPMM Menu',
    edit: MenuEdit,
    save: MenuSave
});


registerBlockType('wp-megamenu/wpmm-block', {
    title: 'WPMM Block',
    edit: BlockEdit,
    save: BlockSave
});
