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
