import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

let colors = require("tailwindcss/colors");

colors.mysecondary = {
    DEFAULT: "#DEBC97",
    50: "#FFFFFF",
    100: "#FFFFFF",
    200: "#FBF8F4",
    300: "#F2E4D5",
    400: "#E8D0B6",
    500: "#DEBC97",
    600: "#D0A16C",
    700: "#C38542",
    800: "#9B6931",
    900: "#714C24",
    950: "#5C3E1D",
};
colors.myprimary = {
    DEFAULT: "#2c2768",
    50: "#eef1ff",
    100: "#e1e6fe",
    200: "#c8d1fd",
    300: "#a7b2fa",
    400: "#8389f6",
    500: "#6966ee",
    600: "#5849e2",
    700: "#4c3bc7",
    800: "#3e32a1",
    900: "#2c2768",
    950: "#211c4a",
};
/** @type {import('tailwindcss').Config} */
export default {
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
            colors: {
                danger: colors.red,
                primary: colors.myprimary,
                secondary: colors.mysecondary,
                success: colors.green,
                warning: colors.yellow,
                background: "#F9F7F4",
            },
        },
    },

    plugins: [forms],
};
