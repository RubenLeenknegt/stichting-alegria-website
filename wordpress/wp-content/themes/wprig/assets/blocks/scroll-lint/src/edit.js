// WP globals
const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;
const { PanelBody, TextControl, Button } = wp.components;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes = {}, setAttributes } = props || {};
	const { title = '', images = [] } = attributes;

	const blockProps = useBlockProps();
	const hasImages = Array.isArray(images) && images.length > 0;

	const onSelectImages = (media) => {
		const nextImages = (media || []).map((item) => ({
			id: item.id,
			url: item.url,
			alt: item.alt || '',
		}));
		setAttributes && setAttributes({ images: nextImages });
	};

	const removeImage = (idToRemove) => {
		setAttributes && setAttributes({
			images: images.filter((img) => img.id !== idToRemove),
		});
	};

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
				</PanelBody>

				<PanelBody title={__('Images (Required)', 'wp-rig')} initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={onSelectImages}
							allowedTypes={['image']}
							multiple={true}
							gallery={true}
							value={images.map((img) => img.id)}
							render={({ open }) => (
								<Button onClick={open} variant="secondary">
									{hasImages ? __('Edit Images', 'wp-rig') : __('Add Images', 'wp-rig')}
								</Button>
							)}
						/>
					</MediaUploadCheck>

					{hasImages && (
						<div style={{ marginTop: '12px', display: 'grid', gap: '8px' }}>
							{images.map((img) => (
								<div key={img.id} style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
									<PreviewThumb src={img.url} alt={img.alt} size={40} />
									<span style={{ fontSize: '12px', flex: 1, overflowWrap: 'anywhere' }}>
										{img.alt || __('(no alt text)', 'wp-rig')}
									</span>
									<Button onClick={() => removeImage(img.id)} variant="link" isDestructive>
										{__('Remove', 'wp-rig')}
									</Button>
								</div>
							))}
						</div>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasImages ? (
					<PreviewCard title={stripHtmlText(title) || __('Scrolllint', 'wp-rig')} style={{ maxWidth: '680px' }}>
						<div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
							{images.map((img) => (
								<PreviewThumb key={img.id} src={img.url} alt={img.alt} size={56} />
							))}
						</div>
						<PreviewField
							label={__('Images', 'wp-rig')}
							value={__('scrolls left to right, full width, not clickable', 'wp-rig')}
							maxLines={1}
						/>
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={__('Complete required fields', 'wp-rig')}
						message={__('Images', 'wp-rig')}
					/>
				)}
			</div>
		</>
	);
}
