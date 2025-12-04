// Main SCSS 
import '../styles/theme.scss';

// JS Components
import './bulma.js';
import './bulma-navbar.js';
import './ajax-search.js';
import './cart.js';
//import './backtotop.js';

// import AOS from 'aos';
// import 'aos/dist/aos.css';

function init() {

  if (document.querySelector('.product-accordion')) {
    import('./accordion.js');
  }

  if (document.getElementById('woo-filters-form')) {
    import('./search-filters.js');
  }

  if (document.querySelector('.embla__viewport')) {
    import('./embla-carousel.js');
  }

  // AOS.init({
  //   duration: 500,
  //   once: true, // Animate every time you scroll
  //   easing: 'ease-in-out'
  // });

}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}