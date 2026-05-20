<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        brand: { DEFAULT: '#1E376E', light: '#2a4a8f', dark: '#152a52' },
        accent: { DEFAULT: '#F5C518', soft: '#fde68a' },
      },
      fontFamily: {
        sans: ['Segoe UI', 'system-ui', 'sans-serif'],
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-12px)' },
        },
      },
      animation: {
        'float-slow': 'float 8s ease-in-out infinite',
        'float-delay': 'float 10s ease-in-out 2s infinite',
      },
    },
  },
};
</script>
