const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                inherit: ['Proxima Nova', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {

                'login-banner': "url('/storage/banner/QA.jpg')",
            },
            borderRadius: {
                '4xl' : '2.0rem',
                '5xl' : '3.0rem',
                '6xl' : '4.0rem',
            },
            boxShadow:{
                'bottom' : '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
            },
        },
    },
    variants: {
        opacity: ({ after }) => after(['disabled'])
      },

    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/line-clamp'),
        require('tailwind-scrollbar'),
    ],

    variants: {
        scrollbar: ['rounded']
    },
};
