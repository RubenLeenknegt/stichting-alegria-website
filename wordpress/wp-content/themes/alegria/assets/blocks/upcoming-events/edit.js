import { __, sprintf } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl } from '@wordpress/components';
import { PreviewCard, PreviewField, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title, maxEvents } = attributes;

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'bijv. Aankomende evenementen', 'alegria' ) }
					/>
					<RangeControl
						label={ __( 'Maximaal aantal evenementen', 'alegria' ) }
						help={ __( 'Op de site worden alleen aankomende evenementen getoond, de eerstvolgende eerst.', 'alegria' ) }
						value={ maxEvents }
						onChange={ ( value ) => setAttributes( { maxEvents: value } ) }
						min={ 1 }
						max={ 10 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Aankomende evenementen', 'alegria' ) } style={ { maxWidth: '780px' } }>
					<PreviewField
						label={ __( 'Selectie', 'alegria' ) }
						value={ sprintf( __( 'Maximaal %d aankomende evenementen, de eerstvolgende eerst. Zonder aankomende evenementen wordt dit blok niet getoond.', 'alegria' ), maxEvents ) }
						maxLines={ 2 }
					/>
				</PreviewCard>
			</div>
		</>
	);
}
