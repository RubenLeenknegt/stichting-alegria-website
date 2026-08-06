<?php
/**
 * Render callback for wp-rig/img.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 */

$image_url = isset( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';
$image_alt = isset( $attributes['imageAlt'] ) ? $attributes['imageAlt'] : '';

if ( empty( $image_url ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'img-block',
	)
);
?>
<div <?php echo $wrapper_attributes; ?>>
	<img
		src="<?php echo esc_url( $image_url ); ?>"
		alt="<?php echo esc_attr( $image_alt ); ?>"
		class="img-block__image"
	/>
</div>
