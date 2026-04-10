<?php
/**
 * Hero Block Template
 *
 * @package wp_rig
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use function WP_Rig\WP_Rig\wp_rig;

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

// Get attributes
$heading               = $attributes['heading'] ?? '';
$subheading            = $attributes['subheading'] ?? '';
$background_image_url  = $attributes['backgroundImageUrl'] ?? '';
$background_image_alt  = $attributes['backgroundImageAlt'] ?? '';
$primary_button_text   = $attributes['primaryButtonText'] ?? '';
$primary_button_url    = $attributes['primaryButtonUrl'] ?? '';
$secondary_button_text = $attributes['secondaryButtonText'] ?? '';
$secondary_button_url  = $attributes['secondaryButtonUrl'] ?? '';

$primary_button_icon = wp_rig()->get_cta_arrow_svg();

// Build wrapper attributes
$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'hero-block', 'overlay-dark' ),
	$attributes
);

// Inline background style
$bg_style = '';
if ( $background_image_url ) {
	$bg_style = sprintf(
		' style="background-image: url(%s);"',
		esc_url( $background_image_url )
	);
}
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="hero-content">
		<?php if ( $heading ) : ?>
			<h1 class="hero-heading"><?php echo esc_html( $heading ); ?></h1>
		<?php endif; ?>

		<?php if ( $subheading ) : ?>
			<p class="hero-subheading"><?php echo esc_html( $subheading ); ?></p>
		<?php endif; ?>

		<?php if ( $primary_button_text || $secondary_button_text ) : ?>
			<div class="hero-buttons">
				<?php if ( $primary_button_text && $primary_button_url ) : ?>
					<a href="<?php echo esc_url( $primary_button_url ); ?>" class="btn-primary">
						<?php echo esc_html( $primary_button_text ); ?>
						<?php if ( ! empty( $primary_button_icon ) ) : ?>
							<span class="btn-icon" aria-hidden="true"><?php echo $primary_button_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>

				<?php if ( $secondary_button_text && $secondary_button_url ) : ?>
					<a href="<?php echo esc_url( $secondary_button_url ); ?>" class="btn-secondary">
						<?php echo esc_html( $secondary_button_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
