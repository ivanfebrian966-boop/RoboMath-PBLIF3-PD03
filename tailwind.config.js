/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"SF Pro Display"', '"SF Pro Text"', '"SF Pro"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
                secondary: ['"SF Pro Display"', '"SF Pro Text"', '"SF Pro"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
                heading: ['Poppins', 'sans-serif'],
            },
            colors: {
                algored: '#FF4757',
                algopurple: '#70A1FF',
                algoorange: '#FFA502',
                algoyellow: '#ECCC68',
                algogreen: '#2ED573',
                algocyan: '#1E90FF',
                algopink: '#FF6B81',
            }
        },
    },
    plugins: [],
}
