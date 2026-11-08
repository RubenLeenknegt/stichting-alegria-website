<?php
$prefix      = 'alegria_archive_' . ( $args['type'] ?? '' );
$title       = alegria_setting( $prefix . '_title' );
$text        = alegria_setting( $prefix . '_text' );
$button_text = alegria_setting( $prefix . '_button_text' );
$button_url  = alegria_setting( $prefix . '_button_url' );

if ( ! $title ) {
	$title = post_type_archive_title( '', false );
}
?>
<header class="archive-header">
	<h1 class="archive-title"><?php echo esc_html( $title ); ?></h1>

	<?php if ( $text || ( $button_text && $button_url ) ) : ?>
		<div class="archive-intro">
			<?php if ( $text ) : ?>
				<div class="archive-description"><?php echo wpautop( esc_html( $text ) ); ?></div>
			<?php endif; ?>

			<?php echo alegria_button( $button_text, $button_url, 'btn-primary archive-button' ); ?>
		</div>
	<?php endif; ?>
</header>
