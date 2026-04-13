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

	<main id="primary" class="site-main newsletter-archive-page">
		<div class="newsletter-archive-block">
			<div class="newsletter-archive-container">
				<header class="newsletter-archive-header">
					<div>
						<h1 class="newsletter-archive-title">Nieuwsbrief</h1>
						<p class="newsletter-archive-description">
							<?php esc_html_e( 'Alle gepubliceerde nieuwsbrieven. Schrijf je in om nieuwe uitgaven rechtstreeks in je inbox te ontvangen. ', 'wp-rig' ); ?>
						</p>
					</div>
				</header>

				<?php if ( have_posts() ) : ?>

					<div class="newsletter-archive-grid">
						<?php while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'newsletter-archive-entry' ); ?>>
								<a href="<?php the_permalink(); ?>" class="newsletter-archive-card">
									<div class="newsletter-archive-card-image">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'large' ); ?>
										<?php else : ?>
											<div class="newsletter-archive-card-image-placeholder" aria-hidden="true"></div>
										<?php endif; ?>
									</div>

									<div class="newsletter-archive-card-content">
										<div class="newsletter-archive-card-date-row">
											<span class="newsletter-archive-card-date-icon" aria-hidden="true">
												<svg viewBox="0 0 24 24" focusable="false">
													<path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-8ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z" />
												</svg>
											</span>
											<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
												<?php echo esc_html( get_the_date() ); ?>
											</time>
										</div>

										<h2 class="newsletter-archive-card-title"><?php echo esc_html( get_the_title() ); ?></h2>

										<div class="newsletter-archive-card-excerpt">
											<?php echo wp_kses_post( get_the_excerpt() ); ?>
										</div>
									</div>
								</a>
							</article>

						<?php endwhile; ?>
					</div>

					<div class="newsletter-archive-pagination">
						<?php the_posts_pagination( [
							'mid_size'  => 2,
							'prev_text' => __( '&larr;', 'wp-rig' ),
							'next_text' => __( '&rarr;', 'wp-rig' ),
						] ); ?>
					</div>

				<?php else : ?>

					<div class="newsletter-archive-empty">
						<p>
							<?php esc_html_e( 'Er zijn nog geen nieuwsbrieven gepubliceerd.', 'wp-rig' ); ?>
						</p>
					</div>

				<?php endif; ?>

			</div>
		</div>
	</main>

	<?php get_footer(); ?>

