const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
const { useSelect } = wp.data;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const {
		topLeftImageUrl,
		topLeftImageId,
		topLeftImageAlt,
		topRightImageUrl,
		topRightImageId,
		topRightImageAlt,
		bottomImageUrl,
		bottomImageId,
		bottomImageAlt,
		preTitle,
		title,
		textBlock1,
		subtitle,
		textBlock2,
		buttonText,
		buttonUrl
	} = attributes;

	const blockProps = useBlockProps();
	const [showLinkPopover, setShowLinkPopover] = useState(false);

	const topLeftPreviewUrl = useSelect((select) => {
		if (topLeftImageUrl) {
			return topLeftImageUrl;
		}
		if (!topLeftImageId) {
			return '';
		}
		return select('core').getMedia(topLeftImageId)?.source_url || '';
	}, [topLeftImageUrl, topLeftImageId]);

	const topRightPreviewUrl = useSelect((select) => {
		if (topRightImageUrl) {
			return topRightImageUrl;
		}
		if (!topRightImageId) {
			return '';
		}
		return select('core').getMedia(topRightImageId)?.source_url || '';
	}, [topRightImageUrl, topRightImageId]);

	const bottomPreviewUrl = useSelect((select) => {
		if (bottomImageUrl) {
			return bottomImageUrl;
		}
		if (!bottomImageId) {
			return '';
		}
		return select('core').getMedia(bottomImageId)?.source_url || '';
	}, [bottomImageUrl, bottomImageId]);

	const hasContent = preTitle || title || textBlock1 || subtitle || textBlock2 || topLeftPreviewUrl || topRightPreviewUrl || bottomPreviewUrl || buttonText || buttonUrl;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Images', 'wp-rig')} initialOpen={true}>
					<h3 style={{ marginBottom: '8px' }}>{__('Top Left Image', 'wp-rig')}</h3>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								setAttributes({
									topLeftImageUrl: media.url,
									topLeftImageId: media.id,
									topLeftImageAlt: media.alt || ''
								});
							}}
							allowedTypes={['image']}
							value={topLeftImageId}
							render={({ open }) => (
								<div style={{ marginBottom: '16px' }}>
									{topLeftImageUrl && (
										<img
											src={topLeftImageUrl}
											alt={topLeftImageAlt}
											style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
										/>
									)}
									<Button onClick={open} variant="secondary">
										{topLeftImageUrl ? __('Change', 'wp-rig') : __('Select', 'wp-rig')}
									</Button>
									{topLeftImageUrl && (
										<Button
											onClick={() => setAttributes({
												topLeftImageUrl: '',
												topLeftImageId: 0,
												topLeftImageAlt: ''
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

					<h3 style={{ marginBottom: '8px' }}>{__('Top Right Image', 'wp-rig')}</h3>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								setAttributes({
									topRightImageUrl: media.url,
									topRightImageId: media.id,
									topRightImageAlt: media.alt || ''
								});
							}}
							allowedTypes={['image']}
							value={topRightImageId}
							render={({ open }) => (
								<div style={{ marginBottom: '16px' }}>
									{topRightImageUrl && (
										<img
											src={topRightImageUrl}
											alt={topRightImageAlt}
											style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
										/>
									)}
									<Button onClick={open} variant="secondary">
										{topRightImageUrl ? __('Change', 'wp-rig') : __('Select', 'wp-rig')}
									</Button>
									{topRightImageUrl && (
										<Button
											onClick={() => setAttributes({
												topRightImageUrl: '',
												topRightImageId: 0,
												topRightImageAlt: ''
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

					<h3 style={{ marginBottom: '8px' }}>{__('Bottom Wide Image', 'wp-rig')}</h3>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => {
								setAttributes({
									bottomImageUrl: media.url,
									bottomImageId: media.id,
									bottomImageAlt: media.alt || ''
								});
							}}
							allowedTypes={['image']}
							value={bottomImageId}
							render={({ open }) => (
								<div style={{ marginBottom: '16px' }}>
									{bottomImageUrl && (
										<img
											src={bottomImageUrl}
											alt={bottomImageAlt}
											style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
										/>
									)}
									<Button onClick={open} variant="secondary">
										{bottomImageUrl ? __('Change', 'wp-rig') : __('Select', 'wp-rig')}
									</Button>
									{bottomImageUrl && (
										<Button
											onClick={() => setAttributes({
												bottomImageUrl: '',
												bottomImageId: 0,
												bottomImageAlt: ''
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

				<PanelBody title={__('Content', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Pre-Title (small text above title)', 'wp-rig')}
						value={preTitle}
						onChange={(value) => setAttributes({ preTitle: value })}
						placeholder={__('e.g., Who We Are', 'wp-rig')}
					/>

					<TextControl
						label={__('Main Title', 'wp-rig')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('e.g., About Our Company', 'wp-rig')}
					/>

					<div style={{ marginTop: '16px', marginBottom: '16px' }}>
						<label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>
							{__('First Text Block (RichText)', 'wp-rig')}
						</label>
						<div style={{
							border: '1px solid #ddd',
							padding: '12px',
							borderRadius: '4px',
							background: '#fff'
						}}>
							<RichText
								tagName="div"
								value={textBlock1}
								onChange={(value) => setAttributes({ textBlock1: value })}
								placeholder={__('Enter your content here. You can use bold, italic, and links...', 'wp-rig')}
							/>
						</div>
						<p style={{ fontSize: '12px', color: '#666', marginTop: '4px' }}>
							{__('Select text to format (bold, italic, links)', 'wp-rig')}
						</p>
					</div>

					<TextControl
						label={__('Subtitle (smaller heading)', 'wp-rig')}
						value={subtitle}
						onChange={(value) => setAttributes({ subtitle: value })}
						placeholder={__('e.g., Our Mission', 'wp-rig')}
					/>

					<div style={{ marginTop: '16px', marginBottom: '16px' }}>
						<label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>
							{__('Second Text Block (RichText)', 'wp-rig')}
						</label>
						<div style={{
							border: '1px solid #ddd',
							padding: '12px',
							borderRadius: '4px',
							background: '#fff'
						}}>
							<RichText
								tagName="div"
								value={textBlock2}
								onChange={(value) => setAttributes({ textBlock2: value })}
								placeholder={__('Enter your content here. You can use bold, italic, and links...', 'wp-rig')}
							/>
						</div>
						<p style={{ fontSize: '12px', color: '#666', marginTop: '4px' }}>
							{__('Select text to format (bold, italic, links)', 'wp-rig')}
						</p>
					</div>
				</PanelBody>

				<PanelBody title={__('Button', 'wp-rig')} initialOpen={false}>
					<TextControl
						label={__('Button Text', 'wp-rig')}
						value={buttonText}
						onChange={(value) => setAttributes({ buttonText: value })}
						placeholder={__('e.g., Learn More', 'wp-rig')}
					/>

					<div style={{ marginTop: '16px' }}>
						<Button
							onClick={() => setShowLinkPopover(!showLinkPopover)}
							variant="secondary"
						>
							{buttonUrl ? __('Edit Link', 'wp-rig') : __('Set Link', 'wp-rig')}
						</Button>
						{buttonUrl && (
							<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
								{buttonUrl}
							</div>
						)}
						{showLinkPopover && (
							<Popover
								position="bottom center"
								onClose={() => setShowLinkPopover(false)}
							>
								<div style={{ width: '300px' }}>
									<LinkControl
										value={{ url: buttonUrl }}
										onChange={(newValue) => {
											setAttributes({ buttonUrl: newValue?.url || '' });
										}}
										onRemove={() => {
											setAttributes({ buttonUrl: '' });
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
				{hasContent ? (
					<PreviewCard title={stripHtmlText(title) || __('About us', 'wp-rig')} style={{ maxWidth: '780px' }}>
						<div style={{ display: 'grid', gap: '10px' }}>
							{preTitle ? <PreviewField label={__('Pre-title', 'wp-rig')} value={preTitle} maxLines={1} /> : null}
							{textBlock1 ? <PreviewField label={__('Intro', 'wp-rig')} value={textBlock1} maxLines={3} /> : null}
							{subtitle ? <PreviewField label={__('Subtitle', 'wp-rig')} value={subtitle} maxLines={1} /> : null}
							{textBlock2 ? <PreviewField label={__('Details', 'wp-rig')} value={textBlock2} maxLines={3} /> : null}
							{buttonText ? <PreviewField label={__('Button', 'wp-rig')} value={buttonText} maxLines={1} /> : null}
							{buttonUrl ? <PreviewField label={__('Link', 'wp-rig')} value={buttonUrl} maxLines={1} /> : null}
							<div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
								{topLeftPreviewUrl ? <PreviewThumb src={topLeftPreviewUrl} alt={topLeftImageAlt} size={48} /> : null}
								{topRightPreviewUrl ? <PreviewThumb src={topRightPreviewUrl} alt={topRightImageAlt} size={48} /> : null}
								{bottomPreviewUrl ? <PreviewThumb src={bottomPreviewUrl} alt={bottomImageAlt} size={48} /> : null}
							</div>
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('No about us content yet', 'wp-rig')} message={__('Add a title, intro text, or images to preview this block.', 'wp-rig')} />
				)}
			</div>
		</>
	);
}
