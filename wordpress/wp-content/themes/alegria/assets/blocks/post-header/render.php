<?php
/**
 * Page header: Rank Math breadcrumbs, the title (h1), an intro text and a 16:9 image. Usable on any page
 * or post type; the newsletter and event template starts with it.
 *
 * Only newsletters and events show a date: events their event date, newsletters their publish date.
 * Without an image of its own, the block shows the post's featured image.
 *
 * @package alegria
 *
 * @var array    $attributes Block attributes.
 * @var WP_Block $block      Block instance.
 */

$post_id   = (int) ( $block->context['postId'] ?? get_the_ID() );

$intro_text = $attributes['introText'] ?? '';
$image_id   = (int) ( $attributes['imageId'] ?? 0 );
$image_url  = $attributes['imageUrl'] ?? '';
$image_alt  = $attributes['imageAlt'] ?? '';

if ( ! $image_id && '' === $image_url ) {
	$image_id = (int) get_post_thumbnail_id( $post_id );
}

// The image is at the top of the page, so load it straight away.
$image = alegria_block_image(
	$image_id,
	$image_url,
	$image_alt,
	'alegria-feature',
	array(
		'class'         => 'post-header-image',
		'loading'       => 'eager',
		'fetchpriority' => 'high',
	)
);

$date = in_array( get_post_type( $post_id ), array( 'newsletter', 'event' ), true )
	? alegria_display_date( $post_id )
	: array( 'text' => '' );

$breadcrumbs = alegria_breadcrumbs( 'post-header-breadcrumbs' );
?>
<header <?php echo get_block_wrapper_attributes( array( 'class' => 'post-header' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="post-header-container">
		<?php echo $breadcrumbs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Rank Math. ?>

		<h1 class="post-header-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>

		<?php if ( $date['text'] ) : ?>
			<time class="post-date" datetime="<?php echo esc_attr( $date['iso'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
		<?php endif; ?>

		<?php if ( $intro_text ) : ?>
			<div class="post-header-intro"><?php echo wp_kses_post( $intro_text ); ?></div>
		<?php endif; ?>

		<?php if ( $image ) : ?>
			<figure class="post-header-figure"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
		<?php endif; ?>
	</div>
</header>
