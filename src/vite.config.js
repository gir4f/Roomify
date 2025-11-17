import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'], // Breeze mengatur ini
            refresh: true,
        }),
        vue({ // Breeze menambahkan ini
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // --- BLOK PERBAIKANNYA ADA DI SINI ---
    server: {
        host: '0.0.0.0', // Ini agar Vite mendengarkan di semua IP di dalam container
        port: 5173,
        hmr: {
            host: 'localhost', // Ini memaksa browser untuk terhubung ke 'localhost'
        },
    },
});