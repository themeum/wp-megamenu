import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';

registerBlockType('gutenberg-examples/example-dynamic', {
    apiVersion: 2,
    title: 'Example: last post',
    icon: 'megaphone',
    category: 'widgets',

    edit: () => {
        const blockProps = useBlockProps();
        const posts = useSelect((select) => {
            return select('core').getEntityRecords('postType', 'post');
        }, []);

        return (
            <div {...blockProps}>
                {!posts && 'Loading'}
                {posts && posts.length === 0 && 'No Posts'}
                {posts && posts.length > 0 && (
                    <a href={posts[0].link}>
                        {posts[0].title.rendered}
                    </a>
                )}
            </div>
        );
    },
});