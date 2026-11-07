import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { RichTextField } from '../_shared/controls';
import { PreviewCard, PreviewField, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title = '', introText = '', bodyText = '' } = attributes;

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
					<RichTextField
						label={ __( 'Eerste tekst', 'alegria' ) }
						value={ introText }
						onChange={ ( value ) => setAttributes( { introText: value } ) }
						placeholder={ __( 'Typ hier de eerste tekst…', 'alegria' ) }
					/>
					<RichTextField
						label={ __( 'Tweede tekst', 'alegria' ) }
						value={ bodyText }
						onChange={ ( value ) => setAttributes( { bodyText: value } ) }
						placeholder={ __( 'Typ hier de tweede tekst…', 'alegria' ) }
					/>
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'De sociale media links komen uit Weergave → Customizer → Footer. Het formulier zelf beheer je onder Contact → Contactformulieren ("Contact formulier").', 'alegria' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Contactformulier', 'alegria' ) } style={ { maxWidth: '680px' } }>
					<div style={ { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' } }>
						<div style={ { display: 'flex', flexDirection: 'column', gap: '10px' } }>
							<PreviewField label={ __( 'Eerste tekst', 'alegria' ) } value={ introText } maxLines={ 3 } />
							<PreviewField label={ __( 'Tweede tekst', 'alegria' ) } value={ bodyText } maxLines={ 3 } />
							<PreviewField label={ __( 'Sociale media', 'alegria' ) } value={ __( 'Uit de Customizer (Footer)', 'alegria' ) } maxLines={ 1 } />
						</div>
						<div style={ { display: 'flex', flexDirection: 'column', gap: '10px' } }>
							<PreviewField label={ __( 'Formulier', 'alegria' ) } value="Contact formulier (Contact Form 7)" maxLines={ 2 } />
							<PreviewField label={ __( 'Velden', 'alegria' ) } value={ __( 'Naam, e-mailadres, telefoonnummer, bericht, privacyverklaring', 'alegria' ) } maxLines={ 3 } />
						</div>
					</div>
				</PreviewCard>
			</div>
		</>
	);
}
