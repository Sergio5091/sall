import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                primary: '#3b2bee',
                'background-light': '#f6f6f8',
                'background-dark': '#121118',
                'accent-magenta': '#FF00FF',
                'accent-cyan': '#00FFFF',
                'accent-yellow': '#FDFD00',
                'accent-magenta-rgb': '255, 0, 255',
                'accent-cyan-rgb': '0, 255, 255',
                'accent-yellow-rgb': '253, 253, 0',
            },
            borderRadius: {
                'DEFAULT': '1rem',
                'lg': '2rem',
                'xl': '3rem',
                'full': '9999px',
            },
        },
    },

    plugins: [forms],
};
