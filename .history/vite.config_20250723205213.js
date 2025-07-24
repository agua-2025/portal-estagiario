// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css", // Arquivo de entrada CSS
                "resources/js/app.js", // Arquivo de entrada JS (que importa o CSS)
            ],
            refresh: true, // Habilita hot-reloading para arquivos Blade, etc.
        }),
    ],
    // Esta seção é importante para garantir o processamento do PostCSS/Tailwind
    css: {
        postcss: {
            // Vite normalmente procura por postcss.config.js automaticamente,
            // mas especificar aqui pode ajudar em alguns casos.
            // Deixe este objeto vazio ou remova a seção `css` inteiramente
            // se quiser confiar na detecção automática via postcss.config.js.
            // Se especificar, aponte para o arquivo de configuração:
            // plugins: [
            //     require('tailwindcss'),
            //     require('autoprefixer'),
            // ]
        },
    },
});
