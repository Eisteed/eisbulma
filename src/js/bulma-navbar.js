/**
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
/* eslint-disable */
(function () {
	let container, button, menu, links, i, len;

	container = document.getElementById('site-navigation');
	if (!container) {
		return;
	}

	button = container.getElementsByTagName('button')[0];
	if (typeof button === 'undefined') {
		return;
	}

	menu = container.getElementsByTagName('ul')[0];

	// Hide menu toggle button if menu is empty and return early.
	if (typeof menu === 'undefined') {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute('aria-expanded', 'false');
	if (menu.className.indexOf('nav-menu') === -1) {
		menu.className += ' nav-menu';
	}

	button.onclick = function () {
		if (container.className.indexOf('toggled') !== -1) {
			container.className = container.className.replace(' toggled', '');
			button.setAttribute('aria-expanded', 'false');
			menu.setAttribute('aria-expanded', 'false');
		} else {
			container.className += ' toggled';
			button.setAttribute('aria-expanded', 'true');
			menu.setAttribute('aria-expanded', 'true');
		}
	};

	// Get all the link elements within the menu.
	links = menu.getElementsByTagName('a');

	// Each time a menu link is focused or blurred, toggle focus.
	for (i = 0, len = links.length; i < len; i++) {
		links[i].addEventListener('focus', toggleFocus, true);
		links[i].addEventListener('blur', toggleFocus, true);
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		let self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while (self && self.className.indexOf('nav-menu') === -1) {
			// On li elements toggle the class .focus.
			if (self.tagName && self.tagName.toLowerCase() === 'li') {
				if (self.className.indexOf('focus') !== -1) {
					self.className = self.className.replace(' focus', '');
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 *
	 * @param container
	 */
	(function (container) {
		let touchStartFn,
			i,
			parentLink = container.querySelectorAll(
				'.menu-item-has-children > a, .page_item_has_children > a'
			);

		if ('ontouchstart' in window) {
			touchStartFn = function (e) {
				let menuItem = this.parentNode,
					i;

				if (!menuItem.classList.contains('focus')) {
					e.preventDefault();
					for (i = 0; i < menuItem.parentNode.children.length; ++i) {
						if (menuItem === menuItem.parentNode.children[i]) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove('focus');
					}
					menuItem.classList.add('focus');
				} else {
					menuItem.classList.remove('focus');
				}
			};

			for (i = 0; i < parentLink.length; ++i) {
				parentLink[i].addEventListener('touchstart', touchStartFn, false);
			}
		}
	})(container);
})();
/* eslint-enable */


document.addEventListener('DOMContentLoaded', function () {
	// Burger menu toggle
	const burgers = document.querySelectorAll('.navbar-burger');
	const menus = document.querySelectorAll('.navbar-menu');

	burgers.forEach(burger => {
		burger.addEventListener('click', function () {
			burgers.forEach(b => b.classList.toggle('is-active'));
			menus.forEach(m => m.classList.toggle('is-active'));
		});
	});

	// Sub menu level 2
	const submenus = document.querySelectorAll('.navbar-dropdown .navbar-item.has-dropdown');

	submenus.forEach(item => {
		item.addEventListener('mouseenter', () => {
			const dropdown = item.querySelector('.navbar-dropdown');
			if (!dropdown) return;

			// Reset
			dropdown.style.left = '';
			dropdown.style.right = '';
			dropdown.style.transform = '';

			const rect = dropdown.getBoundingClientRect();
			const overflowRight = rect.right > window.innerWidth;

			if (overflowRight) {
				// Flip to the left
				dropdown.style.left = 'auto';
				dropdown.style.right = '100%';
				dropdown.style.transform = 'translateX(0)';
			} else {
				// Stay on the right
				dropdown.style.left = '100%';
				dropdown.style.right = 'auto';
				dropdown.style.transform = 'translateX(0)';
			}
		});
	});
});

var prevScrollpos = window.pageYOffset;
var scrollThresholdTop = 250;
var scrollAmountBackUp = 0;
var nav = document.getElementById('navigation-top-menu');
var hideTimeout;

window.onscroll = function () {
	var navbarMenu = document.querySelector('.navbar-menu.nav-menu.is-active');
	var isNavbarExpanded = navbarMenu && navbarMenu.getAttribute('aria-expanded') === 'true';

	if (!isNavbarExpanded) {
		var currentScrollPos = window.pageYOffset;

		if (currentScrollPos > scrollThresholdTop) {
			if (prevScrollpos > currentScrollPos) {
				scrollAmountBackUp += prevScrollpos - currentScrollPos;

				if (scrollAmountBackUp >= scrollThresholdTop) {
					nav.style.visibility = 'visible';
					nav.style.transform = 'translateY(0)';
					clearTimeout(hideTimeout);
					scrollAmountBackUp = 0;
				}
			} else {
				nav.style.transform = 'translateY(-100%)';

				clearTimeout(hideTimeout);
				hideTimeout = setTimeout(() => {
					nav.style.visibility = 'hidden';
				}, 300); // match your CSS transition duration
			}

			prevScrollpos = currentScrollPos;
		} else {
			nav.style.visibility = 'visible';
			nav.style.transform = 'translateY(0)';
			clearTimeout(hideTimeout);
			scrollAmountBackUp = 0;
		}
	}
};