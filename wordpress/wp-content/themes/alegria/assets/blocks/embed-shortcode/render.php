<?php
$raw_content = isset( $attributes['content'] ) ? trim( $attributes['content'] ) : '';
$is_iframe   = ! empty( $attributes['isIframe'] );

if ( empty( $raw_content ) ) {
	return;
}

/*
 * Raw iframe/HTML (including <script>) is only output when the post's author is allowed to publish
 * unfiltered HTML (administrators and editors). For anyone else the block outputs nothing in that mode.
 */
$post_id = get_the_ID();
if ( $is_iframe && ( ! $post_id || ! user_can( (int) get_post_field( 'post_author', $post_id ), 'unfiltered_html' ) ) ) {
	return;
}

// wp_kses_post() strips <iframe>, so embeds use this list of embed-style markup instead.
$allowed_embed_html = array(
	'iframe' => array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'style'           => true,
		'scrolling'       => true,
		'allow'           => true,
		'allowfullscreen' => true,
		'loading'         => true,
		'title'           => true,
		'referrerpolicy'  => true,
		'name'            => true,
		'id'              => true,
		'class'           => true,
	),
	'div'    => array( 'style' => true, 'class' => true, 'id' => true ),
	'span'   => array( 'style' => true, 'class' => true, 'id' => true ),
	'script' => array( 'src' => true, 'async' => true, 'defer' => true ),
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'embed-shortcode-block' ) ); ?>>
	<div class="embed-shortcode-block__inner">
		<?php if ( $is_iframe ) : ?>
			<?php echo wp_kses( $raw_content, $allowed_embed_html ); ?>
		<?php else : ?>
			<?php
			// Sanitize what the author typed first, then run the shortcodes, so plugin output (forms etc.) stays intact.
			echo do_shortcode( wp_kses_post( $raw_content ) );
			?>
		<?php endif; ?>
	</div>
</div>
