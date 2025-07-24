import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    // ADICIONE ESTA SEÇÃO AQUI:
    css: {
        postcss: {}, // Apenas esta linha vazia, ela força o Vite a procurar o postcss.config.js
    },
});
