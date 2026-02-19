/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './app/Views/**/*.php',
    './resources/**/*.{js,jsx,ts,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#2563eb',
        secondary: '#64748b',
        success: '#16a34a',
        warning: '#ea580c',
        danger: '#dc2626',
        light: '#f8fafc',
        dark: '#0f172a',
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
