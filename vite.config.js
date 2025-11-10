import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/institute.css',
                'resources/js/institute/login.js',
                'resources/js/institute/home.js',
            ],
            refresh: true,
        }),
    ],
});
