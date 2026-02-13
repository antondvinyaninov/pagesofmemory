import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        // Переопределяем стандартные цвета TailwindCSS точными значениями из PHP проекта
        
        // Основной цвет: #2c3e50 - это slate-700 в TailwindCSS
        slate: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#2c3e50',    // --primary из PHP проекта
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
        
        // Акцентный цвет: #e74c3c - это red-500 в TailwindCSS
        red: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#e74c3c',    // --accent из PHP проекта
          600: '#c0392b',    // --hover из PHP проекта
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
          950: '#450a0a',
        },
        
        // Серые цвета - переопределяем стандартные
        gray: {
          50: '#f8f9fa',     // --gray-light из PHP проекта
          100: '#eeeeee',    // --border из PHP проекта
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#666666',    // --text-light из PHP проекта
          600: '#4b5563',
          700: '#374151',
          800: '#333333',    // --text из PHP проекта
          900: '#111827',
          950: '#030712',
        },
        
        // Алиасы для удобства
        primary: '#2c3e50',   // slate-700
        accent: '#e74c3c',    // red-500
        
        // Совместимость с существующими стилями
        background: "var(--background)",
        foreground: "var(--foreground)",
      },
      fontFamily: {
        primary: ['Montserrat', 'sans-serif'],
        sans: ['Arial', 'sans-serif'],
      },
      boxShadow: {
        // Тени из PHP проекта
        card: '0 2px 4px rgba(0,0,0,0.1)',        // --card-shadow
        'card-hover': '0 4px 8px rgba(0,0,0,0.2)', // При наведении
        'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)', // Стандартная средняя тень
        'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)', // Большая тень
      },
      borderRadius: {
        card: '8px',
      },
      transitionProperty: {
        'card': 'transform, box-shadow',
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-out',
        'fade-in-delay-1': 'fadeIn 0.6s ease-out 0.2s both',
        'fade-in-delay-2': 'fadeIn 0.6s ease-out 0.4s both', 
        'fade-in-delay-3': 'fadeIn 0.6s ease-out 0.6s both',
        'slide-up': 'slideUp 0.3s ease-in-out',
        'pulse-gentle': 'pulse 2s ease-in-out infinite',
      },
      scrollbar: {
        'hide': {
          /* IE and Edge */
          '-ms-overflow-style': 'none',
          /* Firefox */
          'scrollbar-width': 'none',
          /* Safari and Chrome */
          '&::-webkit-scrollbar': {
            display: 'none'
          }
        }
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        pulse: {
          '0%, 100%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.02)' },
        },
      },
    },
  },
  plugins: [],
};
export default config;
