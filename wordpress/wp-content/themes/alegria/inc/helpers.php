<?php
/**
 * Returns the markup of an SVG from assets/svg, for inlining.
 *
 * @param string $name File name without the .svg extension.
 * @return string SVG markup.
 */
function alegria_svg( string $name ): string {
	return (string) file_get_contents( get_theme_file_path( "assets/svg/{$name}.svg" ) );
}

/**
 * Returns a link with the arrow icon, styled as a button by default.
 *
 * @param string $text  Link text.
 * @param string $url   Link URL.
 * @param string $class Class attribute.
 * @return string Link markup, or an empty string when the text or URL is missing.
 */
function alegria_button( string $text, string $url, string $class = 'btn-primary' ): string {
	if ( '' === $text || '' === $url ) {
		return '';
	}

	return sprintf(
		'<a href="%s" class="%s">%s <span class="btn-icon" aria-hidden="true">%s</span></a>',
		esc_url( $url ),
		esc_attr( $class ),
		esc_html( $text ),
		alegria_svg( 'arrow_right' )
	);
}

/**
 * Returns the date to show for a newsletter or event: the event date for events, the publish date otherwise.
 *
 * Event dates are stored as Y-m-d without a time, so they are read in the site's timezone.
 *
 * @param int $post_id Post ID.
 * @return array{iso: string, text: string} Value for <time datetime> and the date as shown. Empty when an event has no date.
 */
function alegria_display_date( int $post_id ): array {
	if ( 'event' !== get_post_type( $post_id ) ) {
		return array(
			'iso'  => (string) get_the_date( 'c', $post_id ),
			'text' => (string) get_the_date( 'j F Y', $post_id ),
		);
	}

	$event_date = (string) get_post_meta( $post_id, 'event_date', true );
	$event_day  = $event_date ? DateTimeImmutable::createFromFormat( '!Y-m-d', $event_date, wp_timezone() ) : false;

	return $event_day
		? array(
			'iso'  => $event_day->format( 'Y-m-d' ),
			'text' => wp_date( 'j F Y', $event_day->getTimestamp() ),
		)
		: array(
			'iso'  => '',
			'text' => '',
		);
}

/**
 * Returns Rank Math's breadcrumbs as a <nav>, or an empty string when Rank Math is not active.
 *
 * @return string Breadcrumb markup.
 */
function alegria_breadcrumbs(): string {
	if ( ! function_exists( 'rank_math_get_breadcrumbs' ) ) {
		return '';
	}

	return (string) rank_math_get_breadcrumbs(
		array(
			'separator'   => '/',
			'wrap_before' => '<nav aria-label="' . esc_attr__( 'Kruimelpad', 'alegria' ) . '" class="rank-math-breadcrumb breadcrumbs"><p>',
		)
	);
}
