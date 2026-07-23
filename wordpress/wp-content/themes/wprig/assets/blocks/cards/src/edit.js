const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const {
		sectionTitle,
		card1ImageUrl, card1ImageId, card1ImageAlt, card1Title, card1Text, card1Url,
		card2ImageUrl, card2ImageId, card2ImageAlt, card2Title, card2Text, card2Url,
		card3ImageUrl, card3ImageId, card3ImageAlt, card3Title, card3Text, card3Url
	} = attributes;

	const blockProps = useBlockProps();
	const [showLink1Popover, setShowLink1Popover] = useState(false);
	const [showLink2Popover, setShowLink2Popover] = useState(false);
	const [showLink3Popover, setShowLink3Popover] = useState(false);

	const hasContent = sectionTitle || card1ImageUrl || card2ImageUrl || card3ImageUrl || card1Title || card2Title || card3Title || card1Text || card2Text || card3Text || card1Url || card2Url || card3Url;
	const cards = [
		{ key: 'card1', imageUrl: card1ImageUrl, imageAlt: card1ImageAlt, title: card1Title, text: card1Text, url: card1Url },
		{ key: 'card2', imageUrl: card2ImageUrl, imageAlt: card2ImageAlt, title: card2Title, text: card2Text, url: card2Url },
		{ key: 'card3', imageUrl: card3ImageUrl, imageAlt: card3ImageAlt, title: card3Title, text: card3Text, url: card3Url }
	];

	const renderCardControls = (cardNumber, imageUrl, imageId, imageAlt, title, text, url, showLinkPopover, setShowLinkPopover) => {
		const imageAttr = `card${cardNumber}ImageUrl`;
		const imageIdAttr = `card${cardNumber}ImageId`;
		const imageAltAttr = `card${cardNumber}ImageAlt`;
		const titleAttr = `card${cardNumber}Title`;
		const textAttr = `card${cardNumber}Text`;
		const urlAttr = `card${cardNumber}Url`;

		return (
			<PanelBody title={__(`Card ${cardNumber}`, 'wp-rig')} initialOpen={cardNumber === 1}>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={(media) => {
							setAttributes({
								[imageAttr]: media.url,
								[imageIdAttr]: media.id,
								[imageAltAttr]: media.alt || ''
							});
						}}
						allowedTypes={['image']}
						value={imageId}
						render={({ open }) => (
							<div style={{ marginBottom: '16px' }}>
								<p><strong>{__('Background Image', 'wp-rig')}</strong></p>
								{imageUrl && (
									<img
										src={imageUrl}
										alt={imageAlt}
										style={{ maxWidth: '100%', height: 'auto', marginBottom: '8px' }}
									/>
								)}
								<Button onClick={open} variant="secondary">
									{imageUrl ? __('Change', 'wp-rig') : __('Select', 'wp-rig')}
								</Button>
								{imageUrl && (
									<Button
										onClick={() => setAttributes({
											[imageAttr]: '',
											[imageIdAttr]: 0,
											[imageAltAttr]: ''
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

				<TextControl
					label={__('Card Title', 'wp-rig')}
					value={title}
					onChange={(value) => setAttributes({ [titleAttr]: value })}
					placeholder={__('Enter card title...', 'wp-rig')}
				/>

				<div style={{ marginTop: '16px', marginBottom: '16px' }}>
					<label style={{ display: 'block', marginBottom: '8px', fontWeight: '600' }}>
						{__('Card Text (RichText)', 'wp-rig')}
					</label>
					<div style={{
						border: '1px solid #ddd',
						padding: '12px',
						borderRadius: '4px',
						background: '#fff'
					}}>
						<RichText
							tagName="div"
							value={text}
							onChange={(value) => setAttributes({ [textAttr]: value })}
							placeholder={__('Enter card text...', 'wp-rig')}
						/>
					</div>
				</div>

				<div style={{ marginTop: '16px' }}>
					<Button
						onClick={() => setShowLinkPopover(!showLinkPopover)}
						variant="secondary"
					>
						{url ? __('Edit Link', 'wp-rig') : __('Set Link', 'wp-rig')}
					</Button>
					{url && (
						<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
							{url}
						</div>
					)}
					{showLinkPopover && (
						<Popover
							position="bottom center"
							onClose={() => setShowLinkPopover(false)}
						>
							<div style={{ width: '300px' }}>
								<LinkControl
									value={{ url: url }}
									onChange={(newValue) => {
										setAttributes({ [urlAttr]: newValue?.url || '' });
									}}
									onRemove={() => {
										setAttributes({ [urlAttr]: '' });
										setShowLinkPopover(false);
									}}
								/>
							</div>
						</Popover>
					)}
				</div>
			</PanelBody>
		);
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Section Settings', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Section Title', 'wp-rig')}
						value={sectionTitle}
						onChange={(value) => setAttributes({ sectionTitle: value })}
						placeholder={__('e.g., Our Services', 'wp-rig')}
					/>
				</PanelBody>

				{renderCardControls(1, card1ImageUrl, card1ImageId, card1ImageAlt, card1Title, card1Text, card1Url, showLink1Popover, setShowLink1Popover)}
				{renderCardControls(2, card2ImageUrl, card2ImageId, card2ImageAlt, card2Title, card2Text, card2Url, showLink2Popover, setShowLink2Popover)}
				{renderCardControls(3, card3ImageUrl, card3ImageId, card3ImageAlt, card3Title, card3Text, card3Url, showLink3Popover, setShowLink3Popover)}
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard title={stripHtmlText(sectionTitle) || __('Cards section', 'wp-rig')} style={{ maxWidth: '680px' }}>
						<div style={{ display: 'grid', gap: '12px' }}>
							{cards.map((card, index) => {
								const hasCardContent = card.title || card.text || card.url || card.imageUrl;

								if (!hasCardContent) {
									return null;
								}

								return (
									<div key={card.key} style={{ display: 'flex', gap: '12px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' }}>
										<PreviewThumb src={card.imageUrl} alt={card.imageAlt} size={48} />
										<div style={{ flex: 1, minWidth: 0 }}>
											<PreviewField label={__('Title', 'wp-rig')} value={card.title || __('Untitled card', 'wp-rig')} maxLines={1} />
											{card.text ? <PreviewField label={__('Text', 'wp-rig')} value={card.text} maxLines={2} /> : null}
											{card.url ? <PreviewField label={__('Link', 'wp-rig')} value={card.url} maxLines={1} /> : null}
										</div>
									</div>
								);
							})}
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('No cards yet', 'wp-rig')} message={__('Add a section title or card content to preview this block.', 'wp-rig')} />
				)}
			</div>
		</>
	);
}
