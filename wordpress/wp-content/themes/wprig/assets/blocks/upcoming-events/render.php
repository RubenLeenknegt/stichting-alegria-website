<?php
/**
 * Upcoming Events Block Template
 *
 * @package wp_rig
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use function WP_Rig\WP_Rig\wp_rig;

$attributes = is_array( $attributes ?? null ) ? $attributes : array();

$title      = $attributes['title'] ?? '';
$max_events = isset( $attributes['maxEvents'] ) ? (int) $attributes['maxEvents'] : 6;
$max_events = max( 1, $max_events );

$today = current_time( 'Y-m-d' );

$events_query = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => $max_events,
		'meta_key'       => 'event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'event_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

if ( ! $events_query->have_posts() ) {
	return;
}

$primary_button_icon = wp_rig()->get_cta_arrow_svg();
$total_events        = $events_query->post_count;

$wrapper_attrs = wp_rig()->block_wrapper_attributes(
	array( 'upcoming-events-block' ),
	$attributes
);

?>
	<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( $title ) : ?>
			<h2 class="upcoming-events-heading"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<div class="upcoming-events-viewport">
			<div class="upcoming-events-track">
				<?php
				while ( $events_query->have_posts() ) :
					$events_query->the_post();

					$event_date_raw = get_post_meta( get_the_ID(), 'event_date', true );
					$event_date     = '';

					if ( $event_date_raw ) {
						$timestamp   = strtotime( $event_date_raw );
						$event_date  = $timestamp ? date_i18n( 'j F Y', $timestamp ) : '';
					}
					?>
					<div class="upcoming-events-slide">
						<div class="upcoming-events-content">
							<h3 class="upcoming-events-title"><?php echo esc_html( get_the_title() ); ?></h3>

							<?php if ( $event_date ) : ?>
								<div class="upcoming-events-date"><?php echo esc_html( $event_date ); ?></div>
							<?php endif; ?>

							<div class="upcoming-events-excerpt">
								<?php echo wp_kses_post( get_the_excerpt() ); ?>
							</div>

							<div class="upcoming-events-cta">
								<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-primary">
									<?php esc_html_e( 'Bekijk evenement', 'wp-rig' ); ?>
									<?php if ( ! empty( $primary_button_icon ) ) : ?>
										<span class="btn-icon" aria-hidden="true"><?php echo $primary_button_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<?php endif; ?>
								</a>
							</div>
						</div>

						<div class="upcoming-events-media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'class' => 'upcoming-events-image' ) ); ?>
							<?php else : ?>
								<div class="upcoming-events-image upcoming-events-image-placeholder" aria-hidden="true"></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		<?php if ( $total_events > 1 ) : ?>
			<div class="upcoming-events-controls">
				<button type="button" class="upcoming-events-arrow upcoming-events-arrow-prev" aria-label="<?php esc_attr_e( 'Vorige evenement', 'wp-rig' ); ?>">
					<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M15 4l-8 8 8 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>

				<div class="upcoming-events-dots">
					<?php for ( $i = 0; $i < $total_events; $i++ ) : ?>
						<button
							type="button"
							class="upcoming-events-dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
							data-index="<?php echo esc_attr( (string) $i ); ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Ga naar evenement %d', 'wp-rig' ), $i + 1 ) ); ?>"
							aria-current="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						></button>
					<?php endfor; ?>
				</div>

				<button type="button" class="upcoming-events-arrow upcoming-events-arrow-next" aria-label="<?php esc_attr_e( 'Volgende evenement', 'wp-rig' ); ?>">
					<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M9 4l8 8-8 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</button>
			</div>
		<?php endif; ?>
	</div>
<?php
wp_reset_postdata();
