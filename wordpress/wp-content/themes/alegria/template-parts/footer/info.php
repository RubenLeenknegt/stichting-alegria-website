<?php
/**
 * Footer content: logo, intro text, social links and call to action on the left,
 * three footer menus on the right, and a bottom bar with copyright and legal links.
 *
 * The text, links and button come from the Customizer (inc/customizer.php); empty ones are left out.
 *
 * @package alegria
 */

$footer_text = alegria_footer_setting( 'alegria_footer_text' );
$cta_text    = alegria_footer_setting( 'alegria_footer_cta_text' );
$cta_url     = alegria_footer_setting( 'alegria_footer_cta_url' );
$link_text   = alegria_footer_setting( 'alegria_footer_link_text' );
$link_url    = alegria_footer_setting( 'alegria_footer_link_url' );
$arrow_icon  = alegria_svg( 'arrow_right' );

$socials = array_filter(
	array(
		'instagram'   => array( 'Instagram', alegria_footer_setting( 'alegria_social_instagram' ) ),
		'facebook-f'  => array( 'Facebook', alegria_footer_setting( 'alegria_social_facebook' ) ),
		'linkedin-in' => array( 'LinkedIn', alegria_footer_setting( 'alegria_social_linkedin' ) ),
	),
	static fn( array $social ): bool => '' !== $social[1]
);

// Icons: Font Awesome Free brand icons (CC BY 4.0), assets/svg/{instagram,facebook-f,linkedin-in}.svg.
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
			<p class="footer-text"><?php echo nl2br( esc_html( $footer_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>

		<?php if ( $socials ) : ?>
			<ul class="footer-social">
				<?php foreach ( $socials as $icon => list( $name, $url ) ) : ?>
					<li>
						<a class="footer-social-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
							<?php echo alegria_svg( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="screen-reader-text">
								<?php
								/* translators: %s: social network name. */
								echo esc_html( sprintf( __( '%s (opent in nieuw venster)', 'alegria' ), $name ) );
								?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ( $cta_text && $cta_url ) || ( $link_text && $link_url ) ) : ?>
			<div class="footer-actions">
				<?php if ( $cta_text && $cta_url ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn-primary">
						<?php echo esc_html( $cta_text ); ?>
						<span class="btn-icon" aria-hidden="true"><?php echo $arrow_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $link_text && $link_url ) : ?>
					<a href="<?php echo esc_url( $link_url ); ?>" class="footer-text-link">
						<?php echo esc_html( $link_text ); ?>
						<span class="btn-icon" aria-hidden="true"><?php echo $arrow_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>
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
