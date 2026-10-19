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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Poppins', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: 'var(--theme-primary, #3B82F6)',
                secondary: 'var(--theme-secondary, #8B5CF6)',
                accent: 'var(--theme-accent, #F59E0B)',
            },
        },
    },

    plugins: [forms],
};