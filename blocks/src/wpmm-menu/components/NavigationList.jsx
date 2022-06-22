import React, { useState } from '@wordpress/element';


export const NavigationList = (props) => {

    const [navSlug, setNavSlug] = useState([]);


    const updateNavigation = (e) => {
        props.nav_menus.setAttributes({
            set_nav: e.target.value
        })

        if (props.nav_menus.attributes.set_nav) {
            // console.log(props.nav_menus.attributes.set_nav);
            wp.apiFetch({
                url: '/wp-json/wpmm/nav_menu/' + props.nav_menus.attributes.set_nav
            }).then(nav_item => {
                console.log(nav_item);
                setNavSlug(nav_item)
            })
        }
    }

    const listNavigationItems = () => {
        return (
            <div> Menu Items:
                <ul>
                    {navSlug.map(nav => <li>{nav.title}</li>)}
                </ul>
            </div>
        )
    }

    return (
        <>
            <select onChange={updateNavigation} value={props.nav_menus.attributes.set_nav}>
                {
                    props.nav_menus.attributes.nav_items.map(nav => {
                        return (
                            <option value={nav.slug}>{nav.name}</option>
                        )
                    })
                }
            </select>
            {listNavigationItems()}
        </>
    )
}