<?php
/**
 * Feature Split Block Template
 *
 * @package wp_rig
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use function WP_Rig\WP_Rig\wp_rig;

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block      = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

$title     = $attributes['title'] ?? '';
$body_text = $attributes['bodyText'] ?? '';
$image_url = $attributes['imageUrl'] ?? '';
$image_alt = $attributes['imageAlt'] ?? '';
$cta_text  = $attributes['ctaText'] ?? '';
$cta_url   = $attributes['ctaUrl'] ?? '';

// Required fields: image + complete CTA.
if ( ! $image_url || ! $cta_text || ! $cta_url ) {
	return;
}

$primary_button_icon = wp_rig()->get_cta_arrow_svg();

$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'feature-split-block' ),
	$attributes
);

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
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="feature-split-image">
		</div>
	</div>
</div>
