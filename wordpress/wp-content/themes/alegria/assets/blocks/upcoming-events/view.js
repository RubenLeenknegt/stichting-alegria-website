import Swiper from 'swiper';

const AUTOPLAY_DELAY = 5000;
const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

document.querySelectorAll( '.upcoming-events-block' ).forEach( ( block ) => {
	const slider = block.querySelector( '.upcoming-events-slider' );
	if ( ! slider || slider.querySelectorAll( '.swiper-slide' ).length < 2 ) {
		return;
	}

	const labels = slider.dataset;

	const swiper = new Swiper( slider, {
		rewind: true,
		spaceBetween: 48,
		keyboard: { enabled: true, onlyInViewport: true },
		autoplay: reduceMotion
			? false
			: {
				delay: AUTOPLAY_DELAY,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			},
		navigation: {
			prevEl: block.querySelector( '.swiper-button-prev' ),
			nextEl: block.querySelector( '.swiper-button-next' ),
		},
		pagination: {
			el: block.querySelector( '.swiper-pagination' ),
			clickable: true,
		},
		a11y: {
			prevSlideMessage: labels.labelPrev,
			nextSlideMessage: labels.labelNext,
			firstSlideMessage: labels.labelFirst,
			lastSlideMessage: labels.labelLast,
			paginationBulletMessage: labels.labelBullet,
		},
	} );

	if ( ! reduceMotion ) {
		// Keyboard focus only: a mouse click on an arrow also focuses it, but shouldn't stop autoplay.
		block.addEventListener( 'focusin', ( event ) => {
			if ( event.target.matches( ':focus-visible' ) ) {
				swiper.autoplay.stop();
			}
		} );
		block.addEventListener( 'focusout', ( event ) => {
			if ( ! swiper.autoplay.running && ! block.contains( event.relatedTarget ) ) {
				swiper.autoplay.start();
			}
		} );
	}
} );
