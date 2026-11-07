<?php
/**
 * Newsletter popup: title and text from the Customizer (Nieuwsbrief-popup) and the
 * Contact Form 7 form "Nieuwsbrief". Printed on every page, closed until a link to
 * #nieuwsbrief-popup is clicked (assets/js/newsletter-popup.js).
 *
 * The form template and mails are in the database; see newsletter-popup-cf7.md next to this file.
 *
 * @package alegria
 */

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
			<?php echo alegria_svg( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Sluiten', 'alegria' ); ?></span>
		</button>

		<h2 id="nieuwsbrief-popup-title" class="newsletter-popup-title"><?php echo esc_html( $title ); ?></h2>

		<?php if ( $text ) : ?>
			<div class="newsletter-popup-text"><?php echo wpautop( esc_html( $text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>

		<?php echo do_shortcode( $alegria_newsletter_shortcode ); ?>
	</div>
</dialog>
