import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { LinkField, RichTextField } from '../_shared/controls';
import { PreviewCard, PreviewField, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title, bodyText, linkText, linkUrl } = attributes;

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'bijv. Laatste nieuwsbrieven', 'alegria' ) }
					/>
					<RichTextField
						label={ __( 'Tekst', 'alegria' ) }
						value={ bodyText }
						onChange={ ( value ) => setAttributes( { bodyText: value } ) }
						placeholder={ __( 'Typ hier de tekst onder de titel…', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Knop', 'alegria' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Knoptekst', 'alegria' ) }
						value={ linkText }
						onChange={ ( value ) => setAttributes( { linkText: value } ) }
						placeholder={ __( 'bijv. Alle nieuwsbrieven', 'alegria' ) }
					/>
					<LinkField url={ linkUrl } onChange={ ( url ) => setAttributes( { linkUrl: url } ) } />
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'De knop staat rechts naast de titel. Zonder link gaat hij naar het nieuwsbrievenoverzicht.', 'alegria' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Nieuwsbrieven', 'alegria' ) } style={ { maxWidth: '780px' } }>
					{ bodyText ? <PreviewField label={ __( 'Tekst', 'alegria' ) } value={ bodyText } maxLines={ 3 } /> : null }
					{ linkText ? <PreviewField label={ __( 'Knop', 'alegria' ) } value={ linkText } maxLines={ 1 } /> : null }
					{ linkUrl ? <PreviewField label={ __( 'Link', 'alegria' ) } value={ linkUrl } maxLines={ 1 } /> : null }
					<PreviewField label={ __( 'Selectie', 'alegria' ) } value={ __( 'De drie nieuwste nieuwsbrieven.', 'alegria' ) } maxLines={ 1 } />
				</PreviewCard>
			</div>
		</>
	);
}
