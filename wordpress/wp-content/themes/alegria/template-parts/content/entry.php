<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php if ( is_singular() ) : ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	<?php else : ?>
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>
</article>
