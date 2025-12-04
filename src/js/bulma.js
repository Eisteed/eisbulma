/**
 * Handles modal & notification functionality.
 * Added auto padding for page content (dynamic header height)
 */
document.addEventListener('DOMContentLoaded', () => {
	// ---- Sticky nav margin / scroll padding ----
	const nav = document.querySelector('#navigation-top-menu');
	const html = document.documentElement;

	if (nav) {
		const setMargin = () => {
			const h = nav.offsetHeight;
			html.style.scrollPaddingTop = `${h}px`;
			html.style.setProperty('--nav-h', `${h}px`);
		};

		setMargin();
		new ResizeObserver(setMargin).observe(nav);
		window.addEventListener('resize', setMargin);
	}
	// ---- Modals ----
	function openModal(el) {
		if (!el) return;
		el.classList.add('is-active');
		html.classList.add('is-clipped');

		const input = el.querySelector('input[name="s"]');
		if (!input) return;

		const tryFocus = () => {
			const style = window.getComputedStyle(input);
			const isVisible =
				style.display !== 'none' &&
				style.visibility !== 'hidden' &&
				style.opacity !== '0';

			if (isVisible) input.focus();
		};

		tryFocus();
		setTimeout(tryFocus, 100);
	}

	function closeModal(el) {
		if (!el) return;
		el.classList.remove('is-active');
		html.classList.remove('is-clipped');
	}

	function closeAllModals() {
		document.querySelectorAll('.modal.is-active').forEach(closeModal);
	}

	// Ouvre la modale ciblée via data-modal-target
	document.querySelectorAll('[data-modal-target]').forEach(trigger => {
		trigger.addEventListener('click', e => {
			e.preventDefault();
			const targetId = trigger.getAttribute('data-modal-target');
			const modal = document.getElementById(targetId);
			if (modal) openModal(modal);
		});
	});


	// Close modal when clicking background, close buttons, etc.
	const modalCloseSelectors = [
		'.modal-background',
		'.modal-close',
		'.modal-close-custom',
		'.modal-card-head .delete',
		'.modal-card-foot .button'
	].join(', ');

	document.querySelectorAll(modalCloseSelectors).forEach(el => {
		el.addEventListener('click', () => {
			const modal = el.closest('.modal');
			closeModal(modal);
		});
	});

	// Close modal with Escape key
	document.addEventListener('keydown', event => {
		if (event.key === 'Escape' || event.key === 'Esc') {
			closeAllModals();
		}
	});

	// Close Bulma notifications (event delegation)
	document.addEventListener('click', event => {
		const deleteBtn = event.target.closest('.notification .delete');
		if (!deleteBtn) return;

		const notification = deleteBtn.closest('.notification');
		if (notification) {
			notification.remove();
		}
	});
});
