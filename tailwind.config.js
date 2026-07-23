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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
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
        },
    },

    plugins: [forms, typography],
};
