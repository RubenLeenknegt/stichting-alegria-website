<?php
/**
 * Render callback for wp-rig/img.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 */

$image = alegria_block_image(
	(int) ( $attributes['imageId'] ?? 0 ),
	$attributes['imageUrl'] ?? '',
	$attributes['imageAlt'] ?? '',
	'full',
	array( 'class' => 'img-block__image' )
);

if ( ! $image ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'img-block',
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
