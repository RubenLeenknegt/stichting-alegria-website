const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, RichText, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

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

	const hasContent = sectionTitle || card1ImageUrl || card2ImageUrl || card3ImageUrl;

	// Helper function to render card controls
	const renderCardControls = (cardNumber, imageUrl, imageId, imageAlt, title, text, url, showLinkPopover, setShowLinkPopover) => {
		return (
			<PanelBody title={__(`Card ${cardNumber}`, 'wp-rig')} initialOpen={cardNumber === 1}>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={(media) => {
							setAttributes({
								[`card${cardNumber}ImageUrl`]: media.url,
								[`card${cardNumber}ImageId`]: media.id,
								[`card${cardNumber}ImageAlt`]: media.alt || ''
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
											[`card${cardNumber}ImageUrl`]: '',
											[`card${cardNumber}ImageId`]: 0,
											[`card${cardNumber}ImageAlt`]: ''
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
					onChange={(value) => setAttributes({ [`card${cardNumber}Title`]: value })}
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
							onChange={(value) => setAttributes({ [`card${cardNumber}Text`]: value })}
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
										setAttributes({ [`card${cardNumber}Url`]: newValue?.url || '' });
									}}
									onRemove={() => {
										setAttributes({ [`card${cardNumber}Url`]: '' });
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
					<ServerSideRender
						block="wp-rig/cards"
						attributes={attributes}
					/>
				) : (
					<div style={{
						padding: '40px 20px',
						border: '2px dashed #ccc',
						background: '#f9f9f9'
					}}>
						<p style={{ color: '#666', fontStyle: 'italic' }}>
							Configure your cards block in the sidebar →
						</p>
					</div>
				)}
			</div>
		</>
	);
}
