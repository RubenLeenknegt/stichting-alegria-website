<?php
/**
 * Small helpers used by templates and block render files.
 *
 * @package alegria
 */

/**
 * Returns the markup of an SVG from assets/svg, for inlining.
 *
 * @param string $name File name without the .svg extension.
 * @return string SVG markup, or an empty string if the file does not exist.
 */
function alegria_svg( string $name ): string {
	static $cache = array();

	if ( ! isset( $cache[ $name ] ) ) {
		$path            = get_theme_file_path( "assets/svg/{$name}.svg" );
		$cache[ $name ] = is_readable( $path ) ? (string) file_get_contents( $path ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return $cache[ $name ];
}

/**
 * Returns a version string for a theme asset, based on its modification time.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string|null Version string, or null if the file does not exist.
 */
function alegria_asset_version( string $relative_path ): ?string {
	$path = get_theme_file_path( $relative_path );

	return file_exists( $path ) ? (string) filemtime( $path ) : null;
}
