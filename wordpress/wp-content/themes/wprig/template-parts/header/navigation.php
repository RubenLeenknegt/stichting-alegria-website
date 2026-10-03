<?php
/**
 * Primary navigation menu. The nav--toggle-* classes are used by navigation.ts.
 *
 * @package alegria
 */

if ( ! has_nav_menu( 'primary' ) ) {
	return;
}

?>
<div class="primary-menu-container">
	<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small icon-nav" aria-label="<?php esc_attr_e( 'Hoofdmenu', 'wp-rig' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'container'      => '',
			)
		);
		?>
	</nav>
</div>
