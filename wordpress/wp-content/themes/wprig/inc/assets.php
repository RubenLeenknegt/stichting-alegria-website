<?php
/**
 * Front-end and editor stylesheets, scripts and fonts.
 *
 * Block styles and scripts are not listed here; WordPress loads them from each block's block.json.
 *
 * @package alegria
 */

/**
 * Google Fonts stylesheet URL for Rubik.
 */
const ALEGRIA_FONTS_URL = 'https://fonts.googleapis.com/css?family=Rubik:300,400,500,600,700,800,900&display=swap';

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'alegria-fonts', ALEGRIA_FONTS_URL, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'alegria-global', get_theme_file_uri( 'assets/dist/global.css' ), array(), alegria_asset_version( 'assets/dist/global.css' ) );
		wp_enqueue_style( 'alegria-content', get_theme_file_uri( 'assets/dist/content.css' ), array( 'alegria-global' ), alegria_asset_version( 'assets/dist/content.css' ) );

		wp_enqueue_script(
			'alegria-navigation',
			get_theme_file_uri( 'assets/dist/navigation.js' ),
			array(),
			alegria_asset_version( 'assets/dist/navigation.js' ),
			array( 'strategy' => 'defer' )
		);
		wp_localize_script(
			'alegria-navigation',
			'wpRigScreenReaderText',
			array(
				'expand'   => __( 'Submenu openen', 'wp-rig' ),
				'collapse' => __( 'Submenu sluiten', 'wp-rig' ),
			)
		);
	}
);

add_filter(
	'wp_resource_hints',
	function ( array $urls, string $relation_type ): array {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}
		return $urls;
	},
	10,
	2
);

add_action(
	'after_setup_theme',
	function () {
		add_editor_style( array( ALEGRIA_FONTS_URL, 'assets/dist/editor.css' ) );
	}
);
