<?php
/**
 * About Us Block Template
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
$top_left_image_url  = $attributes['topLeftImageUrl'] ?? '';
$top_left_image_alt  = $attributes['topLeftImageAlt'] ?? '';
$top_right_image_url = $attributes['topRightImageUrl'] ?? '';
$top_right_image_alt = $attributes['topRightImageAlt'] ?? '';
$bottom_image_url    = $attributes['bottomImageUrl'] ?? '';
$bottom_image_alt    = $attributes['bottomImageAlt'] ?? '';
$pre_title           = $attributes['preTitle'] ?? '';
$title               = $attributes['title'] ?? '';
$text_block_1        = $attributes['textBlock1'] ?? '';
$subtitle            = $attributes['subtitle'] ?? '';
$text_block_2        = $attributes['textBlock2'] ?? '';
$button_text         = $attributes['buttonText'] ?? '';
$button_url          = $attributes['buttonUrl'] ?? '';

// Build wrapper attributes
$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'about-us-block' ),
	$attributes
);
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="about-us-container">
		<!-- Images Column -->
		<div class="about-us-images">
			<div class="image-grid">
				<?php if ( $top_left_image_url ) : ?>
					<div class="image-grid-item image-top-left">
						<img src="<?php echo esc_url( $top_left_image_url ); ?>" alt="<?php echo esc_attr( $top_left_image_alt ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( $top_right_image_url ) : ?>
					<div class="image-grid-item image-top-right">
						<img src="<?php echo esc_url( $top_right_image_url ); ?>" alt="<?php echo esc_attr( $top_right_image_alt ); ?>">
					</div>
				<?php endif; ?>

				<?php if ( $bottom_image_url ) : ?>
					<div class="image-grid-item image-bottom">
						<img src="<?php echo esc_url( $bottom_image_url ); ?>" alt="<?php echo esc_attr( $bottom_image_alt ); ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Content Column -->
		<div class="about-us-content">
			<?php if ( $pre_title ) : ?>
				<p class="about-us-pretitle"><?php echo esc_html( $pre_title ); ?></p>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<h2 class="about-us-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $text_block_1 ) : ?>
				<div class="about-us-text">
					<?php echo wp_kses_post( $text_block_1 ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $subtitle ) : ?>
				<h3 class="about-us-subtitle"><?php echo esc_html( $subtitle ); ?></h3>
			<?php endif; ?>

			<?php if ( $text_block_2 ) : ?>
				<div class="about-us-text">
					<?php echo wp_kses_post( $text_block_2 ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $button_text && $button_url ) : ?>
				<div class="about-us-button">
					<a href="<?php echo esc_url( $button_url ); ?>" class="btn-primary">
						<?php echo esc_html( $button_text ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
