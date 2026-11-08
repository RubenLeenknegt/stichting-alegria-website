/* global alegriaNav */

// Matches --below-desktop in _custom-media.css, where the menu switches to its mobile layout.
const mobileLayout = window.matchMedia( '(width < 87em)' );

function setSubmenuOpen( item, open ) {
	item.classList.toggle( 'menu-item--toggled-on', open );
	item.querySelector( ':scope > ul' )?.classList.toggle( 'toggle-show', open );

	const toggle = item.querySelector( ':scope > .dropdown-toggle, :scope > .submenu-toggle' );
	toggle?.setAttribute( 'aria-expanded', String( open ) );
	toggle?.setAttribute( 'aria-label', open ? alegriaNav.collapse : alegriaNav.expand );
}

function toggleSubmenu( item ) {
	setSubmenuOpen( item, ! item.classList.contains( 'menu-item--toggled-on' ) );
}

function initSubmenu( item ) {
	// Parents without a URL are rendered as a <button class="submenu-toggle"> that opens the submenu itself.
	let toggle = item.querySelector( ':scope > .submenu-toggle' );

	if ( ! toggle ) {
		toggle = document.createElement( 'button' );
		toggle.className = 'dropdown-toggle';
		toggle.type = 'button';

		const icon = item.querySelector( ':scope > .dropdown' );
		toggle.innerHTML = icon.innerHTML;
		icon.replaceWith( toggle );
	}

	toggle.setAttribute( 'aria-expanded', 'false' );
	toggle.setAttribute( 'aria-label', alegriaNav.expand );
	toggle.addEventListener( 'click', () => toggleSubmenu( item ) );

	const link = item.querySelector( ':scope > a' );
	if ( link ) {
		// On mobile, tapping a parent opens its submenu, so repeat the parent link as the first submenu item.
		const repeated = document.createElement( 'li' );
		repeated.className = 'menu-item menu-item--injected-parent-link';
		repeated.classList.toggle( 'current-menu-item', item.classList.contains( 'current-menu-item' ) );
		repeated.append( link.cloneNode( true ) );
		item.querySelector( ':scope > ul' ).prepend( repeated );

		link.addEventListener( 'click', ( event ) => {
			if ( mobileLayout.matches ) {
				event.preventDefault();
				toggleSubmenu( item );
			}
		} );
	}

	// Close the submenu when focus moves outside it, e.g. by tabbing past its last link.
	item.addEventListener( 'focusout', ( event ) => {
		if ( event.relatedTarget && ! item.contains( event.relatedTarget ) ) {
			setSubmenuOpen( item, false );
		}
	} );

	item.classList.add( 'menu-item--has-toggle' );
}

function initMenuToggle() {
	const toggle = document.querySelector( '.menu-toggle' );
	const nav = document.querySelector( '.nav--toggle-small' );

	if ( ! toggle || ! nav ) {
		return;
	}

	toggle.addEventListener( 'click', () => {
		const open = toggle.getAttribute( 'aria-expanded' ) !== 'true';
		toggle.setAttribute( 'aria-expanded', String( open ) );
		nav.classList.toggle( 'nav--toggled-on', open );
	} );
}

initMenuToggle();

document.querySelectorAll( '.nav--toggle-sub ul.sub-menu' ).forEach( ( submenu ) => initSubmenu( submenu.parentElement ) );

// Submenus opened in the mobile layout should not stay open on desktop.
mobileLayout.addEventListener( 'change', ( event ) => {
	if ( ! event.matches ) {
		document.querySelectorAll( '.menu-item--toggled-on' ).forEach( ( item ) => setSubmenuOpen( item, false ) );
	}
} );
