import { fileURLToPath, URL } from 'node:url'
import {resolve} from 'node:path';
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    lib: {
      name: 'Chat App',
      entry: resolve(__dirname, 'src/main.ts'),
      formats: ['umd'],
      fileName(format, entryName) {
        return `chat-app.${format}.js`
      },
    },
    rollupOptions: {
      input: resolve(__dirname, 'src/main.ts'),
    },
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  server: {
    proxy: {
      '/': 'http://yelen-v2.local/chat-app',
    },
  }
})
