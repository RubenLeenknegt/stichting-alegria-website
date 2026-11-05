<?php
/**
 * Scrolllint: a decorative strip of images that scrolls continuously (Swiper, started by view.js).
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 *
 * @package alegria
 */

$title  = $attributes['title'] ?? '';
$images = ! empty( $attributes['images'] ) && is_array( $attributes['images'] ) ? $attributes['images'] : array();

if ( empty( $images ) ) {
	return;
}

// Swiper's loop needs more slides than fit on screen, so repeat short lists.
$slides = $images;
while ( count( $slides ) < 16 ) {
	$slides = array_merge( $slides, $images );
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'scrolllint alignfull' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( ! empty( $title ) ) : ?>
		<h2 class="scrolllint-heading"><?php echo wp_kses_post( $title ); ?></h2>
	<?php endif; ?>

	<?php // Decorative: the strip is aria-hidden, so the images get an empty alt. ?>
	<div class="scrolllint__slider swiper" aria-hidden="true">
		<div class="swiper-wrapper">
			<?php foreach ( $slides as $image ) : ?>
				<div class="swiper-slide scrolllint__item">
					<?php
					echo alegria_block_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						(int) ( $image['id'] ?? 0 ),
						(string) ( $image['url'] ?? '' ),
						'',
						'alegria-logo',
						array(
							'class'     => 'scrolllint__image',
							'alt'       => '',
							'draggable' => 'false',
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
