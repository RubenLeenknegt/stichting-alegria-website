/**
 * Share block: the copy-link button puts the post's link on the clipboard and briefly swaps its icon
 * for a check mark. Screen readers hear the message through the block's status region.
 */

const FEEDBACK_MS = 2500;

/**
 * Copies text to the clipboard, with a fallback for pages not served over HTTPS.
 *
 * @param {string} text Text to copy.
 * @return {Promise<void>} Resolves when copied, rejects when copying failed.
 */
async function copyText( text ) {
	if ( navigator.clipboard && window.isSecureContext ) {
		await navigator.clipboard.writeText( text );
		return;
	}

	const field = document.createElement( 'textarea' );
	field.value = text;
	field.setAttribute( 'readonly', '' );
	field.style.position = 'fixed';
	field.style.opacity = '0';
	document.body.appendChild( field );
	field.select();
	const copied = document.execCommand( 'copy' );
	field.remove();
	if ( ! copied ) {
		throw new Error( 'Copy failed' );
	}
}

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
				await copyText( url );
				showCopied( block, button );
			} catch ( error ) {
				window.prompt( button.dataset.message || '', url ); // eslint-disable-line no-alert
			}
		} );
	} );
} );
