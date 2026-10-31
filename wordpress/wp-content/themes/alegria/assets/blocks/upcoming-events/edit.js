import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { title, maxEvents } = attributes;

	const blockProps = useBlockProps();

	const events = useSelect((select) => {
		return select(coreStore).getEntityRecords('postType', 'event', {
			per_page: maxEvents,
			orderby: 'date',
			order: 'asc',
			_embed: true,
			context: 'view',
			status: 'publish'
		});
	}, [maxEvents]);

	const hasContent = title || (events && events.length > 0);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Sectie', 'alegria')} initialOpen={true}>
					<TextControl
						label={__('Sectietitel', 'alegria')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('bijv. Aankomende evenementen', 'alegria')}
					/>

					<RangeControl
						label={__('Maximaal aantal evenementen', 'alegria')}
						help={__('Op de site worden alleen aankomende evenementen getoond, de eerstvolgende eerst.', 'alegria')}
						value={maxEvents}
						onChange={(value) => setAttributes({ maxEvents: value })}
						min={1}
						max={10}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard title={stripHtmlText(title) || __('Aankomende evenementen', 'alegria')} style={{ maxWidth: '780px' }}>
						<div style={{ display: 'grid', gap: '10px' }}>
							{events && events.length > 0 ? (
								<ul style={{ padding: 0, margin: 0, listStyle: 'none', display: 'grid', gap: '10px' }}>
									{events.map((event, index) => {
										const imageUrl = event._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
										const excerpt = stripHtmlText(event.excerpt?.rendered || event.excerpt || '');
										const eventDate = event.meta?.event_date || '';

										return (
											<li key={event.id} style={{ display: 'flex', gap: '10px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' }}>
												<PreviewThumb src={imageUrl} alt={event.title?.rendered || ''} size={48} />
												<div style={{ flex: 1, minWidth: 0, display: 'grid', gap: '6px' }}>
													<PreviewField label={__('Evenement', 'alegria')} value={event.title?.rendered || __('Zonder titel', 'alegria')} maxLines={1} />
													{eventDate ? <PreviewField label={__('Datum', 'alegria')} value={eventDate} maxLines={1} /> : null}
													{excerpt ? <PreviewField label={__('Samenvatting', 'alegria')} value={excerpt} maxLines={2} /> : null}
												</div>
											</li>
										);
									})}
								</ul>
							) : (
								<PreviewEmptyState title={__('Geen evenementen gevonden', 'alegria')} message={__('Publiceer evenementen met een datum in de toekomst om ze hier te zien.', 'alegria')} />
							)}
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('Nog geen inhoud', 'alegria')} message={__('Vul in de zijbalk een sectietitel in of publiceer evenementen.', 'alegria')} />
				)}
			</div>
		</>
	);
}
