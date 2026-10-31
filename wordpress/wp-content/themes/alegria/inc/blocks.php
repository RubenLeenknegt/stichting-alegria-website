<?php
/**
 * Registers the theme's blocks from assets/blocks and makes them the only blocks available in the editor.
 *
 * Each block directory holds a block.json; its scripts, styles and render.php are loaded by WordPress from there.
 * A block's directory is named after its block name without the namespace (wp-rig/hero -> assets/blocks/hero).
 *
 * The blocks keep the "wp-rig/" namespace from the starter theme on purpose. Pages store block names in their
 * content (<!-- wp:wp-rig/hero -->), so renaming the namespace would break every existing block on the site.
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

/*
 * Only the theme's own blocks can be added in the editor; every core and plugin block is blocked.
 * The list is the set of blocks registered above, so a new block folder is allowed automatically and
 * a typo in a hand-written list can't hide a block.
 */
add_filter( 'allowed_block_types_all', 'alegria_register_blocks' );
