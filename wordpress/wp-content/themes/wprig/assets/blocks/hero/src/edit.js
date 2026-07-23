const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, TextareaControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
const { useSelect } = wp.data;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

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
	const [showPrimaryLinkPopover, setShowPrimaryLinkPopover] = useState(false);
	const [showSecondaryLinkPopover, setShowSecondaryLinkPopover] = useState(false);

	const previewImageUrl = useSelect((select) => {
		if (backgroundImageUrl) {
			return backgroundImageUrl;
		}

		if (!backgroundImageId) {
			return '';
		}

		return select('core').getMedia(backgroundImageId)?.source_url || '';
	}, [backgroundImageUrl, backgroundImageId]);

	const hasContent = heading || subheading || previewImageUrl || primaryButtonText || secondaryButtonText || primaryButtonUrl || secondaryButtonUrl;

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
				{hasContent ? (
					<PreviewCard title={stripHtmlText(heading) || __('Hero section', 'wp-rig')} style={{ maxWidth: '680px' }}>
						<div style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
							{previewImageUrl ? <PreviewThumb src={previewImageUrl} alt={backgroundImageAlt} size={72} /> : null}
							<div style={{ flex: 1, minWidth: 0, display: 'grid', gap: '8px' }}>
								{subheading ? <PreviewField label={__('Subheading', 'wp-rig')} value={subheading} maxLines={3} /> : null}
								{primaryButtonText ? <PreviewField label={__('Primary button', 'wp-rig')} value={primaryButtonText} maxLines={1} /> : null}
								{secondaryButtonText ? <PreviewField label={__('Secondary button', 'wp-rig')} value={secondaryButtonText} maxLines={1} /> : null}
								{primaryButtonUrl ? <PreviewField label={__('Primary link', 'wp-rig')} value={primaryButtonUrl} maxLines={1} /> : null}
								{secondaryButtonUrl ? <PreviewField label={__('Secondary link', 'wp-rig')} value={secondaryButtonUrl} maxLines={1} /> : null}
							</div>
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('No hero content yet', 'wp-rig')} message={__('Add a heading, text, or image to preview the hero block.', 'wp-rig')} />
				)}
			</div>
		</>
	);
}
