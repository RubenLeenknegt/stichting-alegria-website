<?php
$image = wp_get_attachment_image( (int) ( $attributes['imageId'] ?? 0 ), 'alegria-content', false, array( 'class' => 'img-block__image' ) );

if ( ! $image ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'img-block' ) ); ?>>
	<?php echo $image; ?>
</div>
