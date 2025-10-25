import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },

            animation: {
                "fade-in-up": "fade-in-up 1s ease-out forwards",
                shine: "shine 6s linear infinite",
            },

            keyframes: {
                "fade-in-up": {
                    from: {
                        opacity: "0",
                        transform: "translateY(20px)",
                    },
                    to: {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                shine: {
                    from: { backgroundPosition: "200% 0" },
                    to: { backgroundPosition: "-200% 0" },
                },
            },
        },
    },

    plugins: [forms, require("daisyui")],

    daisyui: {
        themes: ["light", "dark"],
    },
};
