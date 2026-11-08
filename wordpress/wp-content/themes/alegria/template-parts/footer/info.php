<?php
$footer_text = alegria_setting( 'alegria_footer_text' );
$cta_text    = alegria_setting( 'alegria_footer_cta_text' );
$cta_url     = alegria_setting( 'alegria_footer_cta_url' );
$link_text   = alegria_setting( 'alegria_footer_link_text' );
$link_url    = alegria_setting( 'alegria_footer_link_url' );

// Each column's heading is the name of the menu assigned to its location.
$menu_locations = get_nav_menu_locations();
$footer_menus   = array();
foreach ( array( 'footer-info', 'footer-projects', 'footer-contact' ) as $location ) {
	$menu = ! empty( $menu_locations[ $location ] ) ? wp_get_nav_menu_object( $menu_locations[ $location ] ) : false;
	if ( $menu ) {
		$footer_menus[ $location ] = $menu->name;
	}
}
?>

<div class="footer-main">
	<div class="footer-about">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="footer-logo">
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/svg/logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Stichting Alegria, naar de homepagina', 'alegria' ); ?>" width="280" height="168" loading="lazy" decoding="async">
		</a>

		<?php if ( $footer_text ) : ?>
			<p class="footer-text"><?php echo nl2br( esc_html( $footer_text ) ); ?></p>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/social-links', null, array( 'prefix' => 'footer' ) ); ?>

		<?php if ( ( $cta_text && $cta_url ) || ( $link_text && $link_url ) ) : ?>
			<div class="footer-actions">
				<?php echo alegria_button( $cta_text, $cta_url ); ?>
				<?php echo alegria_button( $link_text, $link_url, 'footer-text-link' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $footer_menus ) : ?>
		<div class="footer-menus">
			<?php foreach ( $footer_menus as $location => $menu_name ) : ?>
				<nav class="footer-menu" aria-labelledby="<?php echo esc_attr( $location ); ?>-heading">
					<h2 class="footer-menu-heading" id="<?php echo esc_attr( $location ); ?>-heading"><?php echo esc_html( $menu_name ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $location,
							'container'      => false,
							'menu_class'     => 'footer-menu-list',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<div class="footer-bottom">
	<p class="copyright">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Stichting Alegria</p>

	<div class="footer-legal">
		<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'alegria' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>"><?php esc_html_e( 'Cookie Policy', 'alegria' ); ?></a>
		<a href="https://www.linkedin.com/in/ruben-leenknegt-94a67a199/">Door: Ruben Leenknegt</a>
	</div>
</div>
