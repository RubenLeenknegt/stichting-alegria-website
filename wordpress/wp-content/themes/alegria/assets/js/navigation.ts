/**
 * Primary navigation: the mobile menu toggle and the submenu dropdowns.
 *
 * Markup comes from template-parts/header/ and inc/navigation.php, which adds a
 * `.dropdown` icon after every parent item. Each icon becomes a toggle button here.
 *
 * State classes used by _navigation.css and _header.css:
 * - `.nav--toggled-on` on the nav while the mobile menu is open
 * - `.menu-item--toggled-on` on an open parent item, `.toggle-show` on its submenu
 */

declare const alegriaNav: { expand: string; collapse: string };

// Matches --below-desktop in _custom-media.css, where the menu switches to its mobile layout.
const mobileLayout = window.matchMedia( '(max-width: 87em)' );

function setSubmenuOpen( item: HTMLElement, open: boolean ): void {
	item.classList.toggle( 'menu-item--toggled-on', open );
	item.querySelector( ':scope > ul' )?.classList.toggle( 'toggle-show', open );

	const toggle = item.querySelector( ':scope > .dropdown-toggle, :scope > .submenu-toggle' );
	toggle?.setAttribute( 'aria-expanded', String( open ) );
	toggle?.setAttribute( 'aria-label', open ? alegriaNav.collapse : alegriaNav.expand );
}

function toggleSubmenu( item: HTMLElement ): void {
	setSubmenuOpen( item, ! item.classList.contains( 'menu-item--toggled-on' ) );
}

function closeAllSubmenus(): void {
	document
		.querySelectorAll< HTMLElement >( '.menu-item--toggled-on' )
		.forEach( ( item ) => setSubmenuOpen( item, false ) );
}

function initSubmenu( item: HTMLElement ): void {
	// Items without a URL are rendered as a <button class="submenu-toggle"> that opens the submenu itself.
	let toggle = item.querySelector< HTMLElement >( ':scope > .submenu-toggle' );

	if ( ! toggle ) {
		toggle = document.createElement( 'button' );
		toggle.className = 'dropdown-toggle';
		toggle.type = 'button';

		const icon = item.querySelector( ':scope > .dropdown' );
		if ( icon ) {
			toggle.innerHTML = icon.innerHTML;
			icon.replaceWith( toggle );
		} else {
			item.insertBefore( toggle, item.querySelector( ':scope > ul' ) );
		}
	}

	toggle.setAttribute( 'aria-expanded', 'false' );
	toggle.setAttribute( 'aria-label', alegriaNav.expand );
	toggle.addEventListener( 'click', () => toggleSubmenu( item ) );

	// In the mobile layout a parent link opens its submenu; the submenu repeats the parent link as its first item.
	item.querySelector( ':scope > a' )?.addEventListener( 'click', ( event ) => {
		if ( mobileLayout.matches ) {
			event.preventDefault();
			toggleSubmenu( item );
		}
	} );

	// Close the submenu when focus moves to an element outside it, e.g. by tabbing past its last link.
	item.addEventListener( 'focusout', ( event ) => {
		const next = event.relatedTarget as Node | null;
		if ( next && ! item.contains( next ) ) {
			setSubmenuOpen( item, false );
		}
	} );

	item.classList.add( 'menu-item--has-toggle' );
}

function initMenuToggle(): void {
	const toggles = document.querySelectorAll< HTMLElement >( '.menu-toggle' );
	const navs = document.querySelectorAll< HTMLElement >( '.nav--toggle-small' );

	toggles.forEach( ( toggle ) => {
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.addEventListener( 'click', () => {
			const open = toggle.getAttribute( 'aria-expanded' ) !== 'true';
			toggles.forEach( ( t ) => t.setAttribute( 'aria-expanded', String( open ) ) );
			navs.forEach( ( nav ) => nav.classList.toggle( 'nav--toggled-on', open ) );
		} );
	} );
}

function init(): void {
	initMenuToggle();
	document
		.querySelectorAll< HTMLElement >( '.nav--toggle-sub ul.sub-menu' )
		.forEach( ( submenu ) => initSubmenu( submenu.parentElement as HTMLElement ) );

	// Submenus opened in the mobile layout should not stay open on desktop.
	mobileLayout.addEventListener( 'change', ( event ) => {
		if ( ! event.matches ) {
			closeAllSubmenus();
		}
	} );
}

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', init );
} else {
	init();
}
