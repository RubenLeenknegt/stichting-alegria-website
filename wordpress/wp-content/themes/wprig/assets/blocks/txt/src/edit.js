// WP globals
const { __ } = wp.i18n;
const { InspectorControls, RichText, useBlockProps } = wp.blockEditor;
const { PanelBody, TextControl, SelectControl } = wp.components;
import { PreviewCard, PreviewField, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes = {}, setAttributes } = props || {};
	const { title = '', titleTag = 'h2', bodyText = '' } = attributes;

	const blockProps = useBlockProps();
	const hasContent = title || bodyText;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Content', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Title', 'wp-rig')}
						value={title}
						onChange={(v) => setAttributes && setAttributes({ title: v })}
						placeholder={__('Enter title...', 'wp-rig')}
					/>

					<SelectControl
						label={__('Title Heading Level', 'wp-rig')}
						value={titleTag}
						options={[
							{ label: __('H2', 'wp-rig'), value: 'h2' },
							{ label: __('H3', 'wp-rig'), value: 'h3' },
						]}
						onChange={(v) => setAttributes && setAttributes({ titleTag: v })}
					/>

					<div style={{ marginTop: '16px', marginBottom: '16px' }}>
						<label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>
							{__('Body Text (RichText)', 'wp-rig')}
						</label>
						<div style={{
							border: '1px solid #ddd',
							padding: '12px',
							borderRadius: '4px',
							background: '#fff'
						}}>
							<RichText
								tagName="div"
								value={bodyText}
								onChange={(v) => setAttributes && setAttributes({ bodyText: v })}
								placeholder={__('Enter body text...', 'wp-rig')}
								allowedFormats={['core/bold', 'core/italic', 'core/link']}
							/>
						</div>
					</div>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard title={stripHtmlText(title) || __('Text Block', 'wp-rig')} style={{ maxWidth: '680px' }}>
						{bodyText ? <PreviewField label={__('Body', 'wp-rig')} value={bodyText} maxLines={3} /> : null}
						<PreviewField label={__('Heading level', 'wp-rig')} value={titleTag.toUpperCase()} maxLines={1} />
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={__('Complete required fields', 'wp-rig')}
						message={__('Title or body text', 'wp-rig')}
					/>
				)}
			</div>
		</>
	);
}
