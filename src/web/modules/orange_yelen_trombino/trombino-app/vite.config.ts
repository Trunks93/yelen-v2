import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import {resolve} from 'node:path';
import { nodePolyfills } from 'vite-plugin-node-polyfills'
import {fileURLToPath} from "node:url";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    nodePolyfills()
  ],
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    lib: {
      name: 'Trombino',
      entry: resolve(__dirname, 'src/main.ts'),
      formats: ['umd'],
      fileName(format, entryName) {
          return `trombino-app.${format}.js`
      },
    },
    rollupOptions: {
      input: resolve(__dirname, 'src/main.ts'),
    },
  },
  resolve: {
    alias: [
      { find: '@', replacement: fileURLToPath(new URL('./src', import.meta.url)) },
    ]
  },
  server: {
    proxy: {
      '/': 'http://yelen-v2.local/trombino',
    },
  },
})
