<?php
/**
 * Contact form block (wp-rig/contact-form).
 *
 * Left: title, two texts and the social media links from the Customizer (Footer section).
 * Right: the Contact Form 7 form "Contact formulier". Its fields and mails are set in wp-admin
 * (Contact → Contact Forms); the templates are in cf7-templates.md next to this file.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$alegria_cf7_shortcode = '[contact-form-7 id="2ba0a3a" title="Contact formulier"]';

$title      = $attributes['title'] ?? '';
$intro_text = $attributes['introText'] ?? '';
$body_text  = $attributes['bodyText'] ?? '';
$form       = shortcode_exists( 'contact-form-7' ) ? do_shortcode( $alegria_cf7_shortcode ) : '';

// The same social links as the footer (Customizer → Footer); empty ones are left out.
// Icons: Font Awesome Free brand icons (CC BY 4.0), assets/svg/{instagram,facebook-f,linkedin-in}.svg.
$socials = array_filter(
	array(
		'instagram'   => array( 'Instagram', alegria_setting( 'alegria_social_instagram' ) ),
		'facebook-f'  => array( 'Facebook', alegria_setting( 'alegria_social_facebook' ) ),
		'linkedin-in' => array( 'LinkedIn', alegria_setting( 'alegria_social_linkedin' ) ),
	),
	static fn( array $social ): bool => '' !== $social[1]
);

if ( ! $title && ! $intro_text && ! $body_text && ! $socials && ! $form ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'contact-form-block' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="contact-form-container">
		<div class="contact-form-info">
			<?php if ( $title ) : ?>
				<h2 class="contact-form-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $intro_text ) : ?>
				<div class="contact-form-text"><?php echo wp_kses_post( wpautop( $intro_text ) ); ?></div>
			<?php endif; ?>

			<?php if ( $body_text ) : ?>
				<div class="contact-form-text"><?php echo wp_kses_post( wpautop( $body_text ) ); ?></div>
			<?php endif; ?>

			<?php if ( $socials ) : ?>
				<ul class="contact-form-social">
					<?php foreach ( $socials as $icon => list( $name, $url ) ) : ?>
						<li>
							<a class="contact-form-social-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
								<?php echo alegria_svg( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="screen-reader-text">
									<?php
									/* translators: %s: social network name. */
									echo esc_html( sprintf( __( '%s (opent in nieuw venster)', 'alegria' ), $name ) );
									?>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( $form ) : ?>
			<div class="contact-form-form">
				<?php echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 output. ?>
			</div>
		<?php endif; ?>
	</div>
</div>
