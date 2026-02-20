const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const {
		heading,
		subheading,
		backgroundImageUrl,
		backgroundImageId,
		backgroundImageAlt,
		primaryButtonText,
		primaryButtonUrl,
		secondaryButtonText,
		secondaryButtonUrl
	} = attributes;

	const blockProps = useBlockProps();

	// State for link popover visibility
	const [showPrimaryLinkPopover, setShowPrimaryLinkPopover] = useState(false);
	const [showSecondaryLinkPopover, setShowSecondaryLinkPopover] = useState(false);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Hero Content', 'wp-rig')} initialOpen={true}>
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
				</PanelBody>

				<PanelBody title={__('Buttons', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Primary Button Text', 'wp-rig')}
						value={primaryButtonText}
						onChange={(value) => setAttributes({ primaryButtonText: value })}
						placeholder={__('e.g., Get Started', 'wp-rig')}
					/>

					<div style={{ marginBottom: '16px' }}>
						<Button
							onClick={() => setShowPrimaryLinkPopover(!showPrimaryLinkPopover)}
							variant="secondary"
							style={{ marginTop: '8px' }}
						>
							{primaryButtonUrl ? __('Edit Primary Link', 'wp-rig') : __('Set Primary Link', 'wp-rig')}
						</Button>
						{primaryButtonUrl && (
							<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
								{primaryButtonUrl}
							</div>
						)}
						{showPrimaryLinkPopover && (
							<Popover
								position="bottom center"
								onClose={() => setShowPrimaryLinkPopover(false)}
							>
								<div style={{ width: '300px' }}>
									<LinkControl
										value={{ url: primaryButtonUrl }}
										onChange={(newValue) => {
											setAttributes({ primaryButtonUrl: newValue?.url || '' });
										}}
										onRemove={() => {
											setAttributes({ primaryButtonUrl: '' });
											setShowPrimaryLinkPopover(false);
										}}
									/>
								</div>
							</Popover>
						)}
					</div>

					<div style={{ marginTop: '16px' }}>
						<TextControl
							label={__('Secondary Button Text', 'wp-rig')}
							value={secondaryButtonText}
							onChange={(value) => setAttributes({ secondaryButtonText: value })}
							placeholder={__('e.g., Learn More', 'wp-rig')}
						/>

						<Button
							onClick={() => setShowSecondaryLinkPopover(!showSecondaryLinkPopover)}
							variant="secondary"
							style={{ marginTop: '8px' }}
						>
							{secondaryButtonUrl ? __('Edit Secondary Link', 'wp-rig') : __('Set Secondary Link', 'wp-rig')}
						</Button>
						{secondaryButtonUrl && (
							<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
								{secondaryButtonUrl}
							</div>
						)}
						{showSecondaryLinkPopover && (
							<Popover
								position="bottom center"
								onClose={() => setShowSecondaryLinkPopover(false)}
							>
								<div style={{ width: '300px' }}>
									<LinkControl
										value={{ url: secondaryButtonUrl }}
										onChange={(newValue) => {
											setAttributes({ secondaryButtonUrl: newValue?.url || '' });
										}}
										onRemove={() => {
											setAttributes({ secondaryButtonUrl: '' });
											setShowSecondaryLinkPopover(false);
										}}
									/>
								</div>
							</Popover>
						)}
					</div>
				</PanelBody>

				<PanelBody title={__('Background Image', 'wp-rig')} initialOpen={false}>
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
								<div>
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
											{__('Remove', 'wp-rig')}
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
					<div style={{
						padding: '40px 20px',
						border: '2px dashed #ccc',
						background: '#f9f9f9'
					}}>
						<p style={{ color: '#666', fontStyle: 'italic' }}>
							Configure your hero block in the sidebar →
						</p>
					</div>
				)}
			</div>
		</>
	);
}
