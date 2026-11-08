<?php
/*
 * Three other posts of the same type. Newsletters: the most recent.
 * Events: upcoming first (soonest first), then past (most recent first). Reuses the newsletter-overview markup and CSS.
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
	$event_args = $args + array(
		'fields'   => 'ids',
		'meta_key' => 'event_date',
		'orderby'  => 'meta_value',
	);
	$date_query = static fn( string $compare ): array => array(
		array(
			'key'     => 'event_date',
			'value'   => current_time( 'Y-m-d' ),
			'compare' => $compare,
			'type'    => 'DATE',
		),
	);

	$upcoming  = get_posts( $event_args + array( 'order' => 'ASC', 'meta_query' => $date_query( '>=' ) ) );
	$past      = get_posts( $event_args + array( 'order' => 'DESC', 'meta_query' => $date_query( '<' ) ) );
	$event_ids = array_slice( array_merge( $upcoming, $past ), 0, $count );

	if ( ! $event_ids ) {
		return;
	}

	$args['post__in'] = $event_ids;
	$args['orderby']  = 'post__in';
}

$cards = new WP_Query( $args );

if ( ! $cards->have_posts() ) {
	return;
}

$title = $attributes['title'] ?? '';
$link  = alegria_button(
	$attributes['linkText'] ?? '',
	( $attributes['linkUrl'] ?? '' ) ?: (string) get_post_type_archive_link( $post_type ),
	'newsletter-overview-cta-link'
);
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'read-more-block newsletter-overview-block' ) ); ?>>
	<div class="newsletter-overview-container">
		<?php if ( $title || $link ) : ?>
			<div class="newsletter-overview-header">
				<?php if ( $title ) : ?>
					<h2 class="newsletter-overview-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php echo $link; ?>
			</div>
		<?php endif; ?>

		<div class="newsletter-overview-grid">
			<?php
			while ( $cards->have_posts() ) :
				$cards->the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
