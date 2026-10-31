import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { ImageControl, LinkField, RichTextField, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

// The block's three images, by attribute prefix.
const IMAGES = [
	{ key: 'topLeft', label: __( 'Afbeelding linksboven', 'alegria' ) },
	{ key: 'topRight', label: __( 'Afbeelding rechtsboven', 'alegria' ) },
	{ key: 'bottom', label: __( 'Brede afbeelding onder', 'alegria' ) },
];

export default function Edit( { attributes, setAttributes } ) {
	const { preTitle, title, textBlock1, subtitle, textBlock2, buttonText, buttonUrl } = attributes;
	const image = ( key ) => ( {
		id: attributes[ `${ key }ImageId` ],
		url: attributes[ `${ key }ImageUrl` ],
		alt: attributes[ `${ key }ImageAlt` ],
	} );

	const blockProps = useBlockProps();
	const previewUrls = [
		useMediaUrl( attributes.topLeftImageId, attributes.topLeftImageUrl ),
		useMediaUrl( attributes.topRightImageId, attributes.topRightImageUrl ),
		useMediaUrl( attributes.bottomImageId, attributes.bottomImageUrl ),
	];
	const hasContent = preTitle || title || textBlock1 || subtitle || textBlock2 || buttonText || buttonUrl || previewUrls.some( Boolean );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Afbeeldingen', 'alegria' ) } initialOpen={ true }>
					{ IMAGES.map( ( { key, label } ) => (
						<ImageControl
							key={ key }
							label={ label }
							value={ image( key ) }
							onChange={ ( { id, url, alt } ) =>
								setAttributes( { [ `${ key }ImageId` ]: id, [ `${ key }ImageUrl` ]: url, [ `${ key }ImageAlt` ]: alt } )
							}
						/>
					) ) }
				</PanelBody>

				<PanelBody title={ __( 'Inhoud', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Bovenkop (kleine tekst boven de titel)', 'alegria' ) }
						value={ preTitle }
						onChange={ ( value ) => setAttributes( { preTitle: value } ) }
						placeholder={ __( 'bijv. Wie wij zijn', 'alegria' ) }
					/>
					<TextControl
						label={ __( 'Titel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'bijv. Over Stichting Alegria', 'alegria' ) }
					/>
					<RichTextField
						label={ __( 'Eerste tekstblok', 'alegria' ) }
						value={ textBlock1 }
						onChange={ ( value ) => setAttributes( { textBlock1: value } ) }
						placeholder={ __( 'Typ hier de tekst…', 'alegria' ) }
					/>
					<TextControl
						label={ __( 'Tussenkop', 'alegria' ) }
						value={ subtitle }
						onChange={ ( value ) => setAttributes( { subtitle: value } ) }
						placeholder={ __( 'bijv. Onze missie', 'alegria' ) }
					/>
					<RichTextField
						label={ __( 'Tweede tekstblok', 'alegria' ) }
						value={ textBlock2 }
						onChange={ ( value ) => setAttributes( { textBlock2: value } ) }
						placeholder={ __( 'Typ hier de tekst…', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Knop', 'alegria' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Knoptekst', 'alegria' ) }
						value={ buttonText }
						onChange={ ( value ) => setAttributes( { buttonText: value } ) }
						placeholder={ __( 'bijv. Lees meer', 'alegria' ) }
					/>
					<LinkField url={ buttonUrl } onChange={ ( url ) => setAttributes( { buttonUrl: url } ) } />
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ hasContent ? (
					<PreviewCard title={ stripHtmlText( title ) || __( 'Over ons', 'alegria' ) } style={ { maxWidth: '780px' } }>
						<div style={ { display: 'grid', gap: '10px' } }>
							{ preTitle ? <PreviewField label={ __( 'Bovenkop', 'alegria' ) } value={ preTitle } maxLines={ 1 } /> : null }
							{ textBlock1 ? <PreviewField label={ __( 'Eerste tekstblok', 'alegria' ) } value={ textBlock1 } maxLines={ 3 } /> : null }
							{ subtitle ? <PreviewField label={ __( 'Tussenkop', 'alegria' ) } value={ subtitle } maxLines={ 1 } /> : null }
							{ textBlock2 ? <PreviewField label={ __( 'Tweede tekstblok', 'alegria' ) } value={ textBlock2 } maxLines={ 3 } /> : null }
							{ buttonText ? <PreviewField label={ __( 'Knop', 'alegria' ) } value={ buttonText } maxLines={ 1 } /> : null }
							{ buttonUrl ? <PreviewField label={ __( 'Link', 'alegria' ) } value={ buttonUrl } maxLines={ 1 } /> : null }
							<div style={ { display: 'flex', gap: '8px', flexWrap: 'wrap' } }>
								{ previewUrls.map( ( src, i ) => ( src ? <PreviewThumb key={ IMAGES[ i ].key } src={ src } alt={ image( IMAGES[ i ].key ).alt } size={ 48 } /> : null ) ) }
							</div>
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen inhoud', 'alegria' ) }
						message={ __( 'Vul in de zijbalk een titel, tekst of afbeeldingen in.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
