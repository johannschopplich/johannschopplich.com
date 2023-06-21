import { defineConfig, presetIcons, presetWind } from "unocss";
import { presetDue } from "duecss";
import type { Theme } from "@unocss/preset-wind";

export default defineConfig<Theme>({
  theme: {
    // Increase prose width for Spectral
    // maxWidth: {
    //   prose: "75ch",
    // },
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
    boxShadow: {
      frame: "var(--du-shadow-frame)",
    },
  },
  shortcuts: {
    content: "px-lg md:px-3xl",
    "content-breakout": "-mx-lg md:mx-0",
    "cta-button":
      "text-size-xs leading-heading font-heading font-500 -m-2 p-2 uppercase tracking-[0.125ch]",
    tag: "text-size-xs leading-heading font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 du-dark:border-contrast-low",
    headline:
      "text-contrast-higher text-size-2xl leading-heading font-heading font-900 md:text-size-[calc(var(--du-text-4xl)+0.5vw)]",
    "masonry-grid":
      "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-col-max-w,25rem),100%),1fr))] justify-center children:self-start", // grid-rows-[masonry]
  },
  safelist: ["w-full", "aspect-[16/9]", "invisible"],
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
