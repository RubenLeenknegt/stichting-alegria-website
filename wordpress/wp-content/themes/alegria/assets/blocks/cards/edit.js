import { __, sprintf } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { ImageControl, LinkField, RichTextField, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

// Each card's attributes are stored as card1Title, card1Url, card2Title, ...
const CARD_NUMBERS = [ 1, 2, 3 ];

export default function Edit( { attributes, setAttributes } ) {
	const { sectionTitle, sectionText, ctaText, ctaUrl } = attributes;
	const card = ( n ) => ( {
		imageId: attributes[ `card${ n }ImageId` ],
		imageUrl: attributes[ `card${ n }ImageUrl` ],
		imageAlt: attributes[ `card${ n }ImageAlt` ],
		title: attributes[ `card${ n }Title` ],
		text: attributes[ `card${ n }Text` ],
		url: attributes[ `card${ n }Url` ],
	} );
	const setCard = ( n, field, value ) => setAttributes( { [ `card${ n }${ field }` ]: value } );

	const blockProps = useBlockProps();
	const previewUrls = [
		useMediaUrl( attributes.card1ImageId, attributes.card1ImageUrl ),
		useMediaUrl( attributes.card2ImageId, attributes.card2ImageUrl ),
		useMediaUrl( attributes.card3ImageId, attributes.card3ImageUrl ),
	];
	const cards = CARD_NUMBERS.map( ( n ) => ( { n, ...card( n ), previewUrl: previewUrls[ n - 1 ] } ) );
	const hasContent = sectionTitle || sectionText || ctaText || cards.some( ( c ) => c.title || c.text || c.url || c.previewUrl );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ sectionTitle }
						onChange={ ( value ) => setAttributes( { sectionTitle: value } ) }
						placeholder={ __( 'bijv. Onze projecten', 'alegria' ) }
					/>
					<RichTextField
						label={ __( 'Tekst', 'alegria' ) }
						value={ sectionText }
						onChange={ ( value ) => setAttributes( { sectionText: value } ) }
						placeholder={ __( 'Typ hier de tekst onder de titel…', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Knop', 'alegria' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Knoptekst', 'alegria' ) }
						value={ ctaText }
						onChange={ ( value ) => setAttributes( { ctaText: value } ) }
						placeholder={ __( 'bijv. Bekijk alle projecten', 'alegria' ) }
					/>
					<LinkField url={ ctaUrl } onChange={ ( url ) => setAttributes( { ctaUrl: url } ) } />
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'De knop staat rechts naast de titel en verschijnt alleen als tekst en link allebei zijn ingevuld.', 'alegria' ) }
					</p>
				</PanelBody>

				{ cards.map( ( c ) => (
					/* translators: %d: card number */
					<PanelBody key={ c.n } title={ sprintf( __( 'Kaart %d', 'alegria' ), c.n ) } initialOpen={ c.n === 1 }>
						<ImageControl
							label={ __( 'Achtergrondafbeelding', 'alegria' ) }
							value={ { id: c.imageId, url: c.imageUrl, alt: c.imageAlt } }
							onChange={ ( { id, url, alt } ) =>
								setAttributes( { [ `card${ c.n }ImageId` ]: id, [ `card${ c.n }ImageUrl` ]: url, [ `card${ c.n }ImageAlt` ]: alt } )
							}
						/>
						<TextControl
							label={ __( 'Titel', 'alegria' ) }
							value={ c.title }
							onChange={ ( value ) => setCard( c.n, 'Title', value ) }
						/>
						<RichTextField
							label={ __( 'Tekst', 'alegria' ) }
							value={ c.text }
							onChange={ ( value ) => setCard( c.n, 'Text', value ) }
							placeholder={ __( 'Typ hier de tekst…', 'alegria' ) }
						/>
						<LinkField url={ c.url } onChange={ ( url ) => setCard( c.n, 'Url', url ) } />
					</PanelBody>
				) ) }
			</InspectorControls>

			<div { ...blockProps }>
				{ hasContent ? (
					<PreviewCard title={ stripHtmlText( sectionTitle ) || __( 'Kaarten', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<div style={ { display: 'grid', gap: '12px' } }>
							{ sectionText ? <PreviewField label={ __( 'Tekst', 'alegria' ) } value={ sectionText } maxLines={ 3 } /> : null }
							{ ctaText ? <PreviewField label={ __( 'Knop', 'alegria' ) } value={ ctaUrl ? ctaText : ctaText + ' ' + __( '(nog zonder link)', 'alegria' ) } maxLines={ 1 } /> : null }
							{ cards
								.filter( ( c ) => c.title || c.text || c.url || c.previewUrl )
								.map( ( c, index ) => (
									<div
										key={ c.n }
										style={ { display: 'flex', gap: '12px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' } }
									>
										<PreviewThumb src={ c.previewUrl } alt={ c.imageAlt } size={ 48 } />
										<div style={ { flex: 1, minWidth: 0 } }>
											<PreviewField label={ __( 'Titel', 'alegria' ) } value={ c.title || __( 'Kaart zonder titel', 'alegria' ) } maxLines={ 1 } />
											{ c.text ? <PreviewField label={ __( 'Tekst', 'alegria' ) } value={ c.text } maxLines={ 2 } /> : null }
											{ c.url ? <PreviewField label={ __( 'Link', 'alegria' ) } value={ c.url } maxLines={ 1 } /> : null }
										</div>
									</div>
								) ) }
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen kaarten', 'alegria' ) }
						message={ __( 'Vul in de zijbalk een sectietitel of de inhoud van een kaart in.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
