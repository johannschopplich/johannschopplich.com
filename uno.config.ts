import {
  defineConfig,
  presetIcons,
  presetWind,
  transformerDirectives,
} from "unocss";
import { presetDue } from "duecss";
import type { Theme } from "@unocss/preset-wind";
import { parseColor } from "@unocss/preset-mini/utils";

export default defineConfig<Theme>({
  theme: {
    maxWidth: {
      prose: "70ch",
    },
    colors: {
      primary: {
        DEFAULT: "#89937A",
        50: "#E3E5DF",
        100: "#D9DCD4",
        200: "#C5CABE",
        300: "#B1B8A7",
        400: "#9DA591",
        500: "#89937A",
        600: "#6D755F",
        700: "#505746",
        800: "#33382D",
        900: "#171914",
        950: "#080907",
      },
      secondary: {
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
    },
  },
  shortcuts: {
    content: "px-lg md:px-3xl",
    "content-breakout": "-mx-lg md:mx-0",
    icon: "inline-block w-[1.25em] h-[1.25em] align-text-bottom [&>svg]:w-full [&>svg]:h-full",
    "cta-button":
      "text-size-xs leading-[1.05] font-heading font-500 -m-2 p-2 uppercase tracking-[0.125ch]",
    tag: "text-size-xs leading-[1.05] font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 du-dark:border-contrast-low",
    headline:
      "text-contrast-higher text-size-2xl leading-[1.15] font-heading font-900 md:text-size-[calc(var(--du-text-4xl)+0.5vw)] md:leading-[1.05]",
    "masonry-grid":
      "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-col-max-w,25rem),100%),1fr))] justify-center children:self-start", // grid-rows-[masonry]
  },
  safelist: [
    "sr-only",
    "w-full",
    "aspect-[16/9]",
    "invisible",
    "animated",
    "animated-tada",
  ],
  transformers: [transformerDirectives()],
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
