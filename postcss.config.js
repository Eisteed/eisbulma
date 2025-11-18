const cssnano = require('cssnano');
const pruneVar = require('postcss-prune-var');
const autoprefixer = require('autoprefixer');
const purgecss = require('@fullhuman/postcss-purgecss').default;

module.exports = {
  plugins: [
    autoprefixer(),

    purgecss({
      content: [
        // include all theme php/js so extractor sees the classes
        './**/*.php',
        './src/js/**/*.js',
        '!./node_modules/**',
      ],

      safelist: {

        standard: [
          // WP defaults
          'alignleft', 'alignright', 'aligncenter', 'alignwide', 'alignfull',
          'screen-reader-text', 'sticky', 'gallery-caption', 'bypostauthor',
          'logged-in', 'admin-bar',
          /^img$/,
          /^svg$/,

          // Menus/widgets
          'menu-item-has-children', 'current-menu-item', 'current-menu-parent',
          'current-menu-ancestor', 'current-page-parent', 'current-page-ancestor',
          'sub-menu', 'children',

          // Bulma state toggles & colors
          'is-active', 'is-loading', 'is-hidden', 'is-visible', 'is-expanded', 'is-selected',
          'is-primary', 'is-success', 'is-info', 'is-warning', 'is-danger', 'is-link',

          // Bulma modal
          "modal", "modal-background",

          // Content stuff that can be used in gutenberg
          'columns', 'column', 'content', 'blockquote', 'pre', 'p', 'ul', 'ol', 'dl',
          'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'table', 'thead', 'tfoot', 'tbody', 'tr', 'th', 'td',
          'img', 'video', 'figure', 'figcaption', 'abbr', 'code', 'sup', 'sub', 'strong', 'em', 'small', 'a',

          // WooCommerce — keep everything starting with woocommerce
          /^woocommerce/,
          'flex-control-nav.flex-control-thumbs',

          // Custom
          'is-fullwidth-breakout',
        ],
        deep: [
          /\.woocommerce\b/,
          /\.embla\b/,
        ],
      },

      // remove unless you truly need ALL animations/vars
      keyframes: true,
      variables: true,
      fontFace: true,

      // robust extractor for PHP/JS
      defaultExtractor: content =>
        content.match(/[\w-/:]+(?<!:)/g) || [],

      // Explicitly drop whole families you don’t use
      blocklist: [
        // If not used in your theme, uncomment:
        // /^breadcrumb/, /^dropdown/, /^menu/, /^tabs/, /^pagination/,
        // /^progress/, /^table/, /^tag/, /^level/, /^media/, /^notification/,
      ],
    }),

    pruneVar(),

    cssnano({
      preset: ['default', {
        discardComments: { removeAll: true },
        zindex: false,              // safer for WP
        reduceInitial: false,       // keep initial for compatibility
        calc: { precision: 5 },     // safe calc folding
        normalizeString: { preferredQuote: 'single' },
      }]
    }),
  ],
};