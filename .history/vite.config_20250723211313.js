import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
// ADICIONE ESTAS DUAS LINHAS DE IMPORT:
import tailwindcss from "tailwindcss";
import autoprefixer from "autoprefixer";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                // AGORA CHAME AS FUNÇÕES IMPORTADAS:
                tailwindcss(),
                autoprefixer(),
            ],
        },
    },
});
