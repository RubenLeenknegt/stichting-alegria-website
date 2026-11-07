/**
 * Newsletter popup (template-parts/footer/newsletter-popup.php).
 *
 * Any link to #nieuwsbrief-popup opens it, on every page: buttons, menu items, links in text.
 * A page opened with #nieuwsbrief-popup in its URL (e.g. from an e-mail) opens it straight away.
 * The native <dialog> handles focus, the Escape key and the backdrop; this script opens it,
 * and closes it from the close button or a click on the backdrop.
 */

const popupHash = '#nieuwsbrief-popup';
const popup = document.querySelector( popupHash );

function openPopup() {
	if ( popup && ! popup.open ) {
		popup.showModal();
	}
}

if ( popup ) {
	document.addEventListener( 'click', ( event ) => {
		const link = event.target.closest( 'a[href*="#"]' );

		if ( link && link.hash === popupHash ) {
			event.preventDefault();
			openPopup();
		}
	} );

	popup.addEventListener( 'click', ( event ) => {
		// The content fills the dialog, so a click on the dialog itself is a click on the backdrop.
		if ( event.target === popup || event.target.closest( '[data-newsletter-popup-close]' ) ) {
			popup.close();
		}
	} );

	// Drop the hash when closing, so reloading the page doesn't open the popup again.
	popup.addEventListener( 'close', () => {
		if ( window.location.hash === popupHash ) {
			history.replaceState( null, '', window.location.pathname + window.location.search );
		}
	} );

	if ( window.location.hash === popupHash ) {
		openPopup();
	}
}
