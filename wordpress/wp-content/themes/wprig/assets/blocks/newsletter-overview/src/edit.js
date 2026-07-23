const { __ } = wp.i18n;
const { InspectorControls, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
const { useSelect } = wp.data;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { title, linkText, linkUrl } = attributes;

	const blockProps = useBlockProps();
	const [showLinkPopover, setShowLinkPopover] = useState(false);

	const posts = useSelect((select) => {
		return select('core').getEntityRecords('postType', 'newsletter', {
			per_page: 3,
			orderby: 'date',
			order: 'desc',
			_embed: true,
			context: 'view',
			status: 'publish'
		});
	}, []);

	const hasContent = title || linkText || linkUrl || (posts && posts.length > 0);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Section Settings', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Section Title', 'wp-rig')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('e.g., Latest newsletters', 'wp-rig')}
					/>

					<TextControl
						label={__('Link Text', 'wp-rig')}
						value={linkText}
						onChange={(value) => setAttributes({ linkText: value })}
						placeholder={__('e.g., View all newsletters', 'wp-rig')}
					/>

					<div style={{ marginTop: '16px' }}>
						<Button
							onClick={() => setShowLinkPopover(!showLinkPopover)}
							variant="secondary"
						>
							{linkUrl ? __('Edit Link', 'wp-rig') : __('Set Link', 'wp-rig')}
						</Button>
						{linkUrl && (
							<div style={{ marginTop: '4px', fontSize: '12px', color: '#666' }}>
								{linkUrl}
							</div>
						)}
						{showLinkPopover && (
							<Popover
								position="bottom center"
								onClose={() => setShowLinkPopover(false)}
							>
								<div style={{ width: '300px' }}>
									<LinkControl
										value={{ url: linkUrl }}
										onChange={(newValue) => {
											setAttributes({ linkUrl: newValue?.url || '' });
										}}
										onRemove={() => {
											setAttributes({ linkUrl: '' });
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
					<PreviewCard title={stripHtmlText(title) || __('Newsletter overview', 'wp-rig')} style={{ maxWidth: '780px' }}>
						<div style={{ display: 'grid', gap: '10px' }}>
							{linkText ? <PreviewField label={__('Link text', 'wp-rig')} value={linkText} maxLines={1} /> : null}
							{linkUrl ? <PreviewField label={__('Link', 'wp-rig')} value={linkUrl} maxLines={1} /> : null}
							{posts && posts.length > 0 ? (
								<ul style={{ padding: 0, margin: 0, listStyle: 'none', display: 'grid', gap: '10px' }}>
									{posts.slice(0, 3).map((post, index) => {
										const imageUrl = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
										const excerpt = stripHtmlText(post.excerpt?.rendered || post.excerpt || '');

										return (
											<li key={post.id} style={{ display: 'flex', gap: '10px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' }}>
												<PreviewThumb src={imageUrl} alt={post.title?.rendered || ''} size={48} />
												<div style={{ flex: 1, minWidth: 0, display: 'grid', gap: '6px' }}>
													<PreviewField label={__('Post', 'wp-rig')} value={post.title?.rendered || __('Untitled newsletter', 'wp-rig')} maxLines={1} />
													{excerpt ? <PreviewField label={__('Excerpt', 'wp-rig')} value={excerpt} maxLines={2} /> : null}
												</div>
											</li>
										);
									})}
								</ul>
							) : (
								<PreviewEmptyState title={__('No newsletters found', 'wp-rig')} message={__('Publish newsletter posts to populate this preview.', 'wp-rig')} />
							)}
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('No newsletter content yet', 'wp-rig')} message={__('Add a title or link text to preview this block.', 'wp-rig')} />
				)}
			</div>
		</>
	);
}
