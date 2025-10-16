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
            // SEÇÃO DE CORES ADICIONADA
            colors: {
                'primaria': '#edb351',
                'texto-principal': '#1d1d1b',
                'fundo-claro': '#eae9e8',
                'branco': '#ffffff',
            },
        },
    },

    plugins: [forms],
};
