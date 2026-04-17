import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // You have this

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), // <--- ADD THIS LINE
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});