
function initAccordion() {
  const acc = document.querySelector('.product-accordion');
  if (!acc) return;

  const items = Array.from(acc.querySelectorAll('.accordion-item')); // <details>
  const getPanel = (d) => d.querySelector('.accordion-content');
  const DURATION_FALLBACK = 320;

  // init: ferme tout, ouvre le premier si aucun "open" en HTML
  items.forEach(d => {
    const p = getPanel(d);
    if (!p) return;
    if (!d.hasAttribute('open')) {
      p.style.maxHeight = null;
      p.classList.add('is-hidden');
    } else {
      p.classList.remove('is-hidden');
      p.style.maxHeight = p.scrollHeight + 'px';
    }
  });
  if (!items.some(d => d.hasAttribute('open')) && items[0]) {
    items[0].setAttribute('open', '');
    const p0 = getPanel(items[0]);
    if (p0) {
      p0.classList.remove('is-hidden');
      p0.style.maxHeight = p0.scrollHeight + 'px';
    }
  }

  // ferme tous sauf un
  function closeOthers(except) {
    items.forEach(d => {
      if (d === except) return;
      if (d.hasAttribute('open')) d.removeAttribute('open');
      const p = getPanel(d);
      if (p) {
        p.style.maxHeight = null;
        p.classList.add('is-hidden');
      }
    });
  }

  // scroll helper
  function getStickyOffset() {
    let offset = 0;
    const adminBar = document.getElementById('wpadminbar');
    if (adminBar && getComputedStyle(adminBar).position === 'fixed') {
      offset += adminBar.getBoundingClientRect().height;
    }
    document.querySelectorAll('.navbar.is-fixed-top, .site-header.is-fixed-top, header.is-fixed-top')
      .forEach(el => {
        if (getComputedStyle(el).position === 'fixed' && el.getBoundingClientRect().top <= 0) {
          offset += el.getBoundingClientRect().height;
        }
      });
    return offset + 12;
  }
  function scrollToSummary(d) {
    const summary = d.querySelector('summary');
    if (!summary) return;
    const y = summary.getBoundingClientRect().top + window.pageYOffset - getStickyOffset();
    window.scrollTo({ top: y, behavior: 'smooth' });
  }

  // écouter les toggles natifs des <details>
  items.forEach(d => {
    d.addEventListener('toggle', () => {
      const p = getPanel(d);
      if (!p) return;

      if (d.open) {
        closeOthers(d);
        p.classList.remove('is-hidden');
        // première hauteur
        p.style.maxHeight = p.scrollHeight + 'px';

        // après transition/chargements tardifs
        const onDone = () => {
          p.style.maxHeight = p.scrollHeight + 'px';
          scrollToSummary(d);
          p.removeEventListener('transitionend', onDone);
        };
        p.addEventListener('transitionend', onDone);
        setTimeout(onDone, DURATION_FALLBACK);
      } else {
        p.style.maxHeight = null;
        p.classList.add('is-hidden');
      }
    });

    // empêcher le "double" toggle si besoin ? Non, on laisse le natif gérer.
  });

  // resize: garder la bonne hauteur pour l’item ouvert
  window.addEventListener('resize', () => {
    const open = items.find(d => d.open);
    if (!open) return;
    const p = getPanel(open);
    if (p) p.style.maxHeight = p.scrollHeight + 'px';
  });

}

initAccordion();