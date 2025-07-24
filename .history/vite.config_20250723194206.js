import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// Se você tem a linha abaixo, remova-a. Se não, apenas ignore.
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        // Se você tinha 'tailwindcss()', remova essa linha também.
        // Por exemplo, se tinha 'tailwindcssVite()' ou similar.
    ],
    css: {
        postcss: {
            // ADICIONE ESTES PLUGINS AQUI. Eles farão o trabalho.
            // O Vite pegará o postcss.config.js automaticamente, mas ser explícito aqui pode ajudar.
            plugins: [require("tailwindcss"), require("autoprefixer")],
        },
    },
});
