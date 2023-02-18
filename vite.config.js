import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue2 from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel([
            'resources/ts/bootstrap.ts',
            'resources/ts/lang-loader.ts',
            'resources/ts/app.ts',
            'resources/ts/dashboard.ts',
            'resources/ts/cashier.ts',
            'resources/ts/update.ts',
            'resources/ts/pos-init.ts',
            'resources/ts/pos.ts',
            'resources/ts/auth.ts',
            'resources/ts/setup.ts',
            'resources/ts/popups.ts',

            'resources/sass/app.scss',
            // TODO: other SASS files
        ]),
        vue2({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/ts/'
        }
    },
});
