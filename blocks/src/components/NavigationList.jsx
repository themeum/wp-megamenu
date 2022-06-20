import React from '@wordpress/element';
import { SelectControl, menuItem } from '@wordpress/components';



// const args = [ 'postType', 'wp_navigation', { per_page: -1, status: 'publish' } ];
// const navigationMenu = getEntityRecord(...args);


export const NavigationList = () => {
    return (
        <>
            <SelectControl
                label="Size"
                options={[
                    { label: 'Big', value: '100%' },
                    { label: 'Medium', value: '50%' },
                    { label: 'Small', value: '25%' },
                ]}
                onChange={(newSize) => setSize(newSize)}
                __nextHasNoMarginBottom
            />
        </>
    )
}