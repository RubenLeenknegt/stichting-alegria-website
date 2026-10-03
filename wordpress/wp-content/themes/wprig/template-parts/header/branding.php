<?php
/**
 * Displays the site branding.
 *
 * @package WP_Rig
 */

$logo_svg = alegria_svg( 'logo' );

?>
<div class="site-branding">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo" aria-label="Ga naar home pagina">
		<?php echo $logo_svg; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
	</a>
</div>

