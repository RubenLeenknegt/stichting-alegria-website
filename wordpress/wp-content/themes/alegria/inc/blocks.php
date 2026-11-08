<?php
/*
 * The blocks keep the "wp-rig/" namespace from the starter theme on purpose: pages store block names
 * in their content, so renaming it would break every existing block on the site.
 */
add_action(
	'init',
	function () {
		foreach ( glob( get_theme_file_path( 'assets/blocks/*/block.json' ) ) as $block_json ) {
			register_block_type( dirname( $block_json ) );
		}
	}
);

// Only the theme's own blocks can be inserted. Share and read-more are only offered on newsletters and events.
add_filter(
	'allowed_block_types_all',
	function ( $allowed, WP_Block_Editor_Context $context ): array {
		$blocks = array_filter(
			array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() ),
			static fn( string $name ): bool => str_starts_with( $name, 'wp-rig/' )
		);

		if ( $context->post && in_array( $context->post->post_type, array( 'newsletter', 'event' ), true ) ) {
			return array_values( $blocks );
		}

		return array_values( array_diff( $blocks, array( 'wp-rig/share', 'wp-rig/read-more' ) ) );
	},
	10,
	2
);
