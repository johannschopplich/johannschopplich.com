import { defineConfig, presetIcons } from "unocss";
import { presetWind } from "@unocss/preset-wind";
import { presetDue } from "duecss";

export default defineConfig({
  theme: {
    colors: {
      primary: {
        DEFAULT: "#C9787C",
        "50": "#FDFBFB",
        "100": "#F8EDED",
        "200": "#ECCFD1",
        "300": "#E0B2B5",
        "400": "#D59598",
        "500": "#C9787C",
        "600": "#B95055",
        "700": "#953C40",
        "800": "#6D2C2F",
        "900": "#451C1E",
      },
      accent: {
        DEFAULT: "#365DC9",
        "50": "#C7D2F0",
        "100": "#B7C5EC",
        "200": "#96ABE3",
        "300": "#7691DA",
        "400": "#5677D2",
        "500": "#365DC9",
        "600": "#2A499D",
        "700": "#1E3471",
        "800": "#122044",
        "900": "#060B18",
      },
    },
    // FIXME: UnoCSS won't overrride built-in sizes with [fontSize, lineHeight] syntax
    // Temporarily redeclare sizes from DueCSS
    fontSize: {
      xs: "0.75rem",
      sm: "0.875rem",
      base: "1rem",
      lg: "var(--du-text-lg)",
      xl: "var(--du-text-xl)",
      "2xl": "var(--du-text-2xl)",
      "3xl": "var(--du-text-3xl)",
      "4xl": "var(--du-text-4xl)",
    },
    boxShadow: {
      frame: "var(--du-shadow-frame)",
    },
  },
  shortcuts: {
    content: "px-lg md:px-3xl",
    "content-breakout": "-mx-lg md:mx-0",
    "action-button":
      "p-2 -m-2 text-size-xs font-heading leading-heading font-500 uppercase tracking-[0.125ch]",
    tag: "px-2 py-1 border-1 border-theme-base text-primary text-xs leading-normal font-heading font-500 leading-heading rounded-full whitespace-nowrap",
  },
  safelist: ["w-full", "aspect-ratio-16/9"],
  presets: [
    presetWind(),
    presetIcons({
      extraProperties: {
        display: "inline-block",
        height: "1.25em",
        width: "1.25em",
        "vertical-align": "text-bottom",
      },
    }),
    presetDue(),
  ],
});
