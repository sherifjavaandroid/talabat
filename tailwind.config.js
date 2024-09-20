const defaultTheme = require("tailwindcss/defaultTheme");
const plugin = require("tailwindcss/plugin");
const Color = require("color");

module.exports = {
    future: {
        // removeDeprecatedGapUtilities: true,
        // purgeLayersByDefault: true,
    },
    darkMode: false,
    content: [
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php',
        './vendor/rappasoft/laravel-livewire-tables/resources/views/tailwind/**/*.blade.php',
        './resources/views/layouts/partials/styles.blade.php',
        './vendor/ambrosethebuild/envato-purchase-code-verifier/resources/views/**/*.blade.php',
        './vendor/ambrosethebuild/envato-purchase-code-verifier/resources/views/*.blade.php',
        // livewire select views
        './vendor/asantibanez/livewire-select/resources/views/*.blade.php',
    ],
    safelist: [
        'text-ellipsis', 'overflow-hidden', 'h-4',
        'break-all', 'w-64', 'md:w-3/12', 'w-48',
        'line-clamp-1', 'line-clamp-2', 'line-clamp-3',
        'truncate', 'w-4/12', 'w-10/12', 'w-8/12',
        'text-xs', 'line-through'
    ],
    theme: {
        extend: {
            width: {
                '72': '18rem',
                '80': '20rem',
                '88': '22rem',
                '96': '24rem',
            },
            height: {

                '72': '18rem',
                '80': '20rem',
                '88': '22rem',
                '96': '24rem',


                '1/2': '50%',

                '1/3': '33.333333%',
                '2/3': '66.666667%',

                '1/4': '25%',
                '2/4': '50%',
                '3/4': '75%',

                '1/5': '20%',
                '2/5': '40%',
                '3/5': '60%',
                '4/5': '80%',

                '1/6': '16.666667%',
                '2/6': '33.333333%',
                '3/6': '50%',
                '4/6': '66.666667%',
                '5/6': '83.333333%',

                '1/12': '8.333333%',
                '2/12': '16.666667%',
                '3/12': '25%',
                '4/12': '33.333333%',
                '5/12': '41.666667%',
                '6/12': '50%',
                '7/12': '58.333333%',
                '8/12': '66.666667%',
                '9/12': '75%',
                '10/12': '83.333333%',
                '11/12': '91.666667%',
            },
            fontFamily: {
                sans: ['Krub', ...defaultTheme.fontFamily.sans],
                body: ['Krub', 'sans-serif'],
            },
        },
        themeVariants: [],
        customForms: theme => ({
            default: {
                "input, textarea": {
                    "&::placeholder": {
                        color: theme("colors.gray.400")
                    }
                }
            }
        })
    },
    variants: {
        display: ["responsive"],
        textColor: ["focus-within", "hover", "focus", "active"],
        placeholderColor: ["focus"],
        borderColor: ["focus", "hover"],
        boxShadow: ["hover", "focus"],
        space: ['responsive', 'direction'],
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/line-clamp'),
        // plugin(({ addUtilities, e, theme, variants }) => {
        //   const newUtilities = {};
        //   Object.entries(theme("colors")).map(([name, value]) => {
        //     if (name === "transparent" || name === "current") return;
        //     const color = value[300] ? value[300] : value;
        //     const hsla = Color(color)
        //       .alpha(0.45)
        //       .hsl()
        //       .string();

        //     newUtilities[`.shadow-outline-${name}`] = {
        //       "box-shadow": `0 0 0 3px ${hsla}`
        //     };
        //   });

        //   addUtilities(newUtilities, variants("boxShadow"));
        // })
    ]
}
