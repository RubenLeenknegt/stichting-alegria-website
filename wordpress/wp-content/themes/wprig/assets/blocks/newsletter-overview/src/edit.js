const { __ } = wp.i18n;
const { InspectorControls, __experimentalLinkControl: LinkControl } = wp.blockEditor;
const { PanelBody, TextControl, Button, Popover } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useState } = wp.element;
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { title, linkText, linkUrl } = attributes;

	const blockProps = useBlockProps();
	const [showLinkPopover, setShowLinkPopover] = useState(false);

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
				<ServerSideRender
					block="wp-rig/newsletter-overview"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
