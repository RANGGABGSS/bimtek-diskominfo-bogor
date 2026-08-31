/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.jsx",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        bogor: {
          gold: '#FFD700',
          yellow: '#FACC15',
          navy: '#1E3A8A',
          darknavy: '#0F172A',
          blue: '#2563EB',
          green: '#10B981',
          darkgreen: '#047857',
        }
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'Inter', 'Roboto', 'sans-serif'],
        serif: ['"Times New Roman"', 'Merriweather', 'Georgia', 'serif'],
        dinas: ['"Times New Roman"', 'Georgia', 'serif'],
        cert: ['"Playfair Display"', 'Cinzel', 'serif'],
        signature: ['"Great Vibes"', 'cursive'],
        mono: ['"JetBrains Mono"', '"Roboto Mono"', 'monospace'],
      }
    },
  },
  plugins: [],
}
