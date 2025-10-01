import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(async () => {
    return {
        plugins: [
            laravel({
                input: [
                    'resources/js/record-switcher.js',
                    'resources/css/record-switcher.css',
                ],
                publicDirectory: 'dist',
                refresh: false,
            }),
            tailwindcss(),
        ],
        server: {
            open: false,
            hmr: false,
        },
        build: {
            manifest: false,
            outDir: './resources/dist',
            assetsInlineLimit: 0,
            rollupOptions: {
                preserveEntrySignatures: 'strict',
                output: {
                    entryFileNames: '[name].js',
                    chunkFileNames: '[name].js',
                    assetFileNames: '[name].[ext]',
                },
                treeshake: {
                    moduleSideEffects: (id) => {
                        return id.includes('record-switcher.js')
                    },
                },
            },
        },
    }
})