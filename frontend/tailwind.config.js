/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Roboto', 'sans-serif'],
      },
      margin: {
        'cart-top': '3.1rem',
      },
      padding: {
        'navigation-bottom': '30px',
      },
      height: {
        'carousel': '30rem',
      }
    },
  },
  plugins: [],
}