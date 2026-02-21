<?php
/**
 * Cards Block Template
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
$section_title = $attributes['sectionTitle'] ?? '';

// Card 1
$card1_image_url = $attributes['card1ImageUrl'] ?? '';
$card1_image_alt = $attributes['card1ImageAlt'] ?? '';
$card1_title     = $attributes['card1Title'] ?? '';
$card1_text      = $attributes['card1Text'] ?? '';
$card1_url       = $attributes['card1Url'] ?? '';

// Card 2
$card2_image_url = $attributes['card2ImageUrl'] ?? '';
$card2_image_alt = $attributes['card2ImageAlt'] ?? '';
$card2_title     = $attributes['card2Title'] ?? '';
$card2_text      = $attributes['card2Text'] ?? '';
$card2_url       = $attributes['card2Url'] ?? '';

// Card 3
$card3_image_url = $attributes['card3ImageUrl'] ?? '';
$card3_image_alt = $attributes['card3ImageAlt'] ?? '';
$card3_title     = $attributes['card3Title'] ?? '';
$card3_text      = $attributes['card3Text'] ?? '';
$card3_url       = $attributes['card3Url'] ?? '';

// Build wrapper attributes
$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'cards-block' ),
	$attributes
);

// Helper function to render a card
function render_card( $image_url, $image_alt, $title, $text, $url ) {
	if ( ! $image_url && ! $title && ! $text ) {
		return;
	}

	$tag = $url ? 'a' : 'div';
	$href_attr = $url ? sprintf( ' href="%s"', esc_url( $url ) ) : '';
	$bg_style = $image_url ? sprintf( ' style="background-image: url(%s);"', esc_url( $image_url ) ) : '';
	?>
	<<?php echo $tag; ?> class="card-item"<?php echo $href_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="card-overlay"></div>
	<div class="card-content">
		<?php if ( $title ) : ?>
			<h3 class="card-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<div class="card-text">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		<?php endif; ?>
	</div>
	</<?php echo $tag; ?>>
	<?php
}
?>

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="cards-container">
		<?php if ( $section_title ) : ?>
			<h2 class="cards-section-title"><?php echo esc_html( $section_title ); ?></h2>
		<?php endif; ?>

		<div class="cards-grid">
			<?php render_card( $card1_image_url, $card1_image_alt, $card1_title, $card1_text, $card1_url ); ?>
			<?php render_card( $card2_image_url, $card2_image_alt, $card2_title, $card2_text, $card2_url ); ?>
			<?php render_card( $card3_image_url, $card3_image_alt, $card3_title, $card3_text, $card3_url ); ?>
		</div>
	</div>
</div>
