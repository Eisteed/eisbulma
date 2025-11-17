// Main SCSS 
import '../styles/theme.scss';

// Import Bulma JS components

import './bulma.js';
import './bulma-navbar.js';
import './ajaxsearch.js';

document.addEventListener('DOMContentLoaded', () => {
if (document.querySelector('.product-accordion')) {
    import('./accordion.js');
}

if (document.getElementById('woo-filters-form')) {
    import('./search-filters.js'); 
}

if (document.querySelector('.embla__viewport')) {
  import('./embla-carousel.js');
}

});
// Import and initialize AOS (Animate On Scroll)
// import AOS from 'aos';
// import 'aos/dist/aos.css';
// AOS.init({
//   duration: 800,
//   once: true,
// });
