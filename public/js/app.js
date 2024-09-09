import { defineConfig } from 'vite'
import laravel from 'laravel'

export default defineConfig({
  transpileVueScript: true,
  resolve: {
    alias: {
      '@': laravel.input('resources'),
    },
  },
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      tailwindCss: [
        './node_modules/tailwindcss/dist/css/tailwind.css',
      ],
    }),
  ],
})