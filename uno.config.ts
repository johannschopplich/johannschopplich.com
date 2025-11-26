import type { Theme } from "@unocss/preset-wind3";
import { variantMatcher } from "@unocss/preset-wind3/utils";
import {
  defineConfig,
  presetIcons,
  presetWind3,
  transformerDirectives,
} from "unocss";
import { rules } from "./src/unocss";

export default defineConfig<Theme>({
  cli: {
    entry: {
      patterns: ["site/{snippets,templates}/**/*"],
      outFile: "src/styles/uno.css",
    },
  },
  theme: {
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
      link: {
        DEFAULT: "var(--un-color-link)",
        hover: "var(--un-color-link-hover)",
      },
      theme: {
        base: "var(--un-color-text)",
        background: "var(--un-color-background)",
      },
      contrast: {
        lowest: "var(--un-color-contrast-lowest)",
        lower: "var(--un-color-contrast-lower)",
        low: "var(--un-color-contrast-low)",
        medium: "var(--un-color-contrast-medium)",
        high: "var(--un-color-contrast-high)",
        higher: "var(--un-color-contrast-higher)",
      },
    },
    borderRadius: {
      DEFAULT: "0.125rem",
    },
    fontSize: {
      xs: ["0.75rem", "var(--un-line-height-normal)"],
      sm: ["0.875rem", "var(--un-line-height-normal)"],
      base: ["1rem", "var(--un-line-height-normal)"],
      lg: ["var(--un-text-lg)", "var(--un-line-height-heading)"],
      xl: ["var(--un-text-xl)", "var(--un-line-height-heading)"],
      "2xl": ["var(--un-text-2xl)", "var(--un-line-height-heading)"],
      "3xl": ["var(--un-text-3xl)", "var(--un-line-height-heading)"],
      "4xl": ["var(--un-text-4xl)", "var(--un-line-height-heading)"],
    },
    fontFamily: {
      normal: "var(--un-font-family-normal)",
      heading: "var(--un-font-family-heading)",
    },
    lineHeight: {
      normal: "var(--un-line-height-normal)",
      heading: "var(--un-line-height-heading)",
    },
  },
  variants: [
    variantMatcher("un-light", (input) => ({
      prefix: `:root[data-theme="light"] $$ ${input.prefix}`,
    })),
    variantMatcher("un-dark", (input) => ({
      prefix: `:root[data-theme="dark"] $$ ${input.prefix}`,
    })),
  ],
  rules,
  shortcuts: [
    [/^column-(\d+)$/, ([, d]) => `flex-none w-${d}/12`],
    {
      title: "text-contrast-higher font-heading font-600 leading-heading",
      columns: "flex flex-wrap",
      column: "block flex-1",
      "column-narrow": "block flex-[0_0_auto] w-auto",
      "column-auto": "block flex-1 w-auto",
      "column-full": "block flex-none w-full",
      content: "px-lg md:px-[max(4vw,1.875rem)]",
      "content-l": "pl-lg md:pl-[max(4vw,1.875rem)]",
      "underline-default":
        "underline decoration-current decoration-size-[var(--un-decoration-thickness)] underline-offset-[var(--un-decoration-offset)]",
      headline:
        "text-contrast-higher text-size-2xl leading-[1.15] font-heading font-900 md:text-size-[calc(var(--un-text-4xl)+0.5vw)] md:leading-[1.05]",
      "section-divider": "h-[min(20svh,8rem)]",
      icon: "w-[1.25em] h-[1.25em] [&>svg]:w-full [&>svg]:h-full",
      tag: "text-size-xs leading-[1.05] font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 un-dark:border-contrast-low",
      "cta-button":
        "inline-flex items-center text-size-xs leading-[1.05] font-heading font-500 -m-2 p-2 uppercase tracking-[0.125ch]",
      "masonry-grid":
        "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-column-max-width,25rem),100%),1fr))] justify-center children:self-start", // grid-rows-[masonry]
    },
  ],
  safelist: [
    "sr-only",
    "invisible",
    "animated",
    "animated-tada",
    "i-tabler-brand-github",
    "i-tabler-brand-instagram",
    "i-tabler-brand-youtube",
    "i-tabler-brand-x",
    "i-tabler-brand-linkedin",
  ],
  transformers: [transformerDirectives()],
  presets: [
    presetWind3(),
    presetIcons({
      extraProperties: {
        display: "inline-block",
        height: "1.25em",
        width: "1.25em",
        "vertical-align": "text-bottom",
      },
    }),
  ],
});
