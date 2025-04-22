jQuery(document).ready(function () {
  // Functions to open and close a modal
  function openModal($el) {
    $el.addClass('is-active');
  }

  function closeModal($el) {
    $el.removeClass('is-active');
  }

  function closeAllModals() {
    jQuery('.modal').each(function () {
      closeModal(jQuery(this));
    });
  }

  // Generic trigger handler
  jQuery('.js-modal-trigger').on('click', function () {
    console.log("MODAL !");
    const modalId = jQuery(this).data('target');
    const $target = jQuery('#' + modalId);
    openModal($target);
  });


  // Close modal when clicking background, close buttons, etc.
  jQuery('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button').on('click', function () {
    const $modal = jQuery(this).closest('.modal');
    closeModal($modal);
  });

  // Close modal with Escape key
  jQuery(document).on('keydown', function (event) {
    if (event.key === 'Escape') {
      closeAllModals();
    }
  });

  // Close Bulma notifications
  jQuery(document).on('click', '.notification .delete', function () {
    const $notification = jQuery(this).closest('.notification');
    $notification.remove();
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
