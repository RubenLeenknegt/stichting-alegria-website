<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'alegria' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo" aria-label="Ga naar home pagina">
				<?php echo alegria_svg( 'logo' ); ?>
			</a>
		</div>

		<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'alegria' ); ?>" aria-controls="primary-menu" aria-expanded="false">
			<span></span>
		</button>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<div class="primary-menu-container">
				<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Hoofdmenu', 'alegria' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => '',
							'depth'          => 2,
						)
					);
					?>
				</nav>
			</div>
		<?php endif; ?>
	</header>

	<?php
	// Pages with the post-header block print the breadcrumbs themselves.
	$breadcrumbs = is_front_page() || ( is_singular() && has_block( 'wp-rig/post-header', get_queried_object_id() ) ) ? '' : alegria_breadcrumbs();
	?>
	<?php if ( $breadcrumbs ) : ?>
		<div class="site-breadcrumbs"><?php echo $breadcrumbs; ?></div>
	<?php endif; ?>
