document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.upcoming-events-block').forEach(initSlider);
});

function initSlider(root) {
	const track = root.querySelector('.upcoming-events-track');
	const slides = root.querySelectorAll('.upcoming-events-slide');
	const dots = root.querySelectorAll('.upcoming-events-dot');
	const prevBtn = root.querySelector('.upcoming-events-arrow-prev');
	const nextBtn = root.querySelector('.upcoming-events-arrow-next');

	if (!track || slides.length <= 1) {
		return;
	}

	let index = 0;
	let startX = 0;
	let currentX = 0;
	let isDragging = false;

	function goTo(newIndex) {
		index = (newIndex + slides.length) % slides.length;
		track.style.transform = `translateX(-${index * 100}%)`;

		dots.forEach((dot, i) => {
			const isActive = i === index;
			dot.classList.toggle('is-active', isActive);
			dot.setAttribute('aria-current', isActive ? 'true' : 'false');
		});
	}

	if (prevBtn) {
		prevBtn.addEventListener('click', () => goTo(index - 1));
	}

	if (nextBtn) {
		nextBtn.addEventListener('click', () => goTo(index + 1));
	}

	dots.forEach((dot) => {
		dot.addEventListener('click', () => {
			goTo(parseInt(dot.dataset.index, 10));
		});
	});

	const SWIPE_THRESHOLD = 40;

	track.addEventListener('touchstart', (e) => {
		startX = e.touches[0].clientX;
		currentX = startX;
		isDragging = true;
		track.style.transition = 'none';
	}, { passive: true });

	track.addEventListener('touchmove', (e) => {
		if (!isDragging) {
			return;
		}
		currentX = e.touches[0].clientX;
		const delta = currentX - startX;
		const percentDelta = (delta / track.clientWidth) * 100;
		track.style.transform = `translateX(calc(-${index * 100}% + ${percentDelta}%))`;
	}, { passive: true });

	track.addEventListener('touchend', () => {
		if (!isDragging) {
			return;
		}
		isDragging = false;
		track.style.transition = '';

		const delta = currentX - startX;

		if (delta > SWIPE_THRESHOLD) {
			goTo(index - 1);
		} else if (delta < -SWIPE_THRESHOLD) {
			goTo(index + 1);
		} else {
			goTo(index);
		}

		startX = 0;
		currentX = 0;
	});
}
