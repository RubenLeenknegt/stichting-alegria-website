<?php
// Parents in the primary menu get a dropdown icon, which navigation.js turns into a toggle button.
add_filter(
	'walker_nav_menu_start_el',
	function ( string $item_output, WP_Post $item, int $depth, $args ): string {
		if ( 'primary' !== ( $args->theme_location ?? '' ) || ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			return $item_output;
		}

		$dropdown = '<span class="dropdown">' . alegria_svg( 'dropdown-symbol' ) . '</span>';

		// Parents without a real URL become a button that only opens their submenu.
		if ( empty( $item->url ) || '#' === $item->url ) {
			return sprintf( '<button class="submenu-toggle" type="button" aria-expanded="false">%s %s</button>', esc_html( $item->title ), $dropdown );
		}

		return $item_output . $dropdown;
	},
	10,
	4
);
