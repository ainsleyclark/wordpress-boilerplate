/**
 * app.js
 * All custom JS for theme stored here.
 * This file is only for global functions, please see js/scripts or js/pages for page & function specific.
 * @author Ainsley Clark
 * @author URL:   https://www.ainsleyclark.com
 * @author email: info@ainsleyclark.com
 */

/**
 * Require * Import
 * 
 */

//Local 
import scroll from './scripts/scroll';

//Vendor
import LazyLoad from 'vanilla-lazyload';

/*
* lazyLoad
*
*/
const lazyLoadInstance = new LazyLoad({ 
	elements_selector: '.lazy',
	load_delay: 0 //adjust according to use case
});


/*
* Parallax
*
*/
window.addEventListener('scroll', e => {
	const target = document.querySelectorAll('.para');

	let scrolled = window.pageYOffset;
	
	target.forEach( el => {
		let pos = scrolled * el.dataset.pararate;
		el.style.transform = 'translate3d(0px, ' + pos + 'px, 0px)';
	});
});

/*
 * scrollToAnchor - Targets all links with # anchor & adds smooth scrolling
 * 
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
	anchor.addEventListener('click', function (e) {
		e.preventDefault();

		let headerOffset = document.querySelector('header').clientHeight;
		let link = document.querySelector(this.getAttribute('href'));

		scroll({
			target: link,
			offset: -headerOffset,
			duration: 1000,
			easing: 'easeInOutQuad'
		});
		
	});
});