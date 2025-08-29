import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
    './resources/**/*.vue',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
  ],
  // 👇 mantém essas classes no CSS final mesmo se o purgador não as “ver”
  safelist: [
    'whitespace-nowrap',
    'inline-flex',
    'items-center',
    'gap-1',
    'rounded-full',
    'px-3',
    'py-1',
    'text-xs',
  ],
  theme: { extend: {} },
  plugins: [forms],
}
