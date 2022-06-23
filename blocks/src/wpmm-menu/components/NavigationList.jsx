import React, { useState, useEffect } from '@wordpress/element';


export const NavigationList = (props) => {

    const [navSlug, setNavSlug] = useState([]);
    useEffect(() => {
        if (props.menu_list.attributes.set_nav) {
            wp.apiFetch({
                url: '/wp-json/wpmm/nav_menu/' + props.menu_list.attributes.set_nav
            }).then(nav_item => {
                setNavSlug(nav_item)
            })
        }
    }, []);

    const updateNavigation = (e) => {
        props.menu_list.setAttributes({
            set_nav: e.target.value
        })

        wp.apiFetch({
            url: '/wp-json/wpmm/nav_menu/' + e.target.value
        }).then(nav_item => {
            setNavSlug(nav_item)
        });
    }

    const listNavigationItems = () => {

        if (!navSlug) return;
        console.log(navSlug);
        return (
            <div>
                <ul className='wpmm_list_menu'>
                    {
                        navSlug.map(nav => {
                            return (
                                <li className={nav.is_wpmm ? 'is_wpmm' : ''}>
                                    <a href='{nav.post_url}'>{nav.title}</a>
                                </li>
                            )
                        })
                    }
                </ul>
            </div>
        )
    }

    return (
        <>
            <select onChange={updateNavigation} value={props.menu_list.attributes.set_nav}>
                <option value='-'>Select Navigation</option>
                {
                    props.menu_list.attributes.nav_items.map(nav => {
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