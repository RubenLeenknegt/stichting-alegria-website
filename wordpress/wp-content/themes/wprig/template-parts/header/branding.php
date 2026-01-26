<?php
/**
 * Displays the site branding.
 *
 * @package WP_Rig
 */

namespace WP_Rig\WP_Rig;

?>
<div class="site-branding flex-1">

	<div class="site-branding flex-1">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/src/logo.svg" alt="<?php bloginfo( 'name' ); ?>">
		</a>

		<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
	</div>

</div>
