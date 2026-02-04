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

// Get our custom attributes
$heading = $attributes['heading'] ?? '';
$subheading = $attributes['subheading'] ?? '';
$background_image_url = $attributes['backgroundImageUrl'] ?? '';
$background_image_alt = $attributes['backgroundImageAlt'] ?? '';

// Build wrapper attributes
$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'hero-block' ),
	$attributes
);
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $background_image_url ) : ?>
	<div class="hero-background" style="background-image: url('<?php echo esc_url( $background_image_url ); ?>');">
		<?php endif; ?>

		<div class="hero-content">
			<?php if ( $heading ) : ?>
				<h1 class="hero-heading"><?php echo esc_html( $heading ); ?></h1>
			<?php endif; ?>

			<?php if ( $subheading ) : ?>
				<p class="hero-subheading"><?php echo esc_html( $subheading ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $background_image_url ) : ?>
	</div>
<?php endif; ?>
</div>
