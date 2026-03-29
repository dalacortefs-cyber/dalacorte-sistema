import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#e8f0f4',
          100: '#c5d7e2',
          200: '#9fbcce',
          300: '#78a1b9',
          400: '#5b8daa',
          500: '#3e789b',
          600: '#2d6485',
          700: '#1B3D50',
          800: '#162f3d',
          900: '#0f2029',
          950: '#060f1a',
          DEFAULT: '#1B3D50',
        },
        bronze: {
          50:  '#f5efe6',
          100: '#e4d4ba',
          200: '#d2b88d',
          300: '#c09c60',
          400: '#b48840',
          500: '#8B6B3D',
          600: '#7a5c32',
          700: '#634a28',
          800: '#4c381e',
          900: '#342614',
          DEFAULT: '#8B6B3D',
        },
        gold: {
          DEFAULT: '#C4A35A',
          light: '#D4B96E',
          dark: '#A8883A',
          muted: '#C4A35A33',
        },
        cream: {
          DEFAULT: '#F5F0E8',
          dark: '#EDE5D6',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        serif: ['Playfair Display', 'Georgia', 'serif'],
      },
      backgroundImage: {
        'gradient-dalacorte': 'linear-gradient(135deg, #1B3D50 0%, #2d6485 50%, #1B3D50 100%)',
        'gradient-bronze':    'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)',
        'gradient-hero':      'linear-gradient(160deg, #060f1a 0%, #0f2029 45%, #1B3D50 100%)',
        'gradient-section':   'linear-gradient(180deg, #060f1a 0%, #0d1d2b 100%)',
        'gradient-gold':      'linear-gradient(135deg, #C4A35A 0%, #D4B96E 50%, #A8883A 100%)',
        'gradient-card':      'linear-gradient(135deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.02) 100%)',
        'gradient-mirror':    'linear-gradient(135deg, rgba(196,163,90,0.15) 0%, rgba(196,163,90,0.02) 60%, transparent 100%)',
        'noise': "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E\")",
      },
      animation: {
        'fade-in':        'fadeIn 0.5s ease-in-out',
        'slide-up':       'slideUp 0.6s ease-out',
        'slide-in-right': 'slideInRight 0.5s ease-out',
        'float':          'float 6s ease-in-out infinite',
        'pulse-gold':     'pulseGold 2s ease-in-out infinite',
        'shimmer':        'shimmer 2.5s infinite',
        'glow-pulse':     'glowPulse 3s ease-in-out infinite',
        'gradient-x':     'gradientX 4s ease infinite',
      },
      keyframes: {
        fadeIn:      { from: { opacity: '0' },                                    to: { opacity: '1' } },
        slideUp:     { from: { opacity: '0', transform: 'translateY(30px)' },     to: { opacity: '1', transform: 'translateY(0)' } },
        slideInRight:{ from: { opacity: '0', transform: 'translateX(30px)' },     to: { opacity: '1', transform: 'translateX(0)' } },
        float:       { '0%, 100%': { transform: 'translateY(0)' },                '50%': { transform: 'translateY(-20px)' } },
        pulseGold:   { '0%, 100%': { boxShadow: '0 0 0 0 rgba(196,163,90,0.4)' },'50%': { boxShadow: '0 0 0 15px rgba(196,163,90,0)' } },
        shimmer: {
          '0%':   { transform: 'translateX(-100%) skewX(-12deg)' },
          '100%': { transform: 'translateX(250%) skewX(-12deg)' },
        },
        glowPulse: {
          '0%, 100%': { opacity: '0.4' },
          '50%':      { opacity: '0.9' },
        },
        gradientX: {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%':      { backgroundPosition: '100% 50%' },
        },
      },
      boxShadow: {
        'card':        '0 4px 24px rgba(27,61,80,0.08)',
        'card-hover':  '0 8px 40px rgba(27,61,80,0.16)',
        'gold':        '0 4px 20px rgba(196,163,90,0.3)',
        'gold-lg':     '0 8px 40px rgba(196,163,90,0.4)',
        'glow':        '0 0 30px rgba(196,163,90,0.25), 0 0 60px rgba(196,163,90,0.1)',
        'glass':       '0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.1)',
        'glass-hover': '0 16px 48px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.15)',
        'inner-glow':  'inset 0 1px 0 rgba(255,255,255,0.12), inset 0 -1px 0 rgba(0,0,0,0.2)',
      },
      backdropBlur: {
        xs: '2px',
      },
    },
  },
  plugins: [],
}
export default config
