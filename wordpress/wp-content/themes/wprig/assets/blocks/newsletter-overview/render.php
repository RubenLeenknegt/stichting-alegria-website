<?php
/**
 * Newsletter Overview Block Template
 *
 * @package wp_rig
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use function WP_Rig\WP_Rig\wp_rig;

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block      = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

$title     = $attributes['title'] ?? '';
$link_text = $attributes['linkText'] ?? '';

// Always point CTA to the newsletter archive.
$cta_url = get_post_type_archive_link( 'newsletter' );
if ( ! is_string( $cta_url ) || '' === $cta_url ) {
	$cta_url = home_url( '/newsletter/' );
}

$primary_button_icon = wp_rig()->get_cta_arrow_svg();

$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'newsletter-overview-block' ),
	$attributes
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

<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="newsletter-overview-container">
		<?php if ( $title || $link_text ) : ?>
			<div class="newsletter-overview-header">
				<?php if ( $title ) : ?>
					<h2 class="newsletter-overview-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $link_text ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="newsletter-overview-cta-link">
						<?php echo esc_html( $link_text ); ?>
						<?php if ( ! empty( $primary_button_icon ) ) : ?>
							<span class="btn-icon" aria-hidden="true"><?php echo $primary_button_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $newsletter_query->have_posts() ) : ?>
			<div class="newsletter-overview-grid">
				<?php
				while ( $newsletter_query->have_posts() ) :
					$newsletter_query->the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="newsletter-overview-card">
						<div class="newsletter-overview-card-image">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>

						<div class="newsletter-overview-card-content">
							<div class="newsletter-overview-card-date-row">
								<span class="newsletter-overview-card-date-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" focusable="false">
										<path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-8ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z" />
									</svg>
								</span>
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</div>

							<h3 class="newsletter-overview-card-title"><?php the_title(); ?></h3>

							<div class="newsletter-overview-card-excerpt">
								<?php echo esc_html( get_the_excerpt() ); ?>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
wp_reset_postdata();
