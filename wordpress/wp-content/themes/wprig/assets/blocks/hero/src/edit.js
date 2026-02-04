const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
const { useBlockProps } = wp.blockEditor;
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { heading, subheading, backgroundImageUrl, backgroundImageId, backgroundImageAlt } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Hero Settings', 'wp-rig')}>
					<TextControl
						label={__('Heading', 'wp-rig')}
						value={heading}
						onChange={(value) => setAttributes({ heading: value })}
						help={__('Main heading text', 'wp-rig')}
					/>
					<TextareaControl
						label={__('Subheading', 'wp-rig')}
						value={subheading}
						onChange={(value) => setAttributes({ subheading: value })}
						rows={3}
						help={__('Supporting text below the heading', 'wp-rig')}
					/>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								setAttributes({
									backgroundImageUrl: media.url,
									backgroundImageId: media.id,
									backgroundImageAlt: media.alt || ''
								});
							}}
							allowedTypes={['image']}
							value={backgroundImageId}
							render={({ open }) => (
								<div style={{ marginTop: '16px' }}>
									<p><strong>{__('Background Image', 'wp-rig')}</strong></p>
									{backgroundImageUrl && (
										<img
											src={backgroundImageUrl}
											alt={backgroundImageAlt}
											style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
										/>
									)}
									<Button onClick={open} variant="secondary">
										{backgroundImageUrl ? __('Change Image', 'wp-rig') : __('Select Image', 'wp-rig')}
									</Button>
									{backgroundImageUrl && (
										<Button
											onClick={() => setAttributes({
												backgroundImageUrl: '',
												backgroundImageId: 0,
												backgroundImageAlt: ''
											})}
											variant="link"
											isDestructive
											style={{ marginLeft: '8px' }}
										>
											{__('Remove Image', 'wp-rig')}
										</Button>
									)}
								</div>
							)}
						/>
					</MediaUploadCheck>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{(heading || subheading || backgroundImageUrl) ? (
					<ServerSideRender
						block="wp-rig/hero"
						attributes={attributes}
					/>
				) : (
					<div style={{ padding: '20px', border: '1px dashed #ccc', background: '#f9f9f9' }}>
						<p><em>Configure your hero block in the sidebar →</em></p>
					</div>
				)}
			</div>
		</>
	);
}
