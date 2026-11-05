<?php
/**
 * Upcoming events: a Swiper slider of the next events, from yesterday on. Renders nothing without any.
 *
 * Swiper is the shared `alegria-swiper` script and style (inc/assets.php); view.js starts it.
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = is_array( $attributes ?? null ) ? $attributes : array();

$title      = $attributes['title'] ?? '';
$max_events = max( 1, (int) ( $attributes['maxEvents'] ?? 6 ) );

$events_query = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => $max_events,
		'no_found_rows'  => true,
		'meta_key'       => 'event_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'event_date',
				'value'   => gmdate( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

if ( ! $events_query->have_posts() ) {
	return;
}

$has_controls = $events_query->post_count > 1;
$arrow_icon   = alegria_svg( 'arrow_right' );
$chevron      = '<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M9 4l8 8-8 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'upcoming-events-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( $title ) : ?>
		<h2 class="upcoming-events-heading"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>

	<div
		class="upcoming-events-slider swiper"
		data-label-prev="<?php esc_attr_e( 'Vorig evenement', 'alegria' ); ?>"
		data-label-next="<?php esc_attr_e( 'Volgend evenement', 'alegria' ); ?>"
		data-label-first="<?php esc_attr_e( 'Dit is het eerste evenement', 'alegria' ); ?>"
		data-label-last="<?php esc_attr_e( 'Dit is het laatste evenement', 'alegria' ); ?>"
		data-label-bullet="<?php /* translators: {{index}} is replaced by Swiper with the slide number. */ esc_attr_e( 'Ga naar evenement {{index}}', 'alegria' ); ?>"
	>
		<div class="swiper-wrapper">
			<?php
			while ( $events_query->have_posts() ) :
				$events_query->the_post();

				$event_date = get_post_meta( get_the_ID(), 'event_date', true );
				$timestamp  = $event_date ? strtotime( $event_date ) : false;
				?>
				<div class="swiper-slide upcoming-events-slide">
					<div class="upcoming-events-content">
						<h3 class="upcoming-events-title"><?php echo esc_html( get_the_title() ); ?></h3>

						<?php if ( $timestamp ) : ?>
							<time class="upcoming-events-date" datetime="<?php echo esc_attr( gmdate( 'Y-m-d', $timestamp ) ); ?>"><?php echo esc_html( date_i18n( 'j F Y', $timestamp ) ); ?></time>
						<?php endif; ?>

						<div class="upcoming-events-excerpt">
							<?php echo wp_kses_post( get_the_excerpt() ); ?>
						</div>

						<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-primary upcoming-events-cta">
							<?php esc_html_e( 'Bekijk evenement', 'alegria' ); ?>
							<span class="btn-icon" aria-hidden="true"><?php echo $arrow_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</div>

					<div class="upcoming-events-media">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'alegria-card', array( 'class' => 'upcoming-events-image' ) ); ?>
						<?php else : ?>
							<div class="upcoming-events-image upcoming-events-image-placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>

	<?php if ( $has_controls ) : ?>
		<div class="upcoming-events-controls">
			<button type="button" class="swiper-button-prev upcoming-events-arrow"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			<div class="swiper-pagination upcoming-events-pagination"></div>
			<button type="button" class="swiper-button-next upcoming-events-arrow"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
	<?php endif; ?>
</div>
<?php
wp_reset_postdata();
