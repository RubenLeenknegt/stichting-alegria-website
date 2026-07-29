// WP globals
const { __ } = wp.i18n;
const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;
const { PanelBody, TextControl, Button } = wp.components;
import { PreviewCard, PreviewThumb, PreviewEmptyState } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes = {}, setAttributes } = props || {};
	const { imageUrl = '', imageId = 0, imageAlt = '' } = attributes;

	const blockProps = useBlockProps();
	const hasImage = !! imageUrl;

	const onSelectImage = (media) => {
		setAttributes && setAttributes({
			imageUrl: media.url,
			imageId: media.id,
			imageAlt: media.alt || '',
		});
	};

	const removeImage = () => {
		setAttributes && setAttributes({ imageUrl: '', imageId: 0, imageAlt: '' });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Image (Required)', 'wp-rig')} initialOpen={true}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={onSelectImage}
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
											onClick={removeImage}
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

					{hasImage && (
						<TextControl
							label={__('Alt Text', 'wp-rig')}
							value={imageAlt}
							onChange={(v) => setAttributes && setAttributes({ imageAlt: v })}
							placeholder={__('Describe the image...', 'wp-rig')}
							style={{ marginTop: '12px' }}
						/>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasImage ? (
					<PreviewCard title={__('Image', 'wp-rig')} style={{ maxWidth: '680px' }}>
						<PreviewThumb src={imageUrl} alt={imageAlt} size={96} />
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={__('Complete required fields', 'wp-rig')}
						message={__('Image', 'wp-rig')}
					/>
				)}
			</div>
		</>
	);
}
