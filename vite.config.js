import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Globaux
                'resources/css/app.css',
                'resources/js/app.js',

                // Composants layout
                'resources/css/components/layout/header.css',
                'resources/css/components/layout/footer.css',

                // Pages
                'resources/css/web/home/index.css',
                'resources/css/web/creches/index.css',
                'resources/css/web/pedagogy/index.css',
                'resources/css/web/contact/index.css',
                'resources/css/web/legal/index.css',
                'resources/css/errors/index.css',

                // JS spécifique
                'resources/js/web/contact/index.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
