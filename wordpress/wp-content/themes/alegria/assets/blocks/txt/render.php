<?php
$title     = $attributes['title'] ?? '';
$title_tag = in_array( $attributes['titleTag'] ?? '', array( 'h2', 'h3' ), true ) ? $attributes['titleTag'] : 'h2';
$body_text = $attributes['bodyText'] ?? '';

if ( ! $title && ! $body_text ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'txt-block' ) ); ?>>
	<?php if ( $title ) : ?>
		<<?php echo $title_tag; ?> class="txt-block-heading"><?php echo wp_kses_post( $title ); ?></<?php echo $title_tag; ?>>
	<?php endif; ?>

	<?php if ( $body_text ) : ?>
		<div class="txt-block__body">
			<?php echo wp_kses_post( $body_text ); ?>
		</div>
	<?php endif; ?>
</div>
