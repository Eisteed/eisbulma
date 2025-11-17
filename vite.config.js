import { defineConfig } from 'vite';
import path from 'path';
import fullReload from 'vite-plugin-full-reload';
import { readFileSync } from 'node:fs';

// ====== Site Config ======
const HOST = 'wolz.local';
const PORT = 5173;
const HTTPS_KEY_PATH = `C:/Users/eisteed/AppData/Roaming/Local/run/router/nginx/certs/${HOST}.key`;
const HTTPS_CERT_PATH = `C:/Users/eisteed/AppData/Roaming/Local/run/router/nginx/certs/${HOST}.crt`;
// ==========================

export default defineConfig(({ command }) => {
  const isDev = command === 'serve';

  return {
    root: '.', // theme root
    base: isDev ? '/' : '/wp-content/themes/eisbulma/dist/',

    build: {
      outDir: 'dist',
      assetsDir: 'assets',
      manifest: true,
      emptyOutDir: true,
      sourcemap: isDev,
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'src/js/main.js'),
        },
      },
    },

    css: {
      devSourcemap: true,
      preprocessorOptions: {
        scss: {
          additionalData: `
            @use "sass:math";
          `,
        },
      },
    },

    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
        '~bulma': path.resolve(__dirname, 'node_modules/bulma'),
      },
    },

    plugins: [
      fullReload([
        '*.php',
        'template-parts/**/*.php',
        'template/**/*.php',
        'inc/**/*.php',
      ]),
    ],

    server: {
      host: HOST,
      port: PORT,
      strictPort: true,
      https: {
        key: readFileSync(HTTPS_KEY_PATH),
        cert: readFileSync(HTTPS_CERT_PATH),
      },
      origin: `https://${HOST}:${PORT}`,
      hmr: {
        protocol: 'wss',
        host: HOST,
        port: PORT,
      },
      cors: true,
      open: false,
      watch: {
        usePolling: true,
        interval: 100,
      },
    },
  };
});
