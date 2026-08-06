// WP globals
const { __ } = wp.i18n;
const { InspectorControls, useBlockProps } = wp.blockEditor;
const { PanelBody, ToggleControl, TextareaControl } = wp.components;
import { PreviewCard, PreviewField, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

export default function Edit(props) {
	const { attributes = {}, setAttributes } = props || {};
	const { content = '', isIframe = false } = attributes;

	const blockProps = useBlockProps();
	const hasContent = typeof content === 'string' && content.trim().length > 0;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Embed Settings', 'wp-rig')} initialOpen={true}>
					<ToggleControl
						label={__('Treat as iframe / HTML', 'wp-rig')}
						help={
							isIframe
								? __('Content below is output as raw HTML (e.g. a pasted <iframe> embed code).', 'wp-rig')
								: __('Content below is run through do_shortcode().', 'wp-rig')
						}
						checked={isIframe}
						onChange={(v) => setAttributes && setAttributes({ isIframe: v })}
					/>
				</PanelBody>

				<PanelBody title={__('Content (Required)', 'wp-rig')} initialOpen={true}>
					<TextareaControl
						label={isIframe ? __('Iframe / HTML code', 'wp-rig') : __('Shortcode', 'wp-rig')}
						value={content}
						onChange={(v) => setAttributes && setAttributes({ content: v })}
						placeholder={
							isIframe
								? __('<iframe src="https://..."></iframe>', 'wp-rig')
								: __('[my_shortcode attr="value"]', 'wp-rig')
						}
						rows={6}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard
						title={isIframe ? __('Iframe / HTML mode', 'wp-rig') : __('Shortcode mode', 'wp-rig')}
						style={{ maxWidth: '680px', margin: '0 auto' }}
					>
						{isIframe ? (
							<pre
								style={{
									margin: 0,
									fontSize: '12px',
									fontFamily: 'Menlo, Consolas, monospace',
									whiteSpace: 'pre-wrap',
									overflowWrap: 'anywhere',
									background: '#f7f7f7',
									border: '1px solid #e0e0e0',
									borderRadius: 4,
									padding: '10px',
								}}
							>
                        {content}
                     </pre>
						) : (
							<PreviewField
								label={__('Shortcode', 'wp-rig')}
								value={stripHtmlText(content)}
								maxLines={3}
							/>
						)}
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={__('Complete required fields', 'wp-rig')}
						message={__('Content', 'wp-rig')}
					/>
				)}
			</div>
		</>
	);
}
