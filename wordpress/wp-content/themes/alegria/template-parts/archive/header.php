<?php
/**
 * Header of the event and newsletter archives: title, text and an optional button,
 * from the Customizer (Archief: nieuwsbrieven / evenementen).
 *
 * The title falls back to the post type's name; empty text and an incomplete button are left out.
 * A button linking to #nieuwsbrief-popup opens the newsletter popup.
 *
 * @package alegria
 *
 * @var array $args {
 *     @type string $type 'newsletter' or 'event'.
 * }
 */

$prefix      = 'alegria_archive_' . ( $args['type'] ?? '' );
$title       = alegria_setting( $prefix . '_title' );
$text        = alegria_setting( $prefix . '_text' );
$button_text = alegria_setting( $prefix . '_button_text' );
$button_url  = alegria_setting( $prefix . '_button_url' );

if ( ! $title ) {
	$title = post_type_archive_title( '', false );
}
?>
<header class="archive-header">
	<h1 class="archive-title"><?php echo esc_html( $title ); ?></h1>

	<?php if ( $text ) : ?>
		<div class="archive-description"><?php echo wpautop( esc_html( $text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>

	<?php if ( $button_text && $button_url ) : ?>
		<a href="<?php echo esc_url( $button_url ); ?>" class="btn-primary archive-button">
			<?php echo esc_html( $button_text ); ?>
			<span class="btn-icon" aria-hidden="true"><?php echo alegria_svg( 'arrow_right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</a>
	<?php endif; ?>
</header>
