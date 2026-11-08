<?php
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
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'scrolllint' ) ); ?>>
	<?php if ( ! empty( $title ) ) : ?>
		<h2 class="scrolllint-heading"><?php echo wp_kses_post( $title ); ?></h2>
	<?php endif; ?>

	<div class="scrolllint__slider swiper" aria-hidden="true">
		<div class="swiper-wrapper">
			<?php foreach ( $slides as $image ) : ?>
				<div class="swiper-slide scrolllint__item">
					<?php
					echo wp_get_attachment_image(
						(int) ( $image['id'] ?? 0 ),
						'alegria-logo',
						false,
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
