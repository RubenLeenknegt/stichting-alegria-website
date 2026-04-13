<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

if ( ! wp_rig()->is_primary_nav_menu_active() ) {
	return;
}

?>
<div class="primary-menu-container">
	<div class="mobile-menu-header">
		<?php
		get_template_part( 'template-parts/header/branding' );

		get_template_part( 'template-parts/header/mobile-menu-toggle' );
		?>
	</div>
	<nav id="<?php echo esc_attr( apply_filters( 'wp_rig_site_navigation_id', 'site-navigation' ) ); ?>" class="<?php echo esc_attr( apply_filters( 'wp_rig_site_navigation_classes', 'main-navigation nav--toggle-sub nav--toggle-small' ) ); ?>" aria-label="<?php esc_attr_e( 'Main menu', 'wp-rig' ); ?>">
		<?php wp_rig()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</nav><!-- #site-navigation -->
</div>
