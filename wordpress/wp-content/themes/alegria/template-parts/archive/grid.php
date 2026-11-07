<?php
/**
 * Card grid with pagination for the event and newsletter archives.
 *
 * @package alegria
 *
 * @var array $args {
 *     @type string $empty_text Message shown when there are no posts.
 * }
 */

if ( ! have_posts() ) {
	?>
	<div class="archive-empty">
		<p><?php echo esc_html( $args['empty_text'] ?? '' ); ?></p>
	</div>
	<?php
	return;
}
?>
<div class="archive-grid">
	<?php
	while ( have_posts() ) :
		the_post();

		// Events show their event date, newsletters their publish date.
		$event_date = 'event' === get_post_type() ? get_post_meta( get_the_ID(), 'event_date', true ) : '';
		$timestamp  = $event_date ? strtotime( $event_date ) : get_post_timestamp();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-entry' ); ?>>
			<a href="<?php the_permalink(); ?>" class="archive-card">
				<div class="archive-card-image">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'alegria-card' ); ?>
					<?php else : ?>
						<div class="archive-card-image-placeholder" aria-hidden="true"></div>
					<?php endif; ?>
				</div>

				<div class="archive-card-content">
					<?php if ( $timestamp ) : ?>
						<time class="post-date" datetime="<?php echo esc_attr( wp_date( 'c', $timestamp ) ); ?>"><?php echo esc_html( wp_date( 'j F Y', $timestamp ) ); ?></time>
					<?php endif; ?>

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
	<?php
	$arrow = '<span aria-hidden="true">' . alegria_svg( 'arrow_right' ) . '</span>';

	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => $arrow . '<span class="screen-reader-text">' . esc_html__( 'Vorige pagina', 'alegria' ) . '</span>',
			'next_text'          => $arrow . '<span class="screen-reader-text">' . esc_html__( 'Volgende pagina', 'alegria' ) . '</span>',
			'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Pagina', 'alegria' ) . ' </span>',
		)
	);
	?>
</div>
