import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { PreviewCard, PreviewField } from '../_shared/editor-preview';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<PreviewCard title={ __( 'Deel dit bericht', 'alegria' ) } style={ { maxWidth: '780px' } }>
				<PreviewField label={ __( 'Tekst', 'alegria' ) } value={ __( 'Deel deze post:', 'alegria' ) } maxLines={ 1 } />
				<PreviewField
					label={ __( 'Knoppen', 'alegria' ) }
					value={ __( 'Facebook, LinkedIn en link kopiëren. De links worden automatisch gemaakt.', 'alegria' ) }
					maxLines={ 2 }
				/>
			</PreviewCard>
		</div>
	);
}
