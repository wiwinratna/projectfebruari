import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/jobs.js',
                'resources/js/landing.js', // 👈 TAMBAH INI
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
