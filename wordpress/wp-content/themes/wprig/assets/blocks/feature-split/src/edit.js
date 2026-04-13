const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const {
		title,
		bodyText,
		imageUrl,
		imageId,
		imageAlt,
		ctaText,
		ctaUrl
	} = attributes;

	const blockProps = useBlockProps();
	const [showLinkPopover, setShowLinkPopover] = useState(false);

	const hasRequiredContent = imageUrl && ctaText && ctaUrl;
	const missingRequired = [
		!imageUrl ? __('Image', 'wp-rig') : null,
		!ctaText ? __('CTA text', 'wp-rig') : null,
		!ctaUrl ? __('CTA URL', 'wp-rig') : null,
	].filter(Boolean);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Content', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Title', 'wp-rig')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('Enter title...', 'wp-rig')}
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
								onChange={(value) => setAttributes({ bodyText: value })}
								placeholder={__('Enter body text...', 'wp-rig')}
							/>
						</div>
					</div>
				</PanelBody>

				<PanelBody title={__('Image (Required)', 'wp-rig')} initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								setAttributes({
									imageUrl: media.url,
									imageId: media.id,
									imageAlt: media.alt || ''
								});
							}}
							allowedTypes={['image']}
							value={imageId}
							render={({ open }) => (
								<div>
									{imageUrl && (
										<img
											src={imageUrl}
											alt={imageAlt}
											style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
										/>
									)}
									<Button onClick={open} variant="secondary">
										{imageUrl ? __('Change Image', 'wp-rig') : __('Select Image', 'wp-rig')}
									</Button>
									{imageUrl && (
										<Button
											onClick={() => setAttributes({ imageUrl: '', imageId: 0, imageAlt: '' })}
											variant="link"
											isDestructive
											style={{ marginLeft: '8px' }}
										>
											{__('Remove', 'wp-rig')}
										</Button>
									)}
								</div>
							)}
						/>
					</MediaUploadCheck>
				</PanelBody>

				<PanelBody title={__('CTA (Required)', 'wp-rig')} initialOpen={false}>
					<TextControl
						label={__('CTA Text', 'wp-rig')}
						value={ctaText}
						onChange={(value) => setAttributes({ ctaText: value })}
						placeholder={__('e.g., Learn More', 'wp-rig')}
					/>

					<div style={{ marginTop: '16px' }}>
						<Button
							onClick={() => setShowLinkPopover(!showLinkPopover)}
							variant="secondary"
						>
							{ctaUrl ? __('Edit CTA Link', 'wp-rig') : __('Set CTA Link', 'wp-rig')}
						</Button>
						{ctaUrl && (
							<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
								{ctaUrl}
							</div>
						)}
						{showLinkPopover && (
							<Popover
								position="bottom center"
								onClose={() => setShowLinkPopover(false)}
							>
								<div style={{ width: '300px' }}>
									<LinkControl
										value={{ url: ctaUrl }}
										onChange={(newValue) => {
											setAttributes({ ctaUrl: newValue?.url || '' });
										}}
										onRemove={() => {
											setAttributes({ ctaUrl: '' });
											setShowLinkPopover(false);
										}}
									/>
								</div>
							</Popover>
						)}
					</div>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasRequiredContent ? (
					<ServerSideRender
						block="wp-rig/feature-split"
						attributes={attributes}
					/>
				) : (
					<div style={{
						padding: '40px 20px',
						border: '2px dashed #ccc',
						background: '#f9f9f9'
					}}>
						<p style={{ color: '#666', fontStyle: 'italic', margin: '0 0 8px 0' }}>
							{__('Complete required fields to preview this block:', 'wp-rig')}
						</p>
						<p style={{ color: '#666', margin: 0 }}>
							{missingRequired.join(', ')}
						</p>
					</div>
				)}
			</div>
		</>
	);
}
