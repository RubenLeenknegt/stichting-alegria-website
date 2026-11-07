<?php
/**
 * Text-and-text block (wp-rig/txt-txt): a title above two text columns.
 *
 * @package alegria
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = is_array( $attributes ?? null ) ? $attributes : array();

$title      = $attributes['title'] ?? '';
$left_text  = $attributes['leftText'] ?? '';
$right_text = $attributes['rightText'] ?? '';

if ( ! $title && ! $left_text && ! $right_text ) {
	return;
}

$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'txt-txt-block' ) );

?>
<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
