import { __ } from '@wordpress/i18n';
import {
	MediaUpload,
	MediaUploadCheck,
	RichText,
	__experimentalLinkControl as LinkControl,
} from '@wordpress/block-editor';
import { BaseControl, Button, Popover } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useState } from '@wordpress/element';

const hintStyle = { marginTop: '4px', fontSize: '12px', color: '#666', overflowWrap: 'anywhere' };

/**
 * Returns a URL to preview an image: the stored URL, or the media library URL for an ID.
 *
 * @param {number} id  Attachment ID.
 * @param {string} url Stored URL.
 * @return {string} URL, or an empty string while loading or when there is no image.
 */
export function useMediaUrl( id, url ) {
	return useSelect(
		( select ) => {
			if ( url ) {
				return url;
			}
			return id ? select( coreStore ).getMedia( id )?.source_url || '' : '';
		},
		[ id, url ]
	);
}

/**
 * Pick, replace or remove one image.
 *
 * @param {Object}   props
 * @param {string}   props.label    Field label.
 * @param {Object}   props.value    { id, url, alt } of the current image.
 * @param {Function} props.onChange Called with { id, url, alt }, or empty values when removed.
 */
export function ImageControl( { label, value, onChange } ) {
	const { id = 0, url = '', alt = '' } = value;
	const previewUrl = useMediaUrl( id, url );

	return (
		<BaseControl label={ label } __nextHasNoMarginBottom>
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={ [ 'image' ] }
					value={ id }
					onSelect={ ( media ) => onChange( { id: media.id, url: media.url, alt: media.alt || '' } ) }
					render={ ( { open } ) => (
						<div style={ { marginBottom: '16px' } }>
							{ previewUrl && (
								<img src={ previewUrl } alt={ alt } style={ { maxWidth: '100%', height: 'auto', marginBottom: '8px' } } />
							) }
							<Button onClick={ open } variant="secondary">
								{ previewUrl ? __( 'Afbeelding vervangen', 'alegria' ) : __( 'Afbeelding kiezen', 'alegria' ) }
							</Button>
							{ previewUrl && (
								<Button
									onClick={ () => onChange( { id: 0, url: '', alt: '' } ) }
									variant="link"
									isDestructive
									style={ { marginLeft: '8px' } }
								>
									{ __( 'Verwijderen', 'alegria' ) }
								</Button>
							) }
						</div>
					) }
				/>
			</MediaUploadCheck>
		</BaseControl>
	);
}

/**
 * Set or remove a link through the editor's link picker.
 *
 * @param {Object}   props
 * @param {string}   props.label    Button label when no link is set.
 * @param {string}   props.url      Current URL.
 * @param {Function} props.onChange Called with the new URL, or '' when removed.
 */
export function LinkField( { label = __( 'Link instellen', 'alegria' ), url, onChange } ) {
	const [ isOpen, setIsOpen ] = useState( false );

	return (
		<div style={ { marginBottom: '16px' } }>
			<Button onClick={ () => setIsOpen( ! isOpen ) } variant="secondary">
				{ url ? __( 'Link wijzigen', 'alegria' ) : label }
			</Button>
			{ url && <div style={ hintStyle }>{ url }</div> }
			{ isOpen && (
				<Popover placement="bottom" onClose={ () => setIsOpen( false ) }>
					<div style={ { width: '300px' } }>
						<LinkControl
							value={ { url } }
							onChange={ ( next ) => onChange( next?.url || '' ) }
							onRemove={ () => {
								onChange( '' );
								setIsOpen( false );
							} }
						/>
					</div>
				</Popover>
			) }
		</div>
	);
}

/**
 * A labelled rich text box for the sidebar (bold, italic and links).
 *
 * @param {Object}   props
 * @param {string}   props.label       Field label.
 * @param {string}   props.value       Current HTML.
 * @param {Function} props.onChange    Called with the new HTML.
 * @param {string}   props.placeholder Placeholder text.
 */
export function RichTextField( { label, value, onChange, placeholder } ) {
	return (
		<BaseControl label={ label } __nextHasNoMarginBottom>
			<div style={ { border: '1px solid #ddd', padding: '12px', borderRadius: '4px', background: '#fff', marginBottom: '4px' } }>
				<RichText
					tagName="div"
					value={ value }
					onChange={ onChange }
					placeholder={ placeholder }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
				/>
			</div>
			<p style={ { ...hintStyle, marginBottom: '16px' } }>{ __( 'Selecteer tekst om deze vet, cursief of als link op te maken.', 'alegria' ) }</p>
		</BaseControl>
	);
}
