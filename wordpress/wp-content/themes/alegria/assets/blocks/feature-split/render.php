<?php
/**
 * Feature Split Block Template
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block      = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

$title     = $attributes['title'] ?? '';
$body_text = $attributes['bodyText'] ?? '';
$image = alegria_block_image( (int) ( $attributes['imageId'] ?? 0 ), $attributes['imageUrl'] ?? '', $attributes['imageAlt'] ?? '', 'large', array( 'class' => 'feature-split-image' ) );
$cta_text  = $attributes['ctaText'] ?? '';
$cta_url   = $attributes['ctaUrl'] ?? '';

// Required fields: image + complete CTA.
if ( ! $image || ! $cta_text || ! $cta_url ) {
	return;
}

$primary_button_icon = alegria_svg( 'arrow_right' );

$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'feature-split-block' ) );

?>
<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="feature-split-container">
		<div class="feature-split-content">
			<?php if ( $title ) : ?>
				<h2 class="feature-split-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $body_text ) : ?>
				<div class="feature-split-text">
					<?php echo wp_kses_post( $body_text ); ?>
				</div>
			<?php endif; ?>

			<div class="feature-split-cta">
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn-primary">
					<?php echo esc_html( $cta_text ); ?>
					<?php if ( ! empty( $primary_button_icon ) ) : ?>
						<span class="btn-icon" aria-hidden="true"><?php echo $primary_button_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</a>
			</div>
		</div>

		<div class="feature-split-media">
			<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>
