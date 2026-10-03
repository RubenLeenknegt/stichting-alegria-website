<?php
/**
 * Markup tweaks for the primary menu, used by assets/js/src/navigation.ts.
 *
 * @package alegria
 */

/**
 * Whether a wp_nav_menu() call renders the primary menu.
 *
 * @param stdClass $args wp_nav_menu() arguments.
 * @return bool
 */
function alegria_is_primary_menu( $args ): bool {
	return ! empty( $args->theme_location ) && 'primary' === $args->theme_location;
}

add_filter(
	'walker_nav_menu_start_el',
	function ( string $item_output, WP_Post $item, int $depth, $args ): string {
		if ( ! alegria_is_primary_menu( $args ) ) {
			return $item_output;
		}

		$classes      = is_array( $item->classes ) ? $item->classes : array();
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$dropdown     = '<span class="dropdown">' . alegria_svg( 'dropdown-symbol' ) . '</span>';

		// Items without a real URL become a button that only opens their submenu.
		if ( empty( $item->url ) || '#' === $item->url ) {
			return sprintf(
				'<button class="%s" type="button" aria-expanded="false" aria-controls="submenu-%s">%s %s</button>',
				$has_children ? 'submenu-toggle' : '',
				esc_attr( $item->ID ),
				esc_html( $item->title ),
				$has_children ? $dropdown : ''
			);
		}

		// navigation.ts turns this symbol into a toggle button.
		if ( $has_children ) {
			$item_output .= $dropdown;
		}

		// The "Doneer nu" call to action gets an arrow.
		if ( in_array( 'donate-menu-item', $classes, true ) || 'Doneer nu' === $item->title ) {
			$item_output .= '<span class="cta-arrow">' . alegria_svg( 'arrow_right' ) . '</span>';
		}

		return $item_output;
	},
	10,
	4
);

/*
 * On mobile, tapping a parent item opens its submenu, so the parent's own page would be unreachable.
 * Repeat each parent that has a URL as the first item of its submenu.
 */
add_filter(
	'wp_nav_menu_objects',
	function ( array $items, $args ): array {
		if ( ! alegria_is_primary_menu( $args ) ) {
			return $items;
		}

		$min_child_order = array();
		foreach ( $items as $item ) {
			$parent_id = (int) $item->menu_item_parent;
			if ( $parent_id ) {
				$min_child_order[ $parent_id ] = min( $min_child_order[ $parent_id ] ?? PHP_INT_MAX, (int) $item->menu_order );
			}
		}

		$injected = array();
		foreach ( $items as $item ) {
			$has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );
			if ( ! $has_children || empty( $item->url ) || '#' === $item->url ) {
				continue;
			}

			$copy                   = clone $item;
			$copy->ID               = -1 * ( absint( $item->ID ) + 100000 );
			$copy->db_id            = 0;
			$copy->menu_item_parent = (int) $item->ID;
			$copy->type             = 'custom';
			$copy->object           = 'custom';
			$copy->object_id        = 0;
			$copy->xfn              = '';
			$copy->description      = '';
			$copy->classes          = array_merge( array_diff( (array) $item->classes, array( 'menu-item-has-children' ) ), array( 'menu-item--injected-parent-link' ) );
			$copy->menu_order       = ( $min_child_order[ (int) $item->ID ] ?? 0 ) - 1;

			$injected[] = $copy;
		}

		if ( ! $injected ) {
			return $items;
		}

		$items = array_merge( $items, $injected );
		usort(
			$items,
			function ( $a, $b ) {
				return (int) $a->menu_order <=> (int) $b->menu_order;
			}
		);

		return $items;
	},
	10,
	2
);
