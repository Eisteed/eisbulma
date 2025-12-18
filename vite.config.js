import { defineConfig, loadEnv } from 'vite';
import path from 'path';
import os from "os";
import fullReload from 'vite-plugin-full-reload';
import { readFileSync } from 'node:fs';

const home = os.homedir();

export default defineConfig(({ mode, command }) => {
  const env = loadEnv(mode, process.cwd(), '');

  // ====== Site Config ======
  const HOST = env.LOCAL_DOMAIN || 'test.local';

  const SSL_DIR = path.join(
    home, "AppData", "Roaming", "Local", "run", "router", "nginx", "certs"
  );
  const PORT = 5173;
  const HTTPS_KEY_PATH = path.join(SSL_DIR, `${HOST}.key`);
  const HTTPS_CERT_PATH = path.join(SSL_DIR, `${HOST}.crt`);
  // ==========================

  const isDev = command === 'serve';

  return {
    root: '.',
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
          classInject: path.resolve(__dirname, 'src/js/class-inject.js'),
        },
      },
    },

    css: {
      devSourcemap: true,
      preprocessorOptions: {
        scss: {
          additionalData: `@use "sass:math";`,
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
        'hooks/**/*.php',
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
