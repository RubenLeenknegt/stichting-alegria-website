<?php
$post_id   = (int) ( $block->context['postId'] ?? get_the_ID() );
$permalink = get_permalink( $post_id );

if ( ! $permalink ) {
	return;
}

$share_label = 'event' === get_post_type( $post_id )
	? __( 'Deel dit evenement:', 'alegria' )
	: __( 'Deel deze nieuwsbrief:', 'alegria' );

$share_links = array(
	'facebook-f'  => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $permalink ) ),
	'linkedin-in' => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $permalink ) ),
);
?>
<div
	<?php echo get_block_wrapper_attributes( array( 'class' => 'share-block' ) ); ?>
	data-url="<?php echo esc_url( $permalink ); ?>"
>
	<div class="share-container">
		<p class="share-label" id="share-label-<?php echo esc_attr( (string) $post_id ); ?>"><?php echo esc_html( $share_label ); ?></p>

		<ul class="share-links" aria-labelledby="share-label-<?php echo esc_attr( (string) $post_id ); ?>">
			<?php foreach ( $share_links as $icon => list( $name, $url ) ) : ?>
				<li>
					<a class="share-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
						<span class="share-icon share-icon-brand"><?php echo alegria_svg( $icon ); ?></span>
						<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Delen op %s (opent in nieuw venster)', 'alegria' ), $name ) ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
			<li>
				<button type="button" class="share-link share-copy" data-message="<?php esc_attr_e( 'Link gekopieerd!', 'alegria' ); ?>">
					<span class="share-icon share-icon-copy"><?php echo alegria_svg( 'copy' ); ?></span>
					<span class="share-icon share-icon-check"><?php echo alegria_svg( 'check' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Link kopiëren', 'alegria' ); ?></span>
				</button>
			</li>
		</ul>

		<p class="share-status screen-reader-text" role="status" aria-live="polite"></p>
	</div>
</div>
