<?php
/**
 * Render callback for wp-rig/txt.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content (unused, dynamic block).
 * @var WP_Block $block      Block instance.
 */

$title      = isset( $attributes['title'] ) ? $attributes['title'] : '';
$title_tag  = isset( $attributes['titleTag'] ) ? $attributes['titleTag'] : 'h2';
$body_text  = isset( $attributes['bodyText'] ) ? $attributes['bodyText'] : '';

if ( ! in_array( $title_tag, array( 'h2', 'h3' ), true ) ) {
	$title_tag = 'h2';
}

if ( empty( $title ) && empty( $body_text ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'txt-block',
	)
);
?>
<div <?php echo $wrapper_attributes; ?>>
	<?php if ( ! empty( $title ) ) : ?>
		<?php
		printf(
			'<%1$s class="txt-block-heading">%2$s</%1$s>',
			esc_html( $title_tag ),
			wp_kses_post( $title )
		);
		?>
	<?php endif; ?>

	<?php if ( ! empty( $body_text ) ) : ?>
		<div class="txt-block__body">
			<?php echo wp_kses_post( $body_text ); ?>
		</div>
	<?php endif; ?>
</div>
