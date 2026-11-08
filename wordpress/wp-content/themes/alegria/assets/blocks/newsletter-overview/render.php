<?php
$title     = $attributes['title'] ?? '';
$body_text = $attributes['bodyText'] ?? '';
$button    = alegria_button(
	$attributes['linkText'] ?? '',
	( $attributes['linkUrl'] ?? '' ) ?: get_post_type_archive_link( 'newsletter' ),
	'btn-primary newsletter-overview-cta'
);

$newsletter_query = new WP_Query(
	array(
		'post_type'      => 'newsletter',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	)
);
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'newsletter-overview-block' ) ); ?>>
	<div class="newsletter-overview-container">
		<?php if ( $title || $body_text || $button ) : ?>
			<div class="newsletter-overview-header">
				<?php if ( $title || $body_text ) : ?>
					<div class="newsletter-overview-intro">
						<?php if ( $title ) : ?>
							<h2 class="newsletter-overview-title"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>

						<?php if ( $body_text ) : ?>
							<div class="newsletter-overview-text">
								<?php echo wp_kses_post( $body_text ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php echo $button; ?>
			</div>
		<?php endif; ?>

		<?php if ( $newsletter_query->have_posts() ) : ?>
			<div class="newsletter-overview-grid">
				<?php
				while ( $newsletter_query->have_posts() ) :
					$newsletter_query->the_post();
					get_template_part( 'template-parts/post-card' );
				endwhile;
				?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
wp_reset_postdata();
