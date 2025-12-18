/**
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

document.addEventListener('DOMContentLoaded', function () {
	// Conteneur principal du menu
	const container = document.getElementById('navigation-top-menu');
	if (!container) return;

	// Bouton burger (WordPress/Bulma)
	const button = container.querySelector('.navbar-burger');
	if (!button) return;

	// Menu principal (Bulma)
	const menu = document.getElementById('main-menu'); // .navbar-menu
	if (!menu) {
		button.style.display = 'none';
		return;
	}

	// Marque le menu comme nav-menu (pour la logique de focus)
	if (!menu.classList.contains('nav-menu')) {
		menu.classList.add('nav-menu');
	}

	// ARIA initial
	button.setAttribute('aria-expanded', 'false');
	menu.setAttribute('aria-expanded', 'false');

	// --- Gestion burger Bulma + classes du premier script ---

	const burgers = container.querySelectorAll('.navbar-burger');
	const menus = container.querySelectorAll('.navbar-menu');

	burgers.forEach(burger => {
		burger.addEventListener('click', function () {
			// on décide de l'état global à appliquer
			const willBeActive = !menu.classList.contains('is-active');

			// Bulma : toggle .is-active sur tous les burgers + menus
			burgers.forEach(b => b.classList.toggle('is-active', willBeActive));
			menus.forEach(m => m.classList.toggle('is-active', willBeActive));

			// Ancien script : classe .toggled sur le container
			container.classList.toggle('toggled', willBeActive);

			// ARIA
			button.setAttribute('aria-expanded', willBeActive ? 'true' : 'false');
			menu.setAttribute('aria-expanded', willBeActive ? 'true' : 'false');
			// Empêche le scroll quand le menu est ouvert
			document.documentElement.classList.toggle('is-clipped', willBeActive);
		});
	});

	// --- Gestion focus clavier comme dans le premier script ---

	const links = menu.getElementsByTagName('a');

	for (let i = 0, len = links.length; i < len; i++) {
		links[i].addEventListener('focus', toggleFocus, true);
		links[i].addEventListener('blur', toggleFocus, true);
	}

	function toggleFocus() {
		let self = this;

		// On remonte jusqu'à l'élément qui a .nav-menu
		while (self && self.className.indexOf('nav-menu') === -1) {
			// Sur les <li>, on toggle .focus
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

	// --- Gestion touch pour les sous-menus (tablettes / mobiles) ---

	(function (container) {
		const parentLink = container.querySelectorAll(
			'.menu-item-has-children > a, .page_item_has_children > a'
		);

		if ('ontouchstart' in window && parentLink.length) {
			const touchStartFn = function (e) {
				const menuItem = this.parentNode;

				if (!menuItem.classList.contains('focus')) {
					// 1er tap : on ouvre le sous-menu
					e.preventDefault();

					// On enlève .focus sur les frères
					const siblings = menuItem.parentNode.children;
					for (let i = 0; i < siblings.length; i++) {
						if (siblings[i] !== menuItem) {
							siblings[i].classList.remove('focus');
						}
					}
					menuItem.classList.add('focus');
				} else {
					// 2e tap : on referme
					menuItem.classList.remove('focus');
				}
			};

			for (let i = 0; i < parentLink.length; i++) {
				parentLink[i].addEventListener('touchstart', touchStartFn, false);
			}
		}
	})(container);

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


// AUTO HIDE ON SCROLL DOWN / SHOW ON SCROLL UP
var prevScrollpos = window.pageYOffset;
var scrollThresholdTop = 250;
var scrollAmountBackUp = 0;
var nav = document.getElementById('navigation-top-menu');
var hideTimeout;
const adminBar = document.getElementById('wpadminbar');
const adminBarHeight = adminBar ? parseInt(
	getComputedStyle(document.documentElement)
		.getPropertyValue('--wp-admin--admin-bar--height')
) || 32 : 0;
window.onscroll = function () {
	var navbarMenu = document.querySelector('.navbar-menu.nav-menu.is-active');
	var isNavbarExpanded = navbarMenu && navbarMenu.getAttribute('aria-expanded') === 'true';
	if (window.innerWidth < 600 && adminBar) {
		if (window.scrollY > adminBarHeight) {
			nav.style.top = '0px';
		} else {
			nav.style.top = adminBarHeight + 'px';
		}
	}
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