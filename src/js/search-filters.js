(function ($) {
const $form = $('#woo-filters-form');
const $wrap = $('#woo-products .woo-products-inner');


// Mobile drawer
const $drawer = $('#woo-filters');
$('#woo-filter-open').on('click', function () { $drawer.addClass('is-open'); });
$('#woo-filter-close').on('click', function () { $drawer.removeClass('is-open'); });


// Submit helper
function fetchProducts(page) {
const formData = $form.serializeArray();
const payload = { action: 'woo_bulma_filter_products', nonce: WooBulmaFilters.nonce };
formData.forEach(f => payload[f.name] = f.value);
if (page) payload.page = page;


// Reflect state in URL (so back/refresh keeps filters)
const url = new URL(window.location);
url.search = $("#woo-filters-form").serialize();
history.replaceState({}, '', url);


$wrap.addClass('is-loading');


return $.post(WooBulmaFilters.ajaxUrl, payload).done(function (resp) {
if (resp && resp.success) {
$wrap.html(resp.data.html);
}
}).always(function () {
$wrap.removeClass('is-loading');
// Close drawer on mobile after applying
$drawer.removeClass('is-open');
});
}


// Auto-submit on change
$form.on('change', 'select, input[type="checkbox"]', function () {
fetchProducts(1);
});


// Manual submit (Apply)
$form.on('submit', function (e) {
e.preventDefault();
fetchProducts(1);
});


// Reset
$('#woo-filters-reset').on('click', function () {
$form[0].reset();
fetchProducts(1);
});


// Delegate pagination clicks to AJAX
$(document).on('click', '.pagination a', function (e) {
const href = $(this).attr('href');
if (!href) return; // safety
const m = href.match(/[?&]paged=(\d+)/);
if (m) {
e.preventDefault();
fetchProducts(parseInt(m[1], 10));
}
});
})(window.jQuery);