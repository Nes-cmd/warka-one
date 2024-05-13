import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

let colors = require("tailwindcss/colors");

colors.mysecondary = {
    DEFAULT: "#f3a433",
    50: "#fef9ec",
    100: "##fcebc9",
    200: "#f9d58e",
    300: "#f5ba54",
    400: "#f3a433",
    500: "#ec7f14",
    600: "#d15c0e",
    700: "#ad3f10",
    800: "#8d3113",
    900: "#742913",
    950: "#421306",
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
                poppins: ["Poppins", "sans-serif"],
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
