<?php
$title     = $attributes['title'] ?? '';
$body_text = $attributes['bodyText'] ?? '';
$image     = wp_get_attachment_image( (int) ( $attributes['imageId'] ?? 0 ), 'alegria-feature', false, array( 'class' => 'txt-img-image' ) );
$button    = alegria_button( $attributes['ctaText'] ?? '', $attributes['ctaUrl'] ?? '' );

if ( ! $image || ! $button ) {
	return;
}

$block_class = ! empty( $attributes['imageLeft'] ) ? 'txt-img-block txt-img-block--image-left' : 'txt-img-block';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => $block_class ) ); ?>>
	<div class="txt-img-container">
		<div class="txt-img-content">
			<?php if ( $title ) : ?>
				<h2 class="txt-img-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $body_text ) : ?>
				<div class="txt-img-text">
					<?php echo wp_kses_post( $body_text ); ?>
				</div>
			<?php endif; ?>

			<div class="txt-img-cta"><?php echo $button; ?></div>
		</div>

		<div class="txt-img-media">
			<?php echo $image; ?>
		</div>
	</div>
</div>
