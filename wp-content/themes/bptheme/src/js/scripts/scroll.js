const scroll = ({
	target: destination,
	duration = 500,
	easing = 'linear',
	offset = 0,
	callback
}) => {

	//Check if destination is valid
	if(!destination) {
		console.log('Destination ID Selector not defined');
		return;
	}

	//Easings
	const easings = {
		linear(t) {
			return t;
		},
		easeInQuad(t) {
			return t * t;
		},
		easeOutQuad(t) {
			return t * (2 - t);
		},
		easeInOutQuad(t) {
			return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
		},
		easeInCubic(t) {
			return t * t * t;
		},
		easeOutCubic(t) {
			return (--t) * t * t + 1;
		},
		easeInOutCubic(t) {
			return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
		},
		easeInQuart(t) {
			return t * t * t * t;
		},
		easeOutQuart(t) {
			return 1 - (--t) * t * t * t;
		},
		easeInOutQuart(t) {
			return t < 0.5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t;
		},
		easeInQuint(t) {
			return t * t * t * t * t;
		},
		easeOutQuint(t) {
			return 1 + (--t) * t * t * t * t;
		},
		easeInOutQuint(t) {
			return t < 0.5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t;
		}
	};
	
	// Store initial position of a window and time
	const start = window.pageYOffset;
	const startTime = 'now' in window.performance ? performance.now() : new Date().getTime();

	// Take height of window and document to sesolve max scrollable value, prevent requestAnimationFrame() from scrolling below maximum scollable value
	const documentHeight = Math.max(document.body.scrollHeight, document.body.offsetHeight, document.documentElement.clientHeight, document.documentElement.scrollHeight, document.documentElement.offsetHeight);
	const windowHeight = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
	const destinationOffset = typeof destination === 'number' ? destination : destination.offsetTop;
	const destinationOffsetToScroll = Math.round(documentHeight - destinationOffset < windowHeight ? documentHeight - windowHeight : destinationOffset) + offset;

	// If requestAnimationFrame is not supported - move window to destination position and trigger callback function
	if ('requestAnimationFrame' in window === false) {
		window.scroll(0, destinationOffsetToScroll);
		if (callback) {
				callback();
		}
		return;
	}

	// function resolves position of a window and moves to exact amount of pixels, resolved by calculating delta and timing function choosen by user
	function scroll() {
		const now = 'now' in window.performance ? performance.now() : new Date().getTime();
		const time = Math.min(1, ((now - startTime) / duration));
		const timeFunction = easings[easing](time);
		window.scroll(0, Math.ceil((timeFunction * (destinationOffsetToScroll - start)) + start));

		// Stop requesting animation when window reached its destination and run a callback function
		if (window.pageYOffset === destinationOffsetToScroll) {
			if (callback) {
				callback();
			}
			return;
		}

	  	// If window still needs to scroll to reach destination, request another scroll invokation
		requestAnimationFrame(scroll);
	}

	// Invoke scroll and sequential requestAnimationFrame
	scroll();
}

export default scroll;
