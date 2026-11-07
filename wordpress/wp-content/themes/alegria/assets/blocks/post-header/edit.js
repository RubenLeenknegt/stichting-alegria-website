import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import { ImageControl, RichTextField, useMediaUrl } from '../_shared/controls';
import { PreviewCard, PreviewField } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { introText = '', imageId = 0, imageUrl = '', imageAlt = '' } = attributes;

	const blockProps = useBlockProps();

	// Title, date and featured image come from the post itself, as in render.php.
	const { title, postType, date, eventDate, featuredUrl } = useSelect( ( select ) => {
		const editor = select( editorStore );
		const featuredId = editor.getEditedPostAttribute( 'featured_media' );
		return {
			title: editor.getEditedPostAttribute( 'title' ),
			postType: editor.getCurrentPostType(),
			date: editor.getEditedPostAttribute( 'date' ),
			eventDate: editor.getEditedPostAttribute( 'meta' )?.event_date || '',
			featuredUrl: featuredId ? select( coreStore ).getMedia( featuredId )?.source_url || '' : '',
		};
	}, [] );

	const blockImageUrl = useMediaUrl( imageId, imageUrl );
	const previewImage = blockImageUrl || featuredUrl;

	const formatDate = ( value ) =>
		value ? new Date( value ).toLocaleDateString( 'nl-NL', { day: 'numeric', month: 'long', year: 'numeric' } ) : '';
	// Only newsletters and events show a date, as in render.php.
	const hasDate = postType === 'event' || postType === 'newsletter';
	const shownDate =
		postType === 'event'
			? formatDate( eventDate ) || __( 'Nog geen evenementdatum (zie Evenementdetails)', 'alegria' )
			: formatDate( date ) || __( 'Publicatiedatum', 'alegria' );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Paginakop', 'alegria' ) } initialOpen={ true }>
					<RichTextField
						label={ __( 'Introductietekst', 'alegria' ) }
						value={ introText }
						onChange={ ( value ) => setAttributes( { introText: value } ) }
						placeholder={ __( 'Een korte inleiding onder de titel…', 'alegria' ) }
					/>
					<ImageControl
						label={ __( 'Afbeelding (16:9)', 'alegria' ) }
						value={ { id: imageId, url: imageUrl, alt: imageAlt } }
						onChange={ ( image ) => setAttributes( { imageId: image.id, imageUrl: image.url, imageAlt: image.alt } ) }
					/>
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'Zonder afbeelding wordt de uitgelichte afbeelding van het bericht getoond. De titel (en bij nieuwsbrieven en evenementen de datum) komt uit de pagina zelf.', 'alegria' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ __( 'Paginakop', 'alegria' ) } style={ { maxWidth: '780px' } }>
					<PreviewField label={ __( 'Kruimelpad', 'alegria' ) } value={ __( 'Automatisch (Rank Math)', 'alegria' ) } maxLines={ 1 } />
					<PreviewField label={ __( 'Titel', 'alegria' ) } value={ title || __( 'Zonder titel', 'alegria' ) } maxLines={ 2 } />
					{ hasDate ? (
						<PreviewField label={ postType === 'event' ? __( 'Evenementdatum', 'alegria' ) : __( 'Datum', 'alegria' ) } value={ shownDate } maxLines={ 1 } />
					) : null }
					<PreviewField label={ __( 'Introductietekst', 'alegria' ) } value={ introText || __( 'Vul in de zijbalk een introductietekst in.', 'alegria' ) } maxLines={ 3 } />
					{ previewImage ? (
						<img src={ previewImage } alt={ imageAlt } style={ { width: '100%', aspectRatio: '16 / 9', objectFit: 'cover', borderRadius: 4 } } />
					) : (
						<PreviewField label={ __( 'Afbeelding', 'alegria' ) } value={ __( 'Kies een afbeelding in de zijbalk of stel een uitgelichte afbeelding in.', 'alegria' ) } maxLines={ 2 } />
					) }
				</PreviewCard>
			</div>
		</>
	);
}
