/** @type {import('tailwindcss').Config} */
export default {
  mode: "jit",
  content: [
    "./template-parts/header/*.php",
    "./template-parts/block/*.php",
    "./template-parts/footer/*.php",
    "./template-parts/content/*.php",
    "./header.php",
    "./footer.php",
    "./blocks/**/*.{php,js}",
  ],
  safelist: [
    "cursor-pointer", //Purpose: Forces Tailwind to always include these classes in your CSS, even if they aren't found in your content files.
  ],
  theme: {
    extend: {
      screens: { //Adds new responsive breakpoints beyond Tailwind's defaults (sm, md, lg, xl, 2xl).
        "2k": "2030px",
        "2.5k": "2500px",
        "4k": "3800px", //Usage: md:text-red-500, 2k:text-blue-500
      },
      fontFamily: {
        regular: ['"Arial Nova"', "sans-serif"],
        bold: ['"Arial Nova"', "sans-serif"],
        display: ['"Just Lovely"', "cursive"], //Usage: font-regular, font-display
      },
      colors: {
        red: "#98002e",
        black: "#000000",
        blue: "#00ACED",
        gray: "#CFD1D2",
        gray2: "#EFF0F0",
        gray3: "#6D6E71",
        gray4: "#A5A7AA",
        gray5: "#BABCBE",
        gray6: "#D3D2D2",
        gray7: "#414042", //Usage: text-red, bg-gray7
      },
      fontSize: {
        xxs: "0.675rem", //Usage: text-xxs
      },
      letterSpacing: {
        tighter: "-0.25rem", //Usage: tracking-tighter
      },
      gap: {
        15: "3.75rem", //Usage: gap-15
      },
    },
  },
  plugins: [],
};
