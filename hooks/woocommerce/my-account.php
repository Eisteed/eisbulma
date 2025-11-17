<?php

add_action('woocommerce_before_account_navigation', function () {
  echo '<section class="section">
          <div class="container">
            <div class="columns">
              <div class="column is-one-quarter">';
}, 1);

add_action('woocommerce_after_account_navigation', function () {
   echo '     </div>
              <div class="column">
                <div class="woocommerce-notices-wrapper">
                </div>';

}, 99);

// add_action('woocommerce_account_content', function () {
//   echo '      </div>
//             </div>
//           </div>
//         </section>';
// }, 999);

// Supprimer les classes par défaut sur <li>
add_filter('woocommerce_account_menu_item_classes', function ($classes, $endpoint) {
  // Conserver seulement 'is-active' si présent
  $keep = array_intersect($classes, ['is-active']);
  return $keep;
}, 10, 2);

// Ajouter 'is-active' sur <a> (nécessite un override léger de navigation.php ou JS)
// Variante JS si vous ne voulez toucher à aucun template :
add_action('wp_footer', function () {
  if ( ! is_account_page() ) return; ?>
  <script>
    document.querySelectorAll('.woocommerce-MyAccount-navigation a').forEach(a=>{
      if(a.closest('li')?.classList.contains('is-active')) a.classList.add('is-active');
    });
    // Convertit la nav Woo en Bulma côté DOM
    const nav = document.querySelector('.woocommerce-MyAccount-navigation');
    if(nav){ nav.className = 'menu'; const ul = nav.querySelector('ul'); if(ul) ul.className='menu-list'; }
  </script>
<?php });
