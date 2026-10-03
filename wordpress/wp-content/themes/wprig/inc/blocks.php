<?php
/**
 * Registers the theme's blocks from assets/blocks and limits the inserter to them.
 *
 * Each block directory holds a block.json; its scripts, styles and render.php are loaded by WordPress from there.
 *
 * @package alegria
 */

/**
 * Registers every block in assets/blocks and returns their names.
 *
 * @return string[] Registered block names.
 */
function alegria_register_blocks(): array {
	static $names = null;

	if ( null === $names ) {
		$names = array();
		foreach ( glob( get_theme_file_path( 'assets/blocks/*/block.json' ) ) as $block_json ) {
			$block_type = register_block_type( dirname( $block_json ) );
			if ( $block_type instanceof WP_Block_Type ) {
				$names[] = $block_type->name;
			}
		}
	}

	return $names;
}
add_action( 'init', 'alegria_register_blocks' );

add_filter(
	'allowed_block_types_all',
	function () {
		return array_merge(
			alegria_register_blocks(),
			array(
				'core/paragraph',
				'core/heading',
				'core/group',
				'core/columns',
				'core/column',
			)
		);
	}
);
