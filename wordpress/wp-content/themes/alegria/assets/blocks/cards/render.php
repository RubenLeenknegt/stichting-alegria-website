<?php
$section_title = $attributes['sectionTitle'] ?? '';
$section_text  = $attributes['sectionText'] ?? '';
$cta           = alegria_button( $attributes['ctaText'] ?? '', $attributes['ctaUrl'] ?? '', 'btn-primary cards-cta' );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'cards-block' ) ); ?>>
	<div class="cards-container">
		<?php if ( $section_title || $section_text || $cta ) : ?>
			<div class="cards-header">
				<?php if ( $section_title || $section_text ) : ?>
					<div class="cards-intro">
						<?php if ( $section_title ) : ?>
							<h2 class="cards-section-title"><?php echo esc_html( $section_title ); ?></h2>
						<?php endif; ?>

						<?php if ( $section_text ) : ?>
							<div class="cards-section-text">
								<?php echo wp_kses_post( $section_text ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php echo $cta; ?>
			</div>
		<?php endif; ?>

		<div class="cards-grid">
			<?php
			foreach ( array( 1, 2, 3 ) as $n ) :
				$image_url  = wp_get_attachment_image_url( (int) ( $attributes[ "card{$n}ImageId" ] ?? 0 ), 'alegria-portrait' );
				$card_title = $attributes[ "card{$n}Title" ] ?? '';
				$card_text  = $attributes[ "card{$n}Text" ] ?? '';
				$card_url   = $attributes[ "card{$n}Url" ] ?? '';

				if ( ! $image_url && ! $card_title && ! $card_text ) {
					continue;
				}

				$tag = $card_url ? 'a' : 'div';
				?>
				<<?php echo $tag; ?> class="card-item"<?php echo $card_url ? ' href="' . esc_url( $card_url ) . '"' : ''; ?><?php echo $image_url ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : ''; ?>>
					<div class="card-overlay"></div>
					<div class="card-content">
						<?php if ( $card_title ) : ?>
							<h3 class="card-title"><?php echo esc_html( $card_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $card_text ) : ?>
							<div class="card-text">
								<?php echo wp_kses_post( $card_text ); ?>
							</div>
						<?php endif; ?>
					</div>
				</<?php echo $tag; ?>>
			<?php endforeach; ?>
		</div>
	</div>
</div>
