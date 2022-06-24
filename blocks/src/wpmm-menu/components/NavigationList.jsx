import React, { useState, useEffect } from '@wordpress/element';


export const NavigationList = (props) => {

    const [navSlug, setNavSlug] = useState([]);
    const [navAreas, setNavAreas] = useState(['-']);

    useEffect(() => {
        console.log(props);
        let reqUrl = props.attributes.set_nav ? props.attributes.set_nav : '-';
        fetchApiData('/wp-json/wpmm/nav_menus', setNavAreas);
        fetchApiData(`/wp-json/wpmm/nav_menu/${reqUrl}`, setNavSlug);
    }, []);


    const fetchApiData = (reqUrl, callback) => {
        console.log(reqUrl);
        wp.apiFetch({ url: reqUrl }).then(data => { callback(data); })
    }


    const updateNavigation = (e) => {
        props.setAttributes({
            set_nav: e.target.value
        })
        fetchApiData(`/wp-json/wpmm/nav_menu/${e.target.value}`, setNavSlug);
    }

    const listNavigationItems = () => {

        if (!navSlug) return;

        return (
            <div>
                <ul className='wpmm_list_menu'>
                    {
                        navSlug.map(nav => {
                            return (
                                <li className={nav.is_wpmm ? 'is_wpmm' : ''}>
                                    {nav.is_wpmm ? nav.title : <a href='{nav.post_url}'>{nav.title}</a>}
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
            <select onChange={updateNavigation} value={props.attributes.set_nav}>
                <option value='-'>Select Navigation</option>
                {
                    navAreas.map(nav => {
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