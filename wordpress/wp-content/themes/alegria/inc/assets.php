<?php
/**
 * Front-end and editor stylesheets and scripts.
 *
 * Block styles and scripts are not listed here; WordPress loads them from each block's block.json,
 * only on pages that contain the block. The Rubik @font-face is generated from theme.json.
 *
 * @package alegria
 */

// Load each block's stylesheet only on pages that use the block, instead of all of them everywhere.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/*
 * Swiper, shared by the upcoming-events and scrolllint blocks. Only registered here: the blocks list
 * `alegria-swiper` in their block.json style and (through build/view.asset.php) as a view script dependency,
 * so it loads once, and only on pages that contain one of them. Registered before the blocks (init, 10).
 */
add_action(
	'init',
	function () {
		wp_register_script(
			'alegria-swiper',
			get_theme_file_uri( 'assets/dist/swiper.js' ),
			array(),
			alegria_asset_version( 'assets/dist/swiper.js' ),
			array( 'strategy' => 'defer' )
		);
		wp_register_style( 'alegria-swiper', get_theme_file_uri( 'assets/dist/swiper.css' ), array(), alegria_asset_version( 'assets/dist/swiper.css' ) );
	},
	5
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'alegria-main', get_theme_file_uri( 'assets/dist/main.css' ), array(), alegria_asset_version( 'assets/dist/main.css' ) );

		wp_enqueue_script(
			'alegria-navigation',
			get_theme_file_uri( 'assets/dist/navigation.js' ),
			array(),
			alegria_asset_version( 'assets/dist/navigation.js' ),
			array( 'strategy' => 'defer' )
		);
		wp_localize_script(
			'alegria-navigation',
			'alegriaNav',
			array(
				'expand'   => __( 'Submenu openen', 'alegria' ),
				'collapse' => __( 'Submenu sluiten', 'alegria' ),
			)
		);

		// Opens the newsletter popup (template-parts/footer/newsletter-popup.php) from links to #nieuwsbrief-popup.
		wp_enqueue_script(
			'alegria-newsletter-popup',
			get_theme_file_uri( 'assets/dist/newsletter-popup.js' ),
			array(),
			alegria_asset_version( 'assets/dist/newsletter-popup.js' ),
			array( 'strategy' => 'defer' )
		);
	}
);

// Start downloading the font before the stylesheet that uses it has been parsed.
add_action(
	'wp_head',
	function () {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/rubik-latin-wght-normal.woff2' ) )
		);
	},
	1
);

add_action(
	'after_setup_theme',
	function () {
		add_editor_style( 'assets/dist/editor.css' );
	}
);
