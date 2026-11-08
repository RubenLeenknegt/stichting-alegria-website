<?php
$images = array(
	'image-top-left'  => wp_get_attachment_image( (int) ( $attributes['topLeftImageId'] ?? 0 ), 'alegria-square' ),
	'image-top-right' => wp_get_attachment_image( (int) ( $attributes['topRightImageId'] ?? 0 ), 'alegria-square' ),
	'image-bottom'    => wp_get_attachment_image( (int) ( $attributes['bottomImageId'] ?? 0 ), 'alegria-wide' ),
);

$title        = $attributes['title'] ?? '';
$text_block_1 = $attributes['textBlock1'] ?? '';
$subtitle     = $attributes['subtitle'] ?? '';
$text_block_2 = $attributes['textBlock2'] ?? '';
$button       = alegria_button( $attributes['buttonText'] ?? '', $attributes['buttonUrl'] ?? '' );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'about-us-block' ) ); ?>>
	<div class="about-us-container">
		<div class="about-us-images">
			<div class="image-grid">
				<?php foreach ( array_filter( $images ) as $position => $image ) : ?>
					<div class="image-grid-item <?php echo esc_attr( $position ); ?>">
						<?php echo $image; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

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

			<?php if ( $button ) : ?>
				<div class="about-us-button"><?php echo $button; ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
