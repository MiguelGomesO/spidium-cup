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
                brand: {
                    blue: '#031D76',
                    orange: '#FF5A00',
                    purple: '#4E2672',
                    ice: '#D1DAFF',
                    black: '#060C30',
                    surface: '#0A1548',
                    'blue-light': '#A6BFFF',
                    'orange-sand': '#FFC49A',
                    lilac: '#8D79A6',
                    urban: '#5A6780',
                    asphalt: '#1E3B3D',
                },
            },
        },
    },

    plugins: [
        forms({
            strategy: 'class',
        }),
    ],
};
