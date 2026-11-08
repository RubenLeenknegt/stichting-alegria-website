<?php
$alegria_cf7_shortcode = '[contact-form-7 id="2ba0a3a" title="Contact formulier"]';

$title      = $attributes['title'] ?? '';
$intro_text = $attributes['introText'] ?? '';
$body_text  = $attributes['bodyText'] ?? '';
$form       = shortcode_exists( 'contact-form-7' ) ? do_shortcode( $alegria_cf7_shortcode ) : '';

if ( ! $title && ! $intro_text && ! $body_text && ! $form ) {
	return;
}

?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'contact-form-block' ) ); ?>>
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

			<?php get_template_part( 'template-parts/social-links', null, array( 'prefix' => 'contact-form' ) ); ?>
		</div>

		<?php if ( $form ) : ?>
			<div class="contact-form-form">
				<?php echo $form; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
