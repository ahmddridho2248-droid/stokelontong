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
                primary: '#84DCC6',      // Aquamarine
                accent: '#FFA69E',       // Pastel Pink
                background: '#F7FAF8',   // Off-White
                surface: '#FFFFFF',      // White
                'text-primary': '#4A5568', // Dark Slate
            }
        },
    },
    plugins: [forms],
};