import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';
import { RichTextField } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title = '', titleTag = 'h2', bodyText = '' } = attributes;

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Inhoud', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Titel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
					/>
					<SelectControl
						label={ __( 'Niveau van de titel', 'alegria' ) }
						value={ titleTag }
						options={ [
							{ label: __( 'H2 (sectiekop)', 'alegria' ), value: 'h2' },
							{ label: __( 'H3 (subkop)', 'alegria' ), value: 'h3' },
						] }
						onChange={ ( value ) => setAttributes( { titleTag: value } ) }
					/>
					<RichTextField
						label={ __( 'Tekst', 'alegria' ) }
						value={ bodyText }
						onChange={ ( value ) => setAttributes( { bodyText: value } ) }
						placeholder={ __( 'Typ hier de tekst…', 'alegria' ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ title || bodyText ? (
					<PreviewCard title={ stripHtmlText( title ) || __( 'Tekst', 'alegria' ) } style={ { maxWidth: '680px' } }>
						{ bodyText ? <PreviewField label={ __( 'Tekst', 'alegria' ) } value={ bodyText } maxLines={ 3 } /> : null }
						<PreviewField label={ __( 'Niveau van de titel', 'alegria' ) } value={ titleTag.toUpperCase() } maxLines={ 1 } />
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={ __( 'Nog geen inhoud', 'alegria' ) }
						message={ __( 'Vul in de zijbalk een titel of tekst in.', 'alegria' ) }
					/>
				) }
			</div>
		</>
	);
}
