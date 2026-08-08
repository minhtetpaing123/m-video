import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // <--- ⚡ ဒီနေရာမှာ Dark Mode Class ကို ဖြည့်သွင်းပေးလိုက်ပါတယ်

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        
        // Add these lines if you have components
        './resources/views/components/**/*.blade.php',
        
        // Livewire Paths
        './app/Livewire/**/*.php',
        './app/Http/Livewire/**/*.php',
        
        // Or use this to include everything
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'myanmar': ['Noto Sans Myanmar', 'sans-serif'], // Add Myanmar font
            },
        },
    },

    plugins: [forms],
};
