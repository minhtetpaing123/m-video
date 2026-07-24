import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/css/home.css'],
            refresh: true,
        }),
        // tailwindcss() ကိုဖြုတ်ပါ! ဘာလို့လဲဆိုတော့ @tailwind directives ကို app.css မှာသုံးထားလို့
    ],
    server: {
        watch: {
            usePolling: true,
            interval: 1000,
            ignored: [
                '**/vendor/**',
                '**/node_modules/**',
                '**/storage/**',
                '**/bootstrap/cache/**',
                '**/tests/**',
                '**/database/**',
                '**/config/**',
                '**/public/vendor/**',
                '**/docs/**',
                '**/coverage/**',
                '**/.git/**',
                '**/.idea/**',
                '**/.vscode/**'
            ]
        }
    },
});