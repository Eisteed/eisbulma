// Main SCSS 
import '../styles/theme.scss';

// Custom JS per project/theme - auto-import all .js files in custom folder
import.meta.glob('./custom/**/*.js', { eager: true });

// JS Components

import './bulma/bulma.js';
import './bulma/bulma-navbar.js';
//import './bulma/bulma-tabs.js';
import './ui/ajax-search.js';
import './woocommerce/cart.js';
//import './backtotop.js';

// import AOS from 'aos';
// import 'aos/dist/aos.css';

function init() {

  if (document.querySelector('.product-accordion')) {
    import('./woocommerce/accordion.js');
  }

  if (document.getElementById('woo-filters-form')) {
    import('./woocommerce/search-filters.js');
  }

  if (document.querySelector('.embla__viewport')) {
    import('./ui/embla-carousel.js');
  }

  //  AOS.init({
  //    duration: 500,
  //    once: true, // Animate every time you scroll
  //    easing: 'ease-in-out'
  //  });

}



if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}