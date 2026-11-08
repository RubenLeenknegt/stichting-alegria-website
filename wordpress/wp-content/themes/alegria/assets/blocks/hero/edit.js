import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { ImageControl, LinkField, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const {
		heading,
		subheading,
		backgroundImageUrl,
		backgroundImageId,
		backgroundImageAlt,
		primaryButtonText,
		primaryButtonUrl,
		secondaryButtonText,
		secondaryButtonUrl,
	} = attributes;

	const blockProps = useBlockProps();
	const previewImageUrl = useMediaUrl( backgroundImageId, backgroundImageUrl );
	const hasContent = heading || subheading || previewImageUrl || primaryButtonText || secondaryButtonText || primaryButtonUrl || secondaryButtonUrl;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Inhoud', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Kop', 'alegria' ) }
						value={ heading }
						onChange={ ( value ) => setAttributes( { heading: value } ) }
					/>
					<TextareaControl
						label={ __( 'Subkop', 'alegria' ) }
						value={ subheading }
						onChange={ ( value ) => setAttributes( { subheading: value } ) }
						rows={ 3 }
						help={ __( 'Korte tekst onder de kop.', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Knoppen', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Tekst primaire knop', 'alegria' ) }
						value={ primaryButtonText }
						onChange={ ( value ) => setAttributes( { primaryButtonText: value } ) }
						placeholder={ __( 'bijv. Doneer nu', 'alegria' ) }
					/>
					<LinkField url={ primaryButtonUrl } onChange={ ( url ) => setAttributes( { primaryButtonUrl: url } ) } />

					<TextControl
						label={ __( 'Tekst secundaire knop', 'alegria' ) }
						value={ secondaryButtonText }
						onChange={ ( value ) => setAttributes( { secondaryButtonText: value } ) }
						placeholder={ __( 'bijv. Lees meer', 'alegria' ) }
					/>
					<LinkField url={ secondaryButtonUrl } onChange={ ( url ) => setAttributes( { secondaryButtonUrl: url } ) } />
				</PanelBody>

				<PanelBody title={ __( 'Achtergrondafbeelding', 'alegria' ) } initialOpen={ false }>
					<ImageControl
						value={ { id: backgroundImageId, url: backgroundImageUrl, alt: backgroundImageAlt } }
						onChange={ ( { id, url, alt } ) => setAttributes( { backgroundImageId: id, backgroundImageUrl: url, backgroundImageAlt: alt } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ hasContent ? (
					<PreviewCard title={ stripHtmlText( heading ) || __( 'Hero', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<div style={ { display: 'flex', gap: '12px', alignItems: 'flex-start' } }>
							{ previewImageUrl ? <PreviewThumb src={ previewImageUrl } alt={ backgroundImageAlt } size={ 72 } /> : null }
							<div style={ { flex: 1, minWidth: 0, display: 'grid', gap: '8px' } }>
								{ subheading ? <PreviewField label={ __( 'Subkop', 'alegria' ) } value={ subheading } maxLines={ 3 } /> : null }
								{ primaryButtonText ? <PreviewField label={ __( 'Primaire knop', 'alegria' ) } value={ primaryButtonText } maxLines={ 1 } /> : null }
								{ secondaryButtonText ? <PreviewField label={ __( 'Secundaire knop', 'alegria' ) } value={ secondaryButtonText } maxLines={ 1 } /> : null }
								{ primaryButtonUrl ? <PreviewField label={ __( 'Primaire link', 'alegria' ) } value={ primaryButtonUrl } maxLines={ 1 } /> : null }
								{ secondaryButtonUrl ? <PreviewField label={ __( 'Secundaire link', 'alegria' ) } value={ secondaryButtonUrl } maxLines={ 1 } /> : null }
							</div>
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen inhoud', 'alegria' ) }
						message={ __( 'Vul in de zijbalk een kop, tekst of afbeelding in.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
