<?php
/**
 * Fallback template. Renders pages (there is no page.php) and anything else without a more specific template.
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/entry' );
			}

			if ( ! is_singular() ) {
				the_posts_pagination( array( 'mid_size' => 2 ) );
			}
		} else {
			get_template_part( 'template-parts/content/none' );
		}
		?>
	</main>
<?php
get_footer();
