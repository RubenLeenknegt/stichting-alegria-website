<?php
$title      = $attributes['title'] ?? '';
$left_text  = $attributes['leftText'] ?? '';
$right_text = $attributes['rightText'] ?? '';

if ( ! $title && ! $left_text && ! $right_text ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'txt-txt-block' ) ); ?>>
	<div class="txt-txt-container">
		<?php if ( $title ) : ?>
			<h2 class="txt-txt-title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $left_text || $right_text ) : ?>
			<div class="txt-txt-columns">
				<?php if ( $left_text ) : ?>
					<div class="txt-txt-text">
						<?php echo wp_kses_post( $left_text ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $right_text ) : ?>
					<div class="txt-txt-text">
						<?php echo wp_kses_post( $right_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
