<?php
/**
 * Read-more cards under a newsletter or event: three others of the same type, never the current one.
 *
 * Newsletters: the three most recent. Events: upcoming events first (soonest first), then past events
 * (most recent first) to fill up to three. Renders nothing when there is nothing to show.
 *
 * The markup and stylesheet are the newsletter-overview block's, so both look the same.
 *
 * @package alegria
 *
 * @var array    $attributes Block attributes.
 * @var WP_Block $block      Block instance.
 */

$post_id   = (int) ( $block->context['postId'] ?? get_the_ID() );
$post_type = (string) ( $block->context['postType'] ?? get_post_type( $post_id ) );

if ( ! in_array( $post_type, array( 'newsletter', 'event' ), true ) ) {
	return;
}

$count = 3;
$args  = array(
	'post_type'      => $post_type,
	'post_status'    => 'publish',
	'post__not_in'   => array( $post_id ),
	'posts_per_page' => $count,
	'no_found_rows'  => true,
);

if ( 'event' === $post_type ) {
	$today      = current_time( 'Y-m-d' );
	$event_args = array_merge(
		$args,
		array(
			'fields'   => 'ids',
			'meta_key' => 'event_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'orderby'  => 'meta_value',
		)
	);

	$event_query = static function ( string $compare, string $order, int $limit ) use ( $event_args, $today ): array {
		return get_posts(
			array_merge(
				$event_args,
				array(
					'posts_per_page' => $limit,
					'order'          => $order,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'event_date',
							'value'   => $today,
							'compare' => $compare,
							'type'    => 'DATE',
						),
					),
				)
			)
		);
	};

	$event_ids = $event_query( '>=', 'ASC', $count );
	if ( count( $event_ids ) < $count ) {
		$event_ids = array_merge( $event_ids, $event_query( '<', 'DESC', $count - count( $event_ids ) ) );
	}

	if ( ! $event_ids ) {
		return;
	}

	$args = array_merge(
		$args,
		array(
			'post__in' => $event_ids,
			'orderby'  => 'post__in',
		)
	);
}

$cards = new WP_Query( $args );

if ( ! $cards->have_posts() ) {
	return;
}

$title     = $attributes['title'] ?? '';
$link_text = $attributes['linkText'] ?? '';
$link_url  = $attributes['linkUrl'] ?? '';
if ( '' === $link_url ) {
	$link_url = (string) get_post_type_archive_link( $post_type );
}

$arrow_icon = alegria_svg( 'arrow_right' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'read-more-block newsletter-overview-block' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="newsletter-overview-container">
		<?php if ( $title || $link_text ) : ?>
			<div class="newsletter-overview-header">
				<?php if ( $title ) : ?>
					<h2 class="newsletter-overview-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $link_text && $link_url ) : ?>
					<a href="<?php echo esc_url( $link_url ); ?>" class="newsletter-overview-cta-link">
						<?php echo esc_html( $link_text ); ?>
						<span class="btn-icon" aria-hidden="true"><?php echo $arrow_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="newsletter-overview-grid">
			<?php
			while ( $cards->have_posts() ) :
				$cards->the_post();
				$date = alegria_display_date( get_the_ID() );
				?>
				<a href="<?php the_permalink(); ?>" class="newsletter-overview-card">
					<div class="newsletter-overview-card-image">
						<?php the_post_thumbnail( 'alegria-card' ); ?>
					</div>

					<div class="newsletter-overview-card-content">
						<?php if ( $date['text'] ) : ?>
							<time class="post-date" datetime="<?php echo esc_attr( $date['iso'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
						<?php endif; ?>

						<h3 class="newsletter-overview-card-title"><?php echo esc_html( get_the_title() ); ?></h3>

						<div class="newsletter-overview-card-excerpt">
							<?php echo esc_html( get_the_excerpt() ); ?>
						</div>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
