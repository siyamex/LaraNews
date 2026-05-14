import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/View/Components/**/*.php',
    ],

    safelist: [
        // Alpine transition classes used dynamically
        'opacity-0', 'opacity-100',
        'scale-95', 'scale-100',
        '-translate-y-1', 'translate-y-0',
        'rotate-180',
        // Dark mode icon classes
        'dark:hidden', 'dark:block',
        // x-cloak
        '[x-cloak]',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                dhivehi: ['Noto Sans Dhivehi', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50:  '#fff1f1',
                    100: '#ffe1e1',
                    200: '#ffc7c7',
                    300: '#ffa0a0',
                    400: '#ff6767',
                    500: '#ff3535',
                    600: '#DC2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        a: { color: theme('colors.primary.600'), '&:hover': { color: theme('colors.primary.700') } },
                        blockquote: { borderLeftColor: theme('colors.primary.600') },
                    },
                },
                invert: {
                    css: {
                        a: { color: theme('colors.primary.400'), '&:hover': { color: theme('colors.primary.300') } },
                    },
                },
            }),
            animation: {
                'marquee': 'marquee 30s linear infinite',
                'marquee-rtl': 'marquee-rtl 30s linear infinite',
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
                'marquee-rtl': {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' },
                },
                fadeIn: {
                    '0%': { opacity: 0 },
                    '100%': { opacity: 1 },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: 0 },
                    '100%': { transform: 'translateY(0)', opacity: 1 },
                },
            },
        },
    },

    plugins: [forms, typography],
};
