/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#1B4F72',
                    dark: '#0D2B3E',
                    light: '#E8F1F8',
                },
                secondary: {
                    DEFAULT: '#F5F0E1',
                },
                success: {
                    DEFAULT: '#2E7D32',
                },
                warning: {
                    DEFAULT: '#F9A825',
                },
                danger: {
                    DEFAULT: '#C62828',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
        },
    },
    plugins: [],
}