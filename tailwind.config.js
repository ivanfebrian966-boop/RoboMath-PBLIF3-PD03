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
                sans: ['Fredoka', 'Nunito', 'sans-serif'],
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
