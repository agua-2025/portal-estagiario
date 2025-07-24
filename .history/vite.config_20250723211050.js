// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            // Removemos 'resources/css/app.css' da lista de inputs
            input: [
                // 'resources/css/app.css', // <-- Comentado!
                "resources/js/app.js", // O JS ainda é um input, e ele importa o CSS compilado
            ],
            refresh: true,
        }),
    ],
});
