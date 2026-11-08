<?php
$alegria_newsletter_shortcode = '[contact-form-7 id="fdcb489" title="Nieuwsbrief"]';

if ( ! shortcode_exists( 'contact-form-7' ) ) {
	return;
}

$title = alegria_setting( 'alegria_popup_title' );
$text  = alegria_setting( 'alegria_popup_text' );

if ( ! $title ) {
	$title = __( 'Schrijf je in voor de nieuwsbrief', 'alegria' );
}
?>
<dialog id="nieuwsbrief-popup" class="newsletter-popup" aria-labelledby="nieuwsbrief-popup-title">
	<div class="newsletter-popup-inner">
		<button type="button" class="newsletter-popup-close" data-newsletter-popup-close>
			<?php echo alegria_svg( 'close' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Sluiten', 'alegria' ); ?></span>
		</button>

		<h2 id="nieuwsbrief-popup-title" class="newsletter-popup-title"><?php echo esc_html( $title ); ?></h2>

		<?php if ( $text ) : ?>
			<div class="newsletter-popup-text"><?php echo wpautop( esc_html( $text ) ); ?></div>
		<?php endif; ?>

		<?php echo do_shortcode( $alegria_newsletter_shortcode ); ?>
	</div>
</dialog>
