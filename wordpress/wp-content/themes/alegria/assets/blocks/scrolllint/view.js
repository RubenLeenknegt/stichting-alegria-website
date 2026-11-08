import Swiper from 'swiper';

const SPEED = 5000; // Milliseconds for one slide to pass while the strip runs by itself.
const SNAP_SPEED = 400; // Milliseconds to settle on a slide after a drag.
const GAP = 48;

const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
const canHover = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

document.querySelectorAll( '.scrolllint__slider' ).forEach( ( slider ) => {
	const swiper = new Swiper( slider, {
		loop: true,
		slidesPerView: 'auto',
		spaceBetween: GAP,
		speed: reduceMotion ? SNAP_SPEED : SPEED,
		grabCursor: true,
		a11y: { enabled: false },
		autoplay: reduceMotion
			? false
			: {
				delay: 0,
				disableOnInteraction: false,
				reverseDirection: true,
			},
	} );

	if ( reduceMotion ) {
		return;
	}

	let paused = false;
	let hovered = false;

	/*
	 * Stop autoplay and freeze the running CSS transition where it is. (Swiper's own pauseOnMouseEnter
	 * only stops after the current slide, which with a 5 s linear slide feels unresponsive.)
	 */
	const pause = () => {
		if ( paused ) {
			return;
		}
		paused = true;
		swiper.autoplay.stop();
		if ( swiper.animating ) {
			swiper.setTransition( 0 );
			swiper.setTranslate( swiper.getTranslate() );
		}
	};

	// Finish the slide that was interrupted at the marquee's own pace, then restart autoplay.
	const resume = () => {
		if ( ! paused ) {
			return;
		}
		paused = false;
		swiper.params.speed = SPEED;

		const target = -swiper.snapGrid[ swiper.snapIndex ];
		const slideWidth = swiper.slidesSizesGrid[ swiper.activeIndex ] + GAP;
		const remaining = Math.abs( target - swiper.getTranslate() ) / slideWidth;

		if ( remaining < 0.001 ) {
			swiper.autoplay.start();
			return;
		}

		const onEnd = ( event ) => {
			if ( event.target !== swiper.wrapperEl ) {
				return;
			}
			swiper.wrapperEl.removeEventListener( 'transitionend', onEnd );
			if ( ! paused ) {
				swiper.autoplay.start();
			}
		};
		swiper.wrapperEl.addEventListener( 'transitionend', onEnd );
		swiper.setTransition( Math.round( remaining * SPEED ) );
		swiper.setTranslate( target );
	};

	if ( canHover ) {
		slider.addEventListener( 'mouseenter', () => {
			hovered = true;
			pause();
		} );
		slider.addEventListener( 'mouseleave', () => {
			hovered = false;
			resume();
		} );
	}

	// Dragging: stop the marquee first, and settle quickly on a slide after letting go.
	swiper.on( 'touchStart', () => {
		pause();
		swiper.params.speed = SNAP_SPEED;
	} );

	// Touch screens have no mouseleave, so carry on once the strip has settled.
	swiper.on( 'touchEnd', () => {
		if ( ! hovered ) {
			setTimeout( () => {
				if ( ! hovered ) {
					resume();
				}
			}, SNAP_SPEED + 50 );
		}
	} );
} );
