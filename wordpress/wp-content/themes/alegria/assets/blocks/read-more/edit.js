import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { LinkField } from '../_shared/controls';
import { PreviewCard, PreviewField, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes, context } ) {
	const { title, linkText, linkUrl } = attributes;
	const isEvent = context?.postType === 'event';

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ isEvent ? __( 'bijv. Meer evenementen', 'alegria' ) : __( 'bijv. Meer nieuwsbrieven', 'alegria' ) }
					/>
					<TextControl
						label={ __( 'Linktekst', 'alegria' ) }
						value={ linkText }
						onChange={ ( value ) => setAttributes( { linkText: value } ) }
						placeholder={ isEvent ? __( 'bijv. Alle evenementen', 'alegria' ) : __( 'bijv. Alle nieuwsbrieven', 'alegria' ) }
					/>
					<LinkField url={ linkUrl } onChange={ ( url ) => setAttributes( { linkUrl: url } ) } />
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'Zonder link gaat de linktekst naar het overzicht.', 'alegria' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Lees verder', 'alegria' ) } style={ { maxWidth: '780px' } }>
					{ linkText ? <PreviewField label={ __( 'Linktekst', 'alegria' ) } value={ linkText } maxLines={ 1 } /> : null }
					<PreviewField
						label={ __( 'Selectie', 'alegria' ) }
						value={
							isEvent
								? __( 'Drie andere evenementen: eerst de aankomende, aangevuld met eerdere.', 'alegria' )
								: __( 'De drie nieuwste andere nieuwsbrieven.', 'alegria' )
						}
						maxLines={ 2 }
					/>
				</PreviewCard>
			</div>
		</>
	);
}
