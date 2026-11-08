<?php
$post_id    = (int) ( $block->context['postId'] ?? get_the_ID() );

$intro_text = $attributes['introText'] ?? '';
$image_id   = (int) ( $attributes['imageId'] ?? 0 ) ?: (int) get_post_thumbnail_id( $post_id );

// The image is at the top of the page, so load it straight away.
$image = wp_get_attachment_image(
	$image_id,
	'alegria-feature',
	false,
	array(
		'class'         => 'post-header-image',
		'loading'       => 'eager',
		'fetchpriority' => 'high',
	)
);

$date = in_array( get_post_type( $post_id ), array( 'newsletter', 'event' ), true )
	? alegria_display_date( $post_id )
	: array( 'text' => '' );

$breadcrumbs = alegria_breadcrumbs();
?>
<header <?php echo get_block_wrapper_attributes( array( 'class' => 'post-header' ) ); ?>>
	<div class="post-header-container">
		<?php echo $breadcrumbs; ?>

		<h1 class="post-header-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>

		<?php if ( $date['text'] ) : ?>
			<time class="post-date" datetime="<?php echo esc_attr( $date['iso'] ); ?>"><?php echo esc_html( $date['text'] ); ?></time>
		<?php endif; ?>

		<?php if ( $intro_text ) : ?>
			<div class="post-header-intro"><?php echo wp_kses_post( $intro_text ); ?></div>
		<?php endif; ?>

		<?php if ( $image ) : ?>
			<figure class="post-header-figure"><?php echo $image; ?></figure>
		<?php endif; ?>
	</div>
</header>
