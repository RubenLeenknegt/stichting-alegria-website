const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl, RangeControl } = wp.components;
const { useBlockProps } = wp.blockEditor;
const { useSelect } = wp.data;
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../../components/editor-preview';

const DUTCH_MONTHS = [
	'januari', 'februari', 'maart', 'april', 'mei', 'juni',
	'juli', 'augustus', 'september', 'oktober', 'november', 'december'
];

function formatEventDate(dateString) {
	if (!dateString) {
		return '';
	}

	const parts = dateString.split('-');
	if (parts.length !== 3) {
		return dateString;
	}

	const [year, month, day] = parts;
	const monthIndex = parseInt(month, 10) - 1;
	const monthName = DUTCH_MONTHS[monthIndex];

	if (!monthName) {
		return dateString;
	}

	return `${parseInt(day, 10)} ${monthName} ${year}`;
}

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { title, maxEvents } = attributes;

	const blockProps = useBlockProps();

	const events = useSelect((select) => {
		return select('core').getEntityRecords('postType', 'event', {
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
				<PanelBody title={__('Section Settings', 'wp-rig')} initialOpen={true}>
					<TextControl
						label={__('Section Title', 'wp-rig')}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						placeholder={__('e.g., Aankomende evenementen', 'wp-rig')}
					/>

					<RangeControl
						label={__('Maximum events to show', 'wp-rig')}
						help={__('Only upcoming events are shown, soonest first.', 'wp-rig')}
						value={maxEvents}
						onChange={(value) => setAttributes({ maxEvents: value })}
						min={1}
						max={10}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{hasContent ? (
					<PreviewCard title={stripHtmlText(title) || __('Upcoming events', 'wp-rig')} style={{ maxWidth: '780px' }}>
						<div style={{ display: 'grid', gap: '10px' }}>
							{events && events.length > 0 ? (
								<ul style={{ padding: 0, margin: 0, listStyle: 'none', display: 'grid', gap: '10px' }}>
									{events.map((event, index) => {
										const imageUrl = event._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
										const excerpt = stripHtmlText(event.excerpt?.rendered || event.excerpt || '');
										const eventDate = formatEventDate(event.meta?.event_date || '');

										return (
											<li key={event.id} style={{ display: 'flex', gap: '10px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' }}>
												<PreviewThumb src={imageUrl} alt={event.title?.rendered || ''} size={48} />
												<div style={{ flex: 1, minWidth: 0, display: 'grid', gap: '6px' }}>
													<PreviewField label={__('Event', 'wp-rig')} value={event.title?.rendered || __('Untitled event', 'wp-rig')} maxLines={1} />
													{eventDate ? <PreviewField label={__('Date', 'wp-rig')} value={eventDate} maxLines={1} /> : null}
													{excerpt ? <PreviewField label={__('Excerpt', 'wp-rig')} value={excerpt} maxLines={2} /> : null}
												</div>
											</li>
										);
									})}
								</ul>
							) : (
								<PreviewEmptyState title={__('No upcoming events found', 'wp-rig')} message={__('Publish event posts with a future date to populate this preview.', 'wp-rig')} />
							)}
						</div>
					</PreviewCard>
				) : (
					<PreviewEmptyState title={__('No event content yet', 'wp-rig')} message={__('Add a section title or publish events to preview this block.', 'wp-rig')} />
				)}
			</div>
		</>
	);
}
