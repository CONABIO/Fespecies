import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '172.16.3.63'
    },
    cors: {
        origin: 'http://172.16.3.78:8001',
        allowedHeaders: ['Content-Type', 'Authorization'],
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
