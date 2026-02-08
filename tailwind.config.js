import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    theme: {
    extend: {
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        },
        keyframes: {
            scroll: {
                '0%': { transform: 'translateY(0)', opacity: '0' },
                '30%': { opacity: '1' },
                '100%': { transform: 'translateY(14px)', opacity: '0' },
            },
        },
        animation: {
        'scroll-indicator': 'scroll 1.5s infinite',
        },
    },
    },

    plugins: [forms],
};
