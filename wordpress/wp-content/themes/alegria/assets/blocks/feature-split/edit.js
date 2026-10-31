import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { ImageControl, LinkField, RichTextField, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title, bodyText, imageUrl, imageId, imageAlt, ctaText, ctaUrl } = attributes;

	const blockProps = useBlockProps();
	const previewImageUrl = useMediaUrl( imageId, imageUrl );

	// render.php outputs nothing unless the image and the complete call to action are set.
	const missingRequired = [
		! previewImageUrl ? __( 'afbeelding', 'alegria' ) : null,
		! ctaText ? __( 'knoptekst', 'alegria' ) : null,
		! ctaUrl ? __( 'knoplink', 'alegria' ) : null,
	].filter( Boolean );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Inhoud', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Titel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
					/>
					<RichTextField
						label={ __( 'Tekst', 'alegria' ) }
						value={ bodyText }
						onChange={ ( value ) => setAttributes( { bodyText: value } ) }
						placeholder={ __( 'Typ hier de tekst…', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Afbeelding (verplicht)', 'alegria' ) } initialOpen={ true }>
					<ImageControl
						value={ { id: imageId, url: imageUrl, alt: imageAlt } }
						onChange={ ( { id, url, alt } ) => setAttributes( { imageId: id, imageUrl: url, imageAlt: alt } ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Knop (verplicht)', 'alegria' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Knoptekst', 'alegria' ) }
						value={ ctaText }
						onChange={ ( value ) => setAttributes( { ctaText: value } ) }
						placeholder={ __( 'bijv. Lees meer', 'alegria' ) }
					/>
					<LinkField url={ ctaUrl } onChange={ ( url ) => setAttributes( { ctaUrl: url } ) } />
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ missingRequired.length === 0 ? (
					<PreviewCard title={ stripHtmlText( title ) || __( 'Tekst met afbeelding', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<div style={ { display: 'flex', gap: '12px', alignItems: 'flex-start' } }>
							<PreviewThumb src={ previewImageUrl } alt={ imageAlt } size={ 72 } />
							<div style={ { flex: 1, minWidth: 0, display: 'grid', gap: '8px' } }>
								{ bodyText ? <PreviewField label={ __( 'Tekst', 'alegria' ) } value={ bodyText } maxLines={ 3 } /> : null }
								<PreviewField label={ __( 'Knop', 'alegria' ) } value={ ctaText } maxLines={ 1 } />
								<PreviewField label={ __( 'Link', 'alegria' ) } value={ ctaUrl } maxLines={ 1 } />
							</div>
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog niet compleet', 'alegria' ) }
						message={ __( 'Dit blok verschijnt pas op de site als dit is ingevuld:', 'alegria' ) + ' ' + missingRequired.join( ', ' ) }
					/>
				) }
			</div>
		</>
	);
}
