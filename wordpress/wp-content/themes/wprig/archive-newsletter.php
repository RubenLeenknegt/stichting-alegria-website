<?php
/**
 * Template: Newsletter Archive
 *
 * Displays all published newsletters at /newsletter/
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();

wp_rig()->print_styles( 'wp-rig-content' );
?>

	<main id="primary" class="site-main">
		<div class="archive-header">
			<h1 class="archive-title">Newsletter</h1>
			<p class="archive-description">
				<?php esc_html_e( 'All published newsletters. Subscribe to receive new issues directly in your inbox.', 'wp-rig' ); ?>
			</p>
		</div>

		<?php if ( have_posts() ) : ?>

			<div class="newsletter-archive">
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'newsletter-entry' ); ?>>

						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="newsletter-entry__thumbnail">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php endif; ?>

						<div class="newsletter-entry__content">
							<header>
								<h2 class="newsletter-entry__title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>
								<time class="newsletter-entry__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</header>

							<div class="newsletter-entry__excerpt">
								<?php the_excerpt(); ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="newsletter-entry__read-more">
								<?php esc_html_e( 'Read this issue', 'wp-rig' ); ?>
							</a>
						</div>

					</article>

				<?php endwhile; ?>
			</div>

			<?php the_posts_pagination( [
				'mid_size'  => 2,
				'prev_text' => __( '&larr; Older issues', 'wp-rig' ),
				'next_text' => __( 'Newer issues &rarr;', 'wp-rig' ),
			] ); ?>

		<?php else : ?>

			<p class="newsletter-archive__empty">
				<?php esc_html_e( 'No newsletters have been published yet.', 'wp-rig' ); ?>
			</p>

		<?php endif; ?>

	</main>

	<?php get_footer(); ?>

