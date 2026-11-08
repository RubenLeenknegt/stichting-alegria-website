<?php
$title      = $attributes['title'] ?? '';
$max_events = max( 1, (int) ( $attributes['maxEvents'] ?? 6 ) );

$events_query = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => $max_events,
		'no_found_rows'  => true,
		'meta_key'       => 'event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'event_date',
				'value'   => wp_date( 'Y-m-d', strtotime( '-1 day' ) ),
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
$chevron      = '<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M9 4l8 8-8 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'upcoming-events-block' ) ); ?>>
	<?php if ( $title ) : ?>
		<h2 class="upcoming-events-heading"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>

	<div
		class="upcoming-events-slider swiper"
		data-label-prev="<?php esc_attr_e( 'Vorig evenement', 'alegria' ); ?>"
		data-label-next="<?php esc_attr_e( 'Volgend evenement', 'alegria' ); ?>"
		data-label-first="<?php esc_attr_e( 'Dit is het eerste evenement', 'alegria' ); ?>"
		data-label-last="<?php esc_attr_e( 'Dit is het laatste evenement', 'alegria' ); ?>"
		data-label-bullet="<?php esc_attr_e( 'Ga naar evenement {{index}}', 'alegria' ); ?>"
	>
		<div class="swiper-wrapper">
			<?php
			while ( $events_query->have_posts() ) :
				$events_query->the_post();

				$date = alegria_display_date( get_the_ID() );
				?>
				<div class="swiper-slide upcoming-events-slide">
					<div class="upcoming-events-content">
						<h3 class="upcoming-events-title"><?php echo esc_html( get_the_title() ); ?></h3>

						<?php if ( $date['text'] ) : ?>
							<time class="post-date" datetime="<?php echo esc_attr( $date['iso'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
						<?php endif; ?>

						<div class="upcoming-events-excerpt">
							<?php echo wp_kses_post( get_the_excerpt() ); ?>
						</div>

						<?php echo alegria_button( __( 'Bekijk evenement', 'alegria' ), get_permalink(), 'btn-primary upcoming-events-cta' ); ?>
					</div>

					<div class="upcoming-events-media">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'alegria-feature', array( 'class' => 'upcoming-events-image' ) ); ?>
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
			<button type="button" class="swiper-button-prev upcoming-events-arrow"><?php echo $chevron; ?></button>
			<div class="swiper-pagination upcoming-events-pagination"></div>
			<button type="button" class="swiper-button-next upcoming-events-arrow"><?php echo $chevron; ?></button>
		</div>
	<?php endif; ?>
</div>
<?php
wp_reset_postdata();
