<?php
// A newsletter or event card, for use in the loop. Styled by the newsletter-overview block.
$date = alegria_display_date( get_the_ID() );
?>
<a href="<?php the_permalink(); ?>" class="newsletter-overview-card">
	<div class="newsletter-overview-card-image">
		<?php the_post_thumbnail( 'alegria-card' ); ?>
	</div>

	<div class="newsletter-overview-card-content">
		<?php if ( $date['text'] ) : ?>
			<time class="post-date" datetime="<?php echo esc_attr( $date['iso'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
		<?php endif; ?>

		<h3 class="newsletter-overview-card-title"><?php echo esc_html( get_the_title() ); ?></h3>

		<div class="newsletter-overview-card-excerpt">
			<?php echo esc_html( get_the_excerpt() ); ?>
		</div>
	</div>
</a>
