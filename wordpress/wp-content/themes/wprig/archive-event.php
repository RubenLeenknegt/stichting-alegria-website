<?php
/**
 * Template: Event Archive
 *
 * Displays all published events at /event/
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();

wp_rig()->print_styles( 'wp-rig-content' );
?>

	<main id="primary" class="site-main archive-page archive-page--event">
		<div class="archive-block">
			<div class="archive-container">
				<header class="archive-header">
					<div>
						<h1 class="archive-title">Evenementen</h1>
						<p class="archive-description">
							<?php esc_html_e( 'Alle aankomende evenementen. Bekijk de details en schrijf je in.', 'wp-rig' ); ?>
						</p>
					</div>
				</header>

				<?php if ( have_posts() ) : ?>

					<div class="archive-grid">
						<?php while ( have_posts() ) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-entry' ); ?>>
								<a href="<?php the_permalink(); ?>" class="archive-card">
									<div class="archive-card-image">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'large' ); ?>
										<?php else : ?>
											<div class="archive-card-image-placeholder" aria-hidden="true"></div>
										<?php endif; ?>
									</div>

									<div class="archive-card-content">
										<div class="archive-card-date-row">
                                  <span class="archive-card-date-icon" aria-hidden="true">
                                     <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-8ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z" />
                                     </svg>
                                  </span>
											<?php
											$event_date = get_post_meta( get_the_ID(), 'event_date', true );
											if ( $event_date ) :
												$timestamp = strtotime( $event_date );
												?>
												<time datetime="<?php echo esc_attr( gmdate( 'c', $timestamp ) ); ?>">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ), $timestamp ) ); ?>
												</time>
											<?php endif; ?>
										</div>

										<h2 class="archive-card-title"><?php echo esc_html( get_the_title() ); ?></h2>

										<div class="archive-card-excerpt">
											<?php echo wp_kses_post( get_the_excerpt() ); ?>
										</div>
									</div>
								</a>
							</article>

						<?php endwhile; ?>
					</div>

					<div class="archive-pagination">
						<?php the_posts_pagination( [
							'mid_size'  => 2,
							'prev_text' => __( '&larr;', 'wp-rig' ),
							'next_text' => __( '&rarr;', 'wp-rig' ),
						] ); ?>
					</div>

				<?php else : ?>

					<div class="archive-empty">
						<p>
							<?php esc_html_e( 'Er zijn nog geen evenementen gepland.', 'wp-rig' ); ?>
						</p>
					</div>

				<?php endif; ?>

			</div>
		</div>
	</main>

	<?php get_footer(); ?>
