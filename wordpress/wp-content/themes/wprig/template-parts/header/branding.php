<?php
/**
 * Displays the site branding.
 *
 * @package WP_Rig
 */

namespace WP_Rig\WP_Rig;

$logo_svg = wp_rig()->get_logo_svg();

?>
<div class="site-branding">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo" aria-label="Ga naar home pagina">
		<?php echo $logo_svg; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
	</a>
</div>

