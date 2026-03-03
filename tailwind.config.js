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
            colors: {
                'atcl': {
                    'blue': '#1e3a8a',
                    'grey-dark': '#4b5563',
                    'grey-light': '#f3f4f6',
                    'white': '#f9fafb',
                },
            },
        },
    },

    plugins: [forms],
};
