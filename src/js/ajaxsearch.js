
(function initAjaxSearch () {
  const __ = (window.wp && window.wp.i18n && window.wp.i18n.__) ? window.wp.i18n.__ : (s => s);

  const searchForm  = document.querySelector('form[role="search"]');
  if (!searchForm) return;

  const searchInput = searchForm.querySelector('input[name="s"]');

  // ---- wrap form in Bulma dropdown ----
  let dropdown = document.getElementById('search-dropdown');
  if (!dropdown) {
    dropdown = document.createElement('div');
    dropdown.id = 'search-dropdown';
    dropdown.className = 'dropdown';
    searchForm.parentNode.insertBefore(dropdown, searchForm);
    dropdown.appendChild(searchForm);
  }

  dropdown.insertAdjacentHTML(
    'beforeend',
    '<div class="dropdown-menu" role="menu">' +
      '<div class="dropdown-content mr-auto ml-auto" style="word-wrap: break-word; overflow-wrap: break-word; max-width:1024px;"></div>' +
    '</div>'
  );
  const resultsContainer = dropdown.querySelector('.dropdown-content');

  let currentPage = 1;
  let loading = false;

  function clearResults() { resultsContainer.innerHTML = ''; }
  function openDropdown()  { dropdown.classList.add('is-active'); }
  function closeDropdown() { dropdown.classList.remove('is-active'); }
  function isOpen()        { return dropdown.classList.contains('is-active'); }

  async function loadResults(page, append) {
    const searchTerm = (searchInput.value || '').trim();

    if (searchTerm.length < 3) {
      closeDropdown();
      clearResults();
      return;
    }

    loading = true;
    openDropdown();

    if (!append) {
      resultsContainer.innerHTML =
        '<div class="dropdown-item"><div class="message is-info is-small is-light mb-0">' +
        __(`Searching...`, 'eisbulma') +
        '<div class="loader is-loading"></div></div></div>';
    }

    // Build query for WP AJAX
    const params = new URLSearchParams({
      action: 'wp_ajax_search',
      s: searchTerm,
      nonce: (window.wp_ajax_search && window.wp_ajax_search.nonce) || '',
      page: String(page || 1)
    });

    try {
      const url = `${window.wp_ajax_search.ajaxurl}?${params.toString()}`;
      const res = await fetch(url, { method: 'GET', credentials: 'same-origin' });
      const response = await res.json();

      if (response && response.success) {
        let html = '';

        if (!append) {
          html += '<div class="dropdown-item">';
          html += `<div class="message is-success is-small mb-3"><div class="message-body py-2">Found ${response.data.count} results for "${response.data.search_term}"</div></div>`;
          html += '</div>';
        }

        (response.data.results || []).forEach(result => {
          html += `<a href="${result.url}" class="dropdown-item" style="white-space: normal;">`;
          html += '<article class="media">';
          if (result.thumbnail) {
            html += `<figure class="media-left"><p class="image is-48x48"><img src="${result.thumbnail}" alt="${result.title}"></p></figure>`;
          }
          html += '<div class="media-content" style="overflow: hidden;"><div class="content">';
          html += `<p class="has-text-weight-semibold mb-1" style="word-wrap: break-word; overflow-wrap: break-word;">${result.title}</p>`;
          html += `<p class="is-size-7 has-text-grey" style="word-wrap: break-word; overflow-wrap: break-word;">${result.excerpt}</p>`;
          html += '</div></div></article></a>';
        });

        if (window.wp_ajax_search.loading_method === 'pagination') {
          html += '<hr class="dropdown-divider">';
          html += '<div class="dropdown-item"><div class="buttons is-centered">';
          if (response.data.page > 1) {
            html += '<button class="button is-small is-primary pagination-previous">Previous</button>';
          }
          if (response.data.page < response.data.max_num_pages) {
            html += '<button class="button is-small is-primary pagination-next">Next</button>';
          }
          html += '</div></div>';
        }

        if (append) {
          resultsContainer.insertAdjacentHTML('beforeend', html);
        } else {
          resultsContainer.innerHTML = html;
        }
      } else {
        resultsContainer.innerHTML =
          `<div class="dropdown-item"><div class="notification is-warning mb-0">No results found for "${searchTerm}"</div></div>`;
      }
    } catch (err) {
      resultsContainer.innerHTML =
        `<div class="dropdown-item"><div class="notification is-danger mb-0">Search failed: ${err}</div></div>`;
    } finally {
      loading = false;
    }
  }

  // Debounce
  function debounce(fn, wait, immediate) {
    let t;
    return function (...args) {
      const later = () => { t = null; if (!immediate) fn.apply(this, args); };
      const callNow = immediate && !t;
      clearTimeout(t);
      t = setTimeout(later, wait);
      if (callNow) fn.apply(this, args);
    };
  }

  // Input listener
  searchInput.addEventListener('input', debounce(() => {
    currentPage = 1;
    loadResults(currentPage, false);
  }, 300));

  // Reposition hooks kept for API parity (no-op here)
  window.addEventListener('resize', () => { if (isOpen()) {/* no-op */} });
  window.addEventListener('scroll',  () => { if (isOpen()) {/* no-op */} });

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target)) closeDropdown();
  });

  // Prevent close on internal click
  resultsContainer.addEventListener('click', (e) => e.stopPropagation());

  // Pagination (event delegation)
  resultsContainer.addEventListener('click', (e) => {
    const prev = e.target.closest('.pagination-previous');
    const next = e.target.closest('.pagination-next');
    if (prev) {
      e.preventDefault(); e.stopPropagation();
      if (currentPage > 1) { currentPage--; loadResults(currentPage, false); }
    } else if (next) {
      e.preventDefault(); e.stopPropagation();
      currentPage++; loadResults(currentPage, false);
    }
  });

  // Infinite scroll
  resultsContainer.addEventListener('scroll', () => {
    if (window.wp_ajax_search.loading_method !== 'infinite') return;
    if (loading) return;
    if (resultsContainer.scrollTop + resultsContainer.clientHeight >= resultsContainer.scrollHeight - 20) {
      currentPage++;
      loadResults(currentPage, true);
    }
  });
})();
