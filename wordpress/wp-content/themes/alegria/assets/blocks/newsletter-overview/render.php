<?php
/**
 * Newsletter Overview Block Template
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = is_array( $attributes ?? null ) ? $attributes : array();
$block      = ( isset( $block ) && $block instanceof WP_Block ) ? $block : null;

$title     = $attributes['title'] ?? '';
$link_text = $attributes['linkText'] ?? '';

// The link goes to the URL set in the block, or else the newsletter archive.
$cta_url = $attributes['linkUrl'] ?? '';
if ( '' === $cta_url ) {
	$cta_url = get_post_type_archive_link( 'newsletter' ) ?: home_url( '/nieuwsbrief/' );
}

$primary_button_icon = alegria_svg( 'arrow_right' );

$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'newsletter-overview-block' ) );

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
							<?php the_post_thumbnail( 'alegria-card' ); ?>
						</div>

						<div class="newsletter-overview-card-content">
							<time class="newsletter-overview-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>

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
