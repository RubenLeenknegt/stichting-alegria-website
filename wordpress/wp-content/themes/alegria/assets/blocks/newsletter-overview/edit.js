import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { LinkField } from '../_shared/controls';
import { PreviewCard, PreviewField, PreviewThumb, PreviewEmptyState, stripHtmlText } from '../_shared/editor-preview';

export default function Edit( { attributes, setAttributes } ) {
	const { title, linkText, linkUrl } = attributes;

	const blockProps = useBlockProps();

	// Same selection as render.php: the three most recent newsletters.
	const posts = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'postType', 'newsletter', {
				per_page: 3,
				orderby: 'date',
				order: 'desc',
				_embed: true,
				context: 'view',
				status: 'publish',
			} ),
		[]
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sectie', 'alegria' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Sectietitel', 'alegria' ) }
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'bijv. Laatste nieuwsbrieven', 'alegria' ) }
					/>
					<TextControl
						label={ __( 'Linktekst', 'alegria' ) }
						value={ linkText }
						onChange={ ( value ) => setAttributes( { linkText: value } ) }
						placeholder={ __( 'bijv. Alle nieuwsbrieven', 'alegria' ) }
					/>
					<LinkField url={ linkUrl } onChange={ ( url ) => setAttributes( { linkUrl: url } ) } />
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<PreviewCard title={ stripHtmlText( title ) || __( 'Nieuwsbrieven', 'alegria' ) } style={ { maxWidth: '780px' } }>
					<div style={ { display: 'grid', gap: '10px' } }>
						{ linkText ? <PreviewField label={ __( 'Linktekst', 'alegria' ) } value={ linkText } maxLines={ 1 } /> : null }
						{ linkUrl ? <PreviewField label={ __( 'Link', 'alegria' ) } value={ linkUrl } maxLines={ 1 } /> : null }
						{ posts && posts.length > 0 ? (
							<ul style={ { padding: 0, margin: 0, listStyle: 'none', display: 'grid', gap: '10px' } }>
								{ posts.map( ( post, index ) => {
									const imageUrl = post._embedded?.[ 'wp:featuredmedia' ]?.[ 0 ]?.source_url || '';
									const excerpt = stripHtmlText( post.excerpt?.rendered || '' );

									return (
										<li
											key={ post.id }
											style={ { display: 'flex', gap: '10px', alignItems: 'flex-start', paddingTop: index > 0 ? '8px' : 0, borderTop: index > 0 ? '1px solid #f0f0f0' : 'none' } }
										>
											<PreviewThumb src={ imageUrl } alt={ post.title?.rendered || '' } size={ 48 } />
											<div style={ { flex: 1, minWidth: 0, display: 'grid', gap: '6px' } }>
												<PreviewField label={ __( 'Nieuwsbrief', 'alegria' ) } value={ post.title?.rendered || __( 'Zonder titel', 'alegria' ) } maxLines={ 1 } />
												{ excerpt ? <PreviewField label={ __( 'Samenvatting', 'alegria' ) } value={ excerpt } maxLines={ 2 } /> : null }
											</div>
										</li>
									);
								} ) }
							</ul>
						) : (
							<PreviewEmptyState
								title={ __( 'Nog geen nieuwsbrieven', 'alegria' ) }
								message={ __( 'Zodra er nieuwsbrieven zijn gepubliceerd, verschijnen de drie nieuwste hier.', 'alegria' ) }
							/>
						) }
					</div>
				</PreviewCard>
			</div>
		</>
	);
}
