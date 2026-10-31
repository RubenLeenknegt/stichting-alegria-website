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
 * Returns an <img> for an image picked in a block.
 *
 * Blocks store the attachment ID, URL and alt text. When the ID still points to an image in the
 * media library, WordPress builds the tag, adding srcset/sizes, dimensions and lazy-loading.
 * Otherwise the stored URL is used as is.
 *
 * @param int    $id   Attachment ID.
 * @param string $url  Stored image URL, used when there is no attachment.
 * @param string $alt  Alt text set in the block. Empty uses the media library's alt text.
 * @param string $size Image size to request.
 * @param array  $attr Extra attributes, e.g. class.
 * @return string Image markup, or an empty string when there is no image.
 */
function alegria_block_image( int $id, string $url, string $alt = '', string $size = 'large', array $attr = array() ): string {
	if ( $id && wp_attachment_is_image( $id ) ) {
		if ( '' !== $alt ) {
			$attr['alt'] = $alt;
		}
		return wp_get_attachment_image( $id, $size, false, $attr );
	}

	if ( '' === $url ) {
		return '';
	}

	$attr = array_merge( array( 'loading' => 'lazy' ), $attr, array( 'alt' => $alt ) );
	$html = '<img src="' . esc_url( $url ) . '"';
	foreach ( $attr as $name => $value ) {
		$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
	}

	return $html . '>';
}

/**
 * Returns the URL of an image picked in a block, for use as a CSS background.
 *
 * @param int    $id   Attachment ID.
 * @param string $url  Stored image URL, used when there is no attachment.
 * @param string $size Image size to request.
 * @return string Image URL, or an empty string when there is no image.
 */
function alegria_block_image_url( int $id, string $url, string $size = 'full' ): string {
	$attachment_url = $id ? wp_get_attachment_image_url( $id, $size ) : false;

	return $attachment_url ? $attachment_url : $url;
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
