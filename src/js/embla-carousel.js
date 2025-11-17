import EmblaCarousel from 'embla-carousel';
import Autoplay from 'embla-carousel-autoplay';

export function initEmbla(root) {
  if (root.__emblaApi) return root.__emblaApi;

  // Default options
  let options = { loop: true };

  const raw = root.dataset.embla;
  if (raw) {
    try {
      options = JSON.parse(raw);
    } catch (e) {
      console.error('data-embla JSON invalide', e);
    }
  }

  // Read autoplay config from data-autoplay (or from options.autoplay if present)
  // data-autoplay supports:
  // - empty / "true" -> default autoplay options
  // - a JSON string like '{"delay":3000,"stopOnInteraction":false}'
  // - a number string like "3000" -> interpreted as delay in ms
  let autoplayOptions = null;
  const rawAutoplay = root.dataset.autoplay;
  if (rawAutoplay !== undefined) {
    if (rawAutoplay === '' || rawAutoplay === 'true') {
      autoplayOptions = {};
    } else {
      try {
        autoplayOptions = JSON.parse(rawAutoplay);
      } catch (e) {
        const n = Number(rawAutoplay);
        if (!Number.isNaN(n)) autoplayOptions = { delay: n };
        else console.error('data-autoplay JSON invalide', e);
      }
    }
  } else if (options && options.autoplay) {
    // allow autoplay config inside data-embla JSON under "autoplay"
    autoplayOptions = options.autoplay;
    // remove it from options so Embla options don't receive unexpected keys
    delete options.autoplay;
  }

  const viewport = root.querySelector('.embla__viewport') || root;

  // Prepare plugins array
  const plugins = [];
  if (autoplayOptions !== null) {
    plugins.push(Autoplay(autoplayOptions));
  }

  const api = EmblaCarousel(viewport, options, plugins);

  const prevBtn = root.querySelector('.embla__button--prev')
  const nextBtn = root.querySelector('.embla__button--next')
  const dotsContainer = root.querySelector('.embla__dots');

  if (prevBtn) prevBtn.addEventListener('click', () => api.scrollPrev());
  if (nextBtn) nextBtn.addEventListener('click', () => api.scrollNext());

  // Dots
  if (dotsContainer) {
    const slideCount = api.slideNodes().length;
    const dots = [];

    for (let i = 0; i < slideCount; i++) {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'embla__dot';
      dot.addEventListener('click', () => api.scrollTo(i));
      dotsContainer.appendChild(dot);
      dots.push(dot);
    }

    const setSelectedDot = () => {
      const selectedIndex = api.selectedScrollSnap();
      dots.forEach((dot, index) => {
        dot.classList.toggle('is-active', index === selectedIndex);
      });
    };

    api.on('select', setSelectedDot);
    api.on('init', setSelectedDot);
    setSelectedDot();
  }

  root.__emblaApi = api;
  return api;
}

document.querySelectorAll('.embla').forEach((root) => {
  initEmbla(root);
});
