<?php
/**
 * Single event or newsletter.
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<?php
				// Posts made from the newsletter/event template print their own header (title included) with the
				// post-header block. Older posts without it get this plain title.
				if ( ! has_block( 'wp-rig/post-header' ) ) :
					?>
					<header class="single-header">
						<?php the_title( '<h1 class="single-title">', '</h1>' ); ?>
					</header>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		}
		?>
	</main>
<?php
get_footer();
