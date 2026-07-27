import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    beige: '#fdfbf7', // Keep for compatibility if needed, or change to light pink
                    pink: '#f36a8d', // The vibrant SkimShop pink
                    'pink-light': '#fdf2f5',
                    'pink-hover': '#e65377',
                    text: '#1a1a1a',
                },
                surface: {
                    DEFAULT: '#ffffff',
                    alt: '#f8fafc',
                },
                muted: '#6b7280',
                accent: {
                    DEFAULT: '#334155',
                    soft: '#e5e7eb',
                },
                border: '#d1d5db',
            },
            borderRadius: {
                card: '20px',
            },
            boxShadow: {
                card: '0 10px 30px rgba(15, 23, 42, 0.06)',
            },
            animation: {
                blob: "blob 7s infinite",
            },
            keyframes: {
                blob: {
                    "0%": {
                        transform: "translate(0px, 0px) scale(1)",
                    },
                    "33%": {
                        transform: "translate(30px, -50px) scale(1.1)",
                    },
                    "66%": {
                        transform: "translate(-20px, 20px) scale(0.9)",
                    },
                    "100%": {
                        transform: "translate(0px, 0px) scale(1)",
                    },
                },
            },
        },
    },

    plugins: [forms, typography],
};
