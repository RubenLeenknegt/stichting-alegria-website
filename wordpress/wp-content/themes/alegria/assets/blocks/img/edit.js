import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { ImageControl, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewThumb, PreviewEmptyState } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { imageUrl = '', imageId = 0, imageAlt = '' } = attributes;

	const blockProps = useBlockProps();
	const previewUrl = useMediaUrl( imageId, imageUrl );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Afbeelding (verplicht)', 'alegria' ) } initialOpen={ true }>
					<ImageControl
						value={ { id: imageId, url: imageUrl, alt: imageAlt } }
						onChange={ ( { id, url, alt } ) => setAttributes( { imageId: id, imageUrl: url, imageAlt: alt } ) }
					/>
					{ previewUrl && (
						<TextControl
							label={ __( 'Alternatieve tekst', 'alegria' ) }
							help={ __( 'Beschrijf de afbeelding voor bezoekers die hem niet kunnen zien. Leeg laten gebruikt de tekst uit de mediabibliotheek.', 'alegria' ) }
							value={ imageAlt }
							onChange={ ( value ) => setAttributes( { imageAlt: value } ) }
						/>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ previewUrl ? (
					<PreviewCard title={ __( 'Afbeelding', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<PreviewThumb src={ previewUrl } alt={ imageAlt } size={ 96 } />
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen afbeelding', 'alegria' ) }
						message={ __( 'Kies in de zijbalk een afbeelding.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
