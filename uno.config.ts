import { defineConfig, presetIcons, presetWind } from "unocss";
import { presetDue } from "duecss";
import type { Theme } from "@unocss/preset-wind";

export default defineConfig<Theme>({
  theme: {
    maxWidth: {
      prose: "70ch",
    },
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
    },
    lineHeight: {
      "heading-tight": "var(--du-line-height-heading-tight)",
    },
    boxShadow: {
      frame: "var(--du-shadow-frame)",
    },
  },
  shortcuts: {
    content: "px-lg md:px-3xl",
    "content-breakout": "-mx-lg md:mx-0",
    icon: "inline-block w-[1.25em] h-[1.25em] align-text-bottom [&>svg]:w-full [&>svg]:h-full",
    "cta-button":
      "text-size-xs leading-heading-tight font-heading font-500 -m-2 p-2 uppercase tracking-[0.125ch]",
    tag: "text-size-xs leading-heading-tight font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 du-dark:border-contrast-low",
    headline:
      "text-contrast-higher text-size-2xl leading-heading-tight font-heading font-900 md:text-size-[calc(var(--du-text-4xl)+0.5vw)]",
    "masonry-grid":
      "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-col-max-w,25rem),100%),1fr))] justify-center children:self-start", // grid-rows-[masonry]
  },
  safelist: [
    "sr-only",
    "w-full",
    "aspect-[16/9]",
    "invisible",
    "animate-bounce",
  ],
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
