<?php
$heading               = $attributes['heading'] ?? '';
$subheading            = $attributes['subheading'] ?? '';
$background_image_url  = wp_get_attachment_image_url( (int) ( $attributes['backgroundImageId'] ?? 0 ), 'alegria-hero' );
$primary_button        = alegria_button( $attributes['primaryButtonText'] ?? '', $attributes['primaryButtonUrl'] ?? '' );
$secondary_button_text = $attributes['secondaryButtonText'] ?? '';
$secondary_button_url  = $attributes['secondaryButtonUrl'] ?? '';
$has_secondary_button  = $secondary_button_text && $secondary_button_url;

$bg_style = $background_image_url ? ' style="background-image: url(' . esc_url( $background_image_url ) . ');"' : '';
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'hero-block' ) ); ?><?php echo $bg_style; ?>>
	<div class="hero-container">
		<div class="hero-content">
			<?php if ( $heading ) : ?>
				<h1 class="hero-heading"><?php echo esc_html( $heading ); ?></h1>
			<?php endif; ?>

			<?php if ( $subheading ) : ?>
				<p class="hero-subheading"><?php echo esc_html( $subheading ); ?></p>
			<?php endif; ?>

			<?php if ( $primary_button || $has_secondary_button ) : ?>
				<div class="hero-buttons">
					<?php echo $primary_button; ?>

					<?php if ( $has_secondary_button ) : ?>
						<a href="<?php echo esc_url( $secondary_button_url ); ?>" class="btn-secondary">
							<?php echo esc_html( $secondary_button_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
