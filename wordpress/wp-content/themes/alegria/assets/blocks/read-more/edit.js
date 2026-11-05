import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { store as editorStore } from '@wordpress/editor';
import { LinkField } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

const COUNT = 3;

/**
 * Same selection as render.php: for events the upcoming ones (soonest first), then past ones (latest first).
 *
 * @param {Object[]} events Published events, with their meta.
 * @return {Object[]} Up to three events.
 */
function pickEvents( events ) {
	const today = new Date().toISOString().slice( 0, 10 );
	const dated = events.filter( ( event ) => event.meta?.event_date );
	const upcoming = dated
		.filter( ( event ) => event.meta.event_date >= today )
		.sort( ( a, b ) => a.meta.event_date.localeCompare( b.meta.event_date ) );
	const past = dated
		.filter( ( event ) => event.meta.event_date < today )
		.sort( ( a, b ) => b.meta.event_date.localeCompare( a.meta.event_date ) );

	return [ ...upcoming, ...past ].slice( 0, COUNT );
}

export default function Edit( { attributes, setAttributes } ) {
	const { title, linkText, linkUrl } = attributes;

	const blockProps = useBlockProps();

	const { postId, postType } = useSelect(
		( select ) => ( {
			postId: select( editorStore ).getCurrentPostId(),
			postType: select( editorStore ).getCurrentPostType(),
		} ),
		[]
	);
	const isEvent = postType === 'event';

	const posts = useSelect(
		( select ) => {
			if ( postType !== 'event' && postType !== 'newsletter' ) {
				return [];
			}
			const records = select( coreStore ).getEntityRecords( 'postType', postType, {
				per_page: isEvent ? 100 : COUNT,
				exclude: postId ? [ postId ] : [],
				orderby: 'date',
				order: 'desc',
				_embed: true,
				context: 'view',
				status: 'publish',
			} );
			if ( ! records ) {
				return null;
			}
			return isEvent ? pickEvents( records ) : records;
		},
		[ postId, postType, isEvent ]
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ isEvent ? __( 'bijv. Meer evenementen', 'alegria' ) : __( 'bijv. Meer nieuwsbrieven', 'alegria' ) }
					/>
					<TextControl
						label={ __( 'Linktekst', 'alegria' ) }
						value={ linkText }
						onChange={ ( value ) => setAttributes( { linkText: value } ) }
						placeholder={ isEvent ? __( 'bijv. Alle evenementen', 'alegria' ) : __( 'bijv. Alle nieuwsbrieven', 'alegria' ) }
					/>
					<LinkField url={ linkUrl } onChange={ ( url ) => setAttributes( { linkUrl: url } ) } />
					<p style={ { fontSize: '12px', color: '#666' } }>
						{ __( 'Zonder link gaat de linktekst naar het overzicht.', 'alegria' ) }
					</p>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Lees verder', 'alegria' ) } style={ { maxWidth: '780px' } }>
					<div style={ { display: 'grid', gap: '10px' } }>
						{ linkText ? <PreviewField label={ __( 'Linktekst', 'alegria' ) } value={ linkText } maxLines={ 1 } /> : null }
						<PreviewField
							label={ __( 'Selectie', 'alegria' ) }
							value={
								isEvent
									? __( 'Drie andere evenementen: eerst de aankomende, aangevuld met eerdere.', 'alegria' )
									: __( 'De drie nieuwste andere nieuwsbrieven.', 'alegria' )
							}
							maxLines={ 2 }
						/>
						{ posts && posts.length > 0 ? (
							<ul style={ { padding: 0, margin: 0, listStyle: 'none', display: 'grid', gap: '10px' } }>
								{ posts.map( ( post, index ) => (
									<li
										key={ post.id }
										style={ { display: 'flex', gap: '10px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' } }
									>
										<PreviewThumb src={ post._embedded?.[ 'wp:featuredmedia' ]?.[ 0 ]?.source_url || '' } alt={ post.title?.rendered || '' } size={ 48 } />
										<div style={ { flex: 1, minWidth: 0, display: 'grid', gap: '6px' } }>
											<PreviewField label={ isEvent ? post.meta?.event_date || __( 'Evenement', 'alegria' ) : __( 'Nieuwsbrief', 'alegria' ) } value={ post.title?.rendered || __( 'Zonder titel', 'alegria' ) } maxLines={ 1 } />
										</div>
									</li>
								) ) }
							</ul>
						) : (
							<PreviewEmptyState
								title={ isEvent ? __( 'Nog geen andere evenementen', 'alegria' ) : __( 'Nog geen andere nieuwsbrieven', 'alegria' ) }
								message={ __( 'Zonder andere berichten wordt dit blok op de website niet getoond.', 'alegria' ) }
							/>
						) }
					</div>
				</PreviewCard>
			</div>
		</>
	);
}
