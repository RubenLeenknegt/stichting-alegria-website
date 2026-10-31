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
	}
);

// Start downloading the font before the stylesheet that uses it has been parsed.
add_action(
	'wp_head',
	function () {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/ttf" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/Rubik-VariableFont_wght.ttf' ) )
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
