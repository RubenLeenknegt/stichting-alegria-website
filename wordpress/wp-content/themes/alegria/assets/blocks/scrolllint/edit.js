import { __ } from '@wordpress/i18n';
import { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title = '', images = [] } = attributes;

	const blockProps = useBlockProps();
	const hasImages = Array.isArray( images ) && images.length > 0;

	const onSelectImages = ( media ) =>
		setAttributes( {
			images: ( media || [] ).map( ( item ) => ( { id: item.id, url: item.url, alt: item.alt || '' } ) ),
		} );

	const removeImage = ( idToRemove ) =>
		setAttributes( { images: images.filter( ( img ) => img.id !== idToRemove ) } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Inhoud', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Titel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Afbeeldingen (verplicht)', 'alegria' ) } initialOpen={ true }>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ onSelectImages }
							allowedTypes={ [ 'image' ] }
							multiple={ true }
							gallery={ true }
							value={ images.map( ( img ) => img.id ) }
							render={ ( { open } ) => (
								<Button onClick={ open } variant="secondary">
									{ hasImages ? __( 'Afbeeldingen bewerken', 'alegria' ) : __( 'Afbeeldingen toevoegen', 'alegria' ) }
								</Button>
							) }
						/>
					</MediaUploadCheck>

					{ hasImages && (
						<div style={ { marginTop: '12px', display: 'grid', gap: '8px' } }>
							{ images.map( ( img ) => (
								<div key={ img.id } style={ { display: 'flex', alignItems: 'center', gap: '8px' } }>
									<PreviewThumb src={ img.url } alt={ img.alt } size={ 40 } />
									<span style={ { fontSize: '12px', flex: 1, overflowWrap: 'anywhere' } }>
										{ img.alt || __( '(geen alternatieve tekst)', 'alegria' ) }
									</span>
									<Button onClick={ () => removeImage( img.id ) } variant="link" isDestructive>
										{ __( 'Verwijderen', 'alegria' ) }
									</Button>
								</div>
							) ) }
						</div>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ hasImages ? (
					<PreviewCard title={ stripHtmlText( title ) || __( 'Afbeeldingenstrook', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<div style={ { display: 'flex', gap: '8px', flexWrap: 'wrap' } }>
							{ images.map( ( img ) => (
								<PreviewThumb key={ img.id } src={ img.url } alt={ img.alt } size={ 56 } />
							) ) }
						</div>
						<PreviewField
							label={ __( 'Weergave', 'alegria' ) }
							value={ __( 'Schuift continu van links naar rechts over de volle breedte, niet klikbaar.', 'alegria' ) }
							maxLines={ 2 }
						/>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen afbeeldingen', 'alegria' ) }
						message={ __( 'Voeg in de zijbalk afbeeldingen toe.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
