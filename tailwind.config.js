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
            colors: {
                // Red palette (sudah default di Tailwind, tapi bisa di-extend kalau perlu)
                primary: {
                50: '#FEF2F2',
                100: '#FEE2E2',
                200: '#FECACA',
                300: '#FCA5A5',
                400: '#F87171',
                500: '#EF4444',  // Primary Red
                600: '#DC2626',
                700: '#B91C1C',
                800: '#991B1B',
                900: '#7F1D1D',
                },
                // Glassmorphism custom colors
                glass: {
                white: 'rgba(255, 255, 255, 0.7)',
                red: 'rgba(239, 68, 68, 0.15)',
                border: 'rgba(255, 255, 255, 0.5)',
                shadow: 'rgba(0, 0, 0, 0.05)',
                }
            },
            backdropBlur: {
                xs: '2px',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.05)',
                'glass-lg': '0 12px 40px 0 rgba(0, 0, 0, 0.08)',
            }
        },
    },

    plugins: [forms],
};
