const FEEDBACK_MS = 2500;

/**
 * Shows the copied state on a button and announces it to screen readers.
 *
 * @param {HTMLElement} block  The share block.
 * @param {HTMLElement} button The button that was used.
 */
function showCopied( block, button ) {
	const message = button.dataset.message || '';
	const status = block.querySelector( '.share-status' );

	// Restart the animation when the button is used again while it is still showing.
	button.classList.remove( 'is-copied' );
	void button.offsetWidth; // eslint-disable-line no-void
	button.classList.add( 'is-copied' );

	if ( status ) {
		status.textContent = '';
		window.setTimeout( () => ( status.textContent = message ), 50 );
	}

	window.clearTimeout( button.copiedTimer );
	button.copiedTimer = window.setTimeout( () => button.classList.remove( 'is-copied' ), FEEDBACK_MS );
}

document.querySelectorAll( '.share-block' ).forEach( ( block ) => {
	const { url } = block.dataset;

	block.querySelectorAll( '.share-copy' ).forEach( ( button ) => {
		button.addEventListener( 'click', async () => {
			try {
				await navigator.clipboard.writeText( url );
				showCopied( block, button );
			} catch ( error ) {
				window.prompt( button.dataset.message || '', url ); // eslint-disable-line no-alert
			}
		} );
	} );
} );
