<?php
// Load each block's stylesheet only on pages that use the block.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Swiper is only registered: blocks depend on it, so it loads once and only where needed. Runs before the blocks register (init, 10).
add_action(
	'init',
	function () {
		wp_register_script(
			'alegria-swiper',
			get_theme_file_uri( 'assets/dist/swiper.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/dist/swiper.js' ) ),
			array( 'strategy' => 'defer' )
		);
		wp_register_style( 'alegria-swiper', get_theme_file_uri( 'assets/dist/swiper.css' ), array(), filemtime( get_theme_file_path( 'assets/dist/swiper.css' ) ) );
	},
	5
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'alegria-main', get_theme_file_uri( 'assets/dist/main.css' ), array(), filemtime( get_theme_file_path( 'assets/dist/main.css' ) ) );

		wp_enqueue_script(
			'alegria-navigation',
			get_theme_file_uri( 'assets/dist/navigation.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/dist/navigation.js' ) ),
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

		wp_enqueue_script(
			'alegria-newsletter-popup',
			get_theme_file_uri( 'assets/dist/newsletter-popup.js' ),
			array(),
			filemtime( get_theme_file_path( 'assets/dist/newsletter-popup.js' ) ),
			array( 'strategy' => 'defer' )
		);
	}
);

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
