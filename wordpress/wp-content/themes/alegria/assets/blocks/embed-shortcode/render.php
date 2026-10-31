<?php
/**
 * Render callback for wp-rig/embed-shortcode.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 */

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

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'embed-shortcode-block',
	)
);

/**
 * Iframe embeds need to bypass wp_kses_post(), which strips <iframe> by
 * default. This whitelist is intentionally scoped to embed-style markup.
 */
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
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="embed-shortcode-block__inner">
		<?php if ( $is_iframe ) : ?>
			<?php echo wp_kses( $raw_content, $allowed_embed_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<?php
			// Sanitize what the author typed first, then run the shortcodes, so plugin output (forms etc.) stays intact.
			echo do_shortcode( wp_kses_post( $raw_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		<?php endif; ?>
	</div>
</div>
