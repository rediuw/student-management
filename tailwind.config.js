import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // add this just in case you use Vue or Alpine later
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans], // keep this
                playfair: ['"Playfair Display"', 'serif'], // add this for fancy titles
                roboto: ['Roboto', 'sans-serif'], // add this for your main body font
            },
        },
    },

    plugins: [forms],
};
