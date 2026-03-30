import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                body:    ['"Plus Jakarta Sans"', 'system-ui', ...defaultTheme.fontFamily.sans],
                display: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                sans:    ['"Plus Jakarta Sans"', 'system-ui', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                purple: {
                    50:  '#F7F5FB',
                    100: '#EDE8F5',
                    200: '#C9BBE8',
                    700: '#5038A0',
                    800: '#3D2880',
                    900: '#2D1B69',
                    950: '#110A2E',
                },
                gold: {
                    50:  '#FFFCF0',
                    100: '#FEF5D4',
                    300: '#FADE8A',
                    400: '#F7C84D',
                    500: '#F5B731',
                    600: '#C8850A',
                },
                cream: {
                    DEFAULT: '#FAF8F3',
                    dark:    '#EDEAE2',
                },
            },
            borderRadius: {
                '2xl': '16px',
                'xl':  '12px',
            },
        },
    },

    plugins: [forms, typography],
};
