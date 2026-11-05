<?php
/**
 * About Us Block Template
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

// Get attributes
$top_left_image      = alegria_block_image( (int) ( $attributes['topLeftImageId'] ?? 0 ), $attributes['topLeftImageUrl'] ?? '', $attributes['topLeftImageAlt'] ?? '', 'alegria-square' );
$top_right_image     = alegria_block_image( (int) ( $attributes['topRightImageId'] ?? 0 ), $attributes['topRightImageUrl'] ?? '', $attributes['topRightImageAlt'] ?? '', 'alegria-square' );
$bottom_image        = alegria_block_image( (int) ( $attributes['bottomImageId'] ?? 0 ), $attributes['bottomImageUrl'] ?? '', $attributes['bottomImageAlt'] ?? '', 'alegria-wide' );
$title               = $attributes['title'] ?? '';
$text_block_1        = $attributes['textBlock1'] ?? '';
$subtitle            = $attributes['subtitle'] ?? '';
$text_block_2        = $attributes['textBlock2'] ?? '';
$button_text         = $attributes['buttonText'] ?? '';
$button_url          = $attributes['buttonUrl'] ?? '';

$primary_button_icon = alegria_svg( 'arrow_right' );

// Build wrapper attributes
$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'about-us-block' ) );
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="about-us-container">
		<!-- Images Column -->
		<div class="about-us-images">
			<div class="image-grid">
				<?php if ( $top_left_image ) : ?>
					<div class="image-grid-item image-top-left">
						<?php echo $top_left_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( $top_right_image ) : ?>
					<div class="image-grid-item image-top-right">
						<?php echo $top_right_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( $bottom_image ) : ?>
					<div class="image-grid-item image-bottom">
						<?php echo $bottom_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Content Column -->
		<div class="about-us-content">
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
						<?php if ( ! empty( $primary_button_icon ) ) : ?>
							<span class="btn-icon" aria-hidden="true"><?php echo $primary_button_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
