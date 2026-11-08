import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextareaControl } from '@wordpress/components';
import { PreviewCard, PreviewField, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit(props) {
	const { attributes = {}, setAttributes } = props;
	const { content = '', isIframe = false } = attributes;

	const blockProps = useBlockProps();
	const hasContent = typeof content === 'string' && content.trim().length > 0;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Instellingen', 'alegria')} initialOpen={true}>
					<ToggleControl
						label={__('Als iframe / HTML insluiten', 'alegria')}
						help={
							isIframe
								? __('De inhoud wordt als HTML op de pagina gezet (bijv. een geplakte <iframe>-code). Alleen beheerders en redacteuren kunnen dit gebruiken.', 'alegria')
								: __('De inhoud wordt als shortcode uitgevoerd.', 'alegria')
						}
						checked={isIframe}
						onChange={(v) => setAttributes({ isIframe: v })}
					/>
				</PanelBody>

				<PanelBody title={__('Inhoud (verplicht)', 'alegria')} initialOpen={true}>
					<TextareaControl
						label={isIframe ? __('Iframe- / HTML-code', 'alegria') : __('Shortcode', 'alegria')}
						value={content}
						onChange={(v) => setAttributes({ content: v })}
						placeholder={
							isIframe
								? __('<iframe src="https://..."></iframe>', 'alegria')
								: __('[my_shortcode attr="value"]', 'alegria')
						}
						rows={6}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard
						title={isIframe ? __('Iframe / HTML', 'alegria') : __('Shortcode', 'alegria')}
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
								label={__('Shortcode', 'alegria')}
								value={stripHtmlText(content)}
								maxLines={3}
							/>
						)}
					</PreviewCard>
				) : (
					<PreviewEmptyState
						title={__('Nog geen inhoud', 'alegria')}
						message={__('Vul in de zijbalk een shortcode of embedcode in.', 'alegria')}
					/>
				)}
			</div>
		</>
	);
}
