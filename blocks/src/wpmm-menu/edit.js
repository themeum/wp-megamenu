/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import React, { useEffect } from '@wordpress/element';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, AlignmentToolbar, BlockControls, InspectorControls, ColorPalette } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
import { NavigationList } from './components/NavigationList';

export default function MenuEdit(props) {
	return (
		<div {...useBlockProps()}>
			<BlockControls>
				<AlignmentToolbar
					value={props.attributes.alignment}
				/>
			</BlockControls>
			<InspectorControls key="setting">
				<div className='block-editor-block-inspector'>
					<div id="gutenpride-controls" className='components-panel__body'>
						<fieldset>
							<legend className="blocks-base-control__label">
								{__('Background color', 'gutenpride')}
							</legend>
							<ColorPalette />
						</fieldset>
						<fieldset>
							<legend className="blocks-base-control__label">
								{__('Text color', 'gutenpride')}
							</legend>
							<ColorPalette />
						</fieldset>
					</div>
				</div>
			</InspectorControls>
			<NavigationList {...props} />
		</div>
	);
}
