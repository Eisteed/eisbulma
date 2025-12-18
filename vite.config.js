import { defineConfig, loadEnv } from 'vite';
import path from 'path';
import os from "os";
import fullReload from 'vite-plugin-full-reload';
import { readFileSync, existsSync } from 'node:fs';

const home = os.homedir();

export default defineConfig(({ mode, command }) => {
  const env = loadEnv(mode, process.cwd(), '');

  const PORT = 5173;

  // ====== Site Config ======
  const HOST = env.LOCAL_DOMAIN || 'test.local';


  // Find Local by Flywheel SSL cert path
  // Windows 
  const SSL_DIR_WIN = path.join(
    os.homedir(),
    "AppData", "Roaming", "Local", "run", "router", "nginx", "certs"
  );

  // Linux
  const SSL_DIR_LINUX = path.join(
    os.homedir(),
    ".config", "Local", "run", "router", "nginx", "certs"
  );

  // Pick depending on OS + allow override
  const SSL_DIR =
    env.SSL_DIR ||
    (process.platform === "win32" ? SSL_DIR_WIN : SSL_DIR_LINUX);


  // Set path - check if files exist, otherwise try with www. prefix
  let HTTPS_KEY_PATH = env.HTTPS_KEY_PATH || path.join(SSL_DIR, `${HOST}.key`);
  let HTTPS_CERT_PATH = env.HTTPS_CERT_PATH || path.join(SSL_DIR, `${HOST}.crt`);

  // If files don't exist, try with www. prefix
  if (!env.HTTPS_KEY_PATH && !existsSync(HTTPS_KEY_PATH)) {
    HTTPS_KEY_PATH = path.join(SSL_DIR, `www.${HOST}.key`);
  }
  if (!env.HTTPS_CERT_PATH && !existsSync(HTTPS_CERT_PATH)) {
    HTTPS_CERT_PATH = path.join(SSL_DIR, `www.${HOST}.crt`);
  }
  // ==========================

  const isDev = command === 'serve';
  const themeName = path.basename(__dirname);

  return {
    root: '.',
    base: isDev ? '/' : `/wp-content/themes/${themeName}/dist/`,

    build: {
      outDir: 'dist',
      assetsDir: 'assets',
      manifest: true,
      emptyOutDir: true,
      sourcemap: isDev,
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'src/js/main.js'),
          classInject: path.resolve(__dirname, 'src/js/styles/class-inject.js'),
        },
      },
    },

    css: {
      devSourcemap: true,
      preprocessorOptions: {
        scss: {
          additionalData: `
            @use "sass:math";

            // Colors from .env (fallback to defaults if not set)
            $primary: ${env.COLOR_PRIMARY || '#d8002b'};
            $secondary: ${env.COLOR_SECONDARY || '#296795'};
            $link: ${env.COLOR_LINK || '#af1c39'};
            $text: ${env.COLOR_TEXT || '#aab1bf'};
            $info: ${env.COLOR_INFO || '#5972bd'};
            $success: ${env.COLOR_SUCCESS || '#6fbd69'};
            $warning: ${env.COLOR_WARNING || '#c6cd5b'};
            $danger: ${env.COLOR_DANGER || '#ad6767'};
            $grey-dark: ${env.COLOR_GREY_DARK || '#252525'};
            $grey-light: ${env.COLOR_GREY_LIGHT || '#c4c4c4'};
            $grey-lighter: ${env.COLOR_GREY_LIGHTER || '#e9e9e9'};
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
