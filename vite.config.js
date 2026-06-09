import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

import { cloudflare } from "@cloudflare/vite-plugin";

export default defineConfig({
    plugins: [tailwindcss(), laravel({
        input: [
            'resources/css/app.css',
            'resources/js/app.js'
        ],
        refresh: true,
    }), cloudflare()],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: 'manifest.json',
    }
});