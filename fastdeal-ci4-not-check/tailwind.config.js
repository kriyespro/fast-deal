/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./app/Controllers/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        dark: '#0f172a',
        paper: '#f8fafc',
        surface: '#ffffff',
        primary: '#1a2035',
        red: '#bb2821',
        accent: '#c8a14b',
        muted: '#8b949e'
      }
    }
  },
  plugins: [],
}
