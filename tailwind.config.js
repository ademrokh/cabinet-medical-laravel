const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'media', // or 'class'

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    '50': '#f0f5ff',
                    '100': '#e0eaff',
                    '200': '#c8d9ff',
                    '300': '#a6c1ff',
                    '400': '#80a2ff',
                    '500': '#667eea',
                    '600': '#5a67d8',
                    '700': '#4c51bf',
                    '800': '#4247a3',
                    '900': '#363b85',
                    '950': '#222554'
                },
            },
            zIndex: {
                '-10': '-10',
            }
        },
    },

    plugins: [
        forms,
    ],
};
