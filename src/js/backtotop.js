

// BACK TO TOP Button
// Disabled because who even use this with sticky header :) ? 
// Still keeping it here when some boomers ask for it

document.addEventListener('DOMContentLoaded', () => {
  const backToTopHTML = `
    <div id="backToTop" style="position: fixed; bottom: 5rem; right: 20px; z-index: 9999; display:none;">
      <button class="button is-primary is-rounded">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 256 256">
          <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm37.66-101.66a8,8,0,0,1-11.32,11.32L136,107.31V168a8,8,0,0,1-16,0V107.31l-18.34,18.35a8,8,0,0,1-11.32-11.32l32-32a8,8,0,0,1,11.32,0Z"></path>
        </svg>
      </button>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', backToTopHTML);

  const backToTopButton = document.getElementById('backToTop');

  window.addEventListener('scroll', () => {
    if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
      backToTopButton.style.display = 'block';
    } else {
      backToTopButton.style.display = 'none';
    }
  });

  backToTopButton.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
});