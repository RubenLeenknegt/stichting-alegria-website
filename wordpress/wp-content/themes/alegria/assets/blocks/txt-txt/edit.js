import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { RichTextField } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title = '', leftText = '', rightText = '' } = attributes;

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
				</PanelBody>

				<PanelBody title={ __( 'Tekst links', 'alegria' ) } initialOpen={ true }>
					<RichTextField
						label={ __( 'Tekst links', 'alegria' ) }
						value={ leftText }
						onChange={ ( value ) => setAttributes( { leftText: value } ) }
						placeholder={ __( 'Typ hier de linker tekst…', 'alegria' ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Tekst rechts', 'alegria' ) } initialOpen={ true }>
					<RichTextField
						label={ __( 'Tekst rechts', 'alegria' ) }
						value={ rightText }
						onChange={ ( value ) => setAttributes( { rightText: value } ) }
						placeholder={ __( 'Typ hier de rechter tekst…', 'alegria' ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ title || leftText || rightText ? (
					<PreviewCard title={ stripHtmlText( title ) || __( 'Tekst - Tekst', 'alegria' ) } style={ { maxWidth: '680px' } }>
						<div style={ { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', alignItems: 'start' } }>
							<PreviewField label={ __( 'Tekst links', 'alegria' ) } value={ leftText || '—' } maxLines={ 4 } />
							<PreviewField label={ __( 'Tekst rechts', 'alegria' ) } value={ rightText || '—' } maxLines={ 4 } />
						</div>
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
