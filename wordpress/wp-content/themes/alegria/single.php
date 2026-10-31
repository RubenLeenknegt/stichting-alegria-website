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
				<header class="single-header">
					<?php the_title( '<h1 class="single-title">', '</h1>' ); ?>
				</header>

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
