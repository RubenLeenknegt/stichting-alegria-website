<?php
/**
 * Render callback for wp-rig/scrolllint.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 */

$title  = isset( $attributes['title'] ) ? $attributes['title'] : '';
$images = ! empty( $attributes['images'] ) && is_array( $attributes['images'] ) ? $attributes['images'] : array();

if ( empty( $images ) ) {
	return;
}

// Duplicate the image list so the CSS animation can loop seamlessly.
$track_images = array_merge( $images, $images );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'scrolllint alignfull',
	)
);
?>
<div <?php echo $wrapper_attributes; ?>>
	<?php if ( ! empty( $title ) ) : ?>
		<h2 class="scrolllint-heading">
			<?php echo wp_kses_post( $title ); ?>
		</h2>
	<?php endif; ?>

	<div class="scrolllint__viewport">
		<div class="scrolllint__track" aria-hidden="true">
			<?php foreach ( $track_images as $image ) : ?>
				<div class="scrolllint__item">
					<?php
					// Decorative: the whole track is aria-hidden, so the alt text is left empty.
					echo alegria_block_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						(int) ( $image['id'] ?? 0 ),
						(string) ( $image['url'] ?? '' ),
						'',
						'medium',
						array(
							'class'     => 'scrolllint__image',
							'alt'       => '',
							'loading'   => 'lazy',
							'draggable' => 'false',
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
